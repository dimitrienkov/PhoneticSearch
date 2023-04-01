<?php

namespace PhoneticSearch;

use Exception;
use PhoneticSearch\Generators\GeneratorInterface;
use ReflectionClass;
use ReflectionException;

class WordsGenerator
{
    private string $translitClass = \PhoneticSearch\Generators\Translit::class;

    private array $generators = [
        \PhoneticSearch\Generators\DropVowels::class,
        \PhoneticSearch\Generators\StringSplitting::class,
    ];

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $arGenerators = [
            ...$this->generators,
            $this->translitClass
        ];

        try {
            $this->useGenerators($arGenerators);
        } catch (Exception $e) {
            var_dump($e);

            throw new Exception($e);
        }
    }

    /**
     * @param string $word
     * @return array
     *
     * Метод создаёт массив слов для поиска по изначальному слову используя генераторы (Generators)
     * Это позволяет использовать сгенерированный массив для поиска по БД с учетом ошибок
     *
     * Например, изначальный запрос: обркс
     * Метод предоставит готовый массив строк, который поможет найти 'абрикос' в БД
     */

    private function generate(string $word): ?array
    {
        $arResult = [
            [
                'string' => $word,
                'relevance' => 1
            ]
        ];

        $word = trim($word);

        foreach ($this->generators as $generator) {
            $generator = new $generator;

            if ($result = $generator->getResult($word)) {
                if ($result['string']) {
                    $arResult[] = $result;
                } else {
                    $arResult = [
                        ...$arResult,
                        ...$result
                    ];
                }
            }
        }

        $translitInstance = new $this->translitClass;

        if ($translitInstance->isEnglish($word)) {
            $translitedWord = $translitInstance->getString($word);
            $result = $this->generate($translitedWord);

            $arResult = [
                ...$arResult,
                ...$result
            ];
        }

        return $arResult;
    }

    /**
     * @param string $word
     * @return array
     *
     * Метод ипользуя generate из слова генерирует массив слов для поиска по БД
     */

    public function generateFromWord(string $word): ?array
    {
        $result = $this->generate($word);

        return $result ?: null;
    }

    /**
     * @param string $string
     * @return array|null
     *
     * Метод ипользуя generateFromWord из строки генерирует массив слов для поиска по БД
     */

    public function generateFromString(string $string): ?array
    {
        if (!$string = trim($string)) {
            return null;
        }

        $arResult = [];

        if ($words = explode(' ', $string)) {
            foreach ($words as $word) {
                if ($result = $this->generateFromWord($word)) {

                    $arResult = [
                        ...$arResult,
                        ...$result
                    ];
                }
            }
        }

        return $arResult;
    }

    /**
     * @param array $generators
     * @return void
     *
     *  Метод позволяет установить нужные вам генераторы для использования при обработке запроса
     * @throws ReflectionException
     */

    public function useGenerators(array $generators): void
    {
        $arGenerators = [];

        foreach ($generators as $generator) {
            $reflect = new ReflectionClass($generator);

            if ($reflect->implementsInterface(GeneratorInterface::class)) {
                $arGenerators[] = $generator;
            }
        }

        $this->generators = $arGenerators;
    }

}