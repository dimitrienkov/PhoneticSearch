<?php

namespace PhoneticSearch;

use ReflectionClass;
use ReflectionException;

class StringVariations
{
    //Массив генераторов, с помощью которых будет происходит создание вариаций запроса (useGenerators для переопределения)
    private array $generators = [
        \PhoneticSearch\Generators\DropVowels::class,
        \PhoneticSearch\Generators\StringSplitting::class,
        \PhoneticSearch\Generators\Translit::class,
        \PhoneticSearch\Generators\RuWordInEnLayout::class,
        \PhoneticSearch\Generators\DropBackWords::class,
        \PhoneticSearch\Generators\YandexSpeller::class,
    ];

    //Учитывать преобразование результата транслитерации
    private bool $considerTranslitConversion = false;

    //Учитывать преобразование резульатат преобразования русского слова на англ. раскладке
    private bool $considerRuWordsInEnLayout = false;

    //Уникализация результатов
    private bool $uniqueResults = false;

    //Минимальная длина строки для преобразования
    private int $minLen = 3;

    /**
     * @param string $word
     * @param bool $enableAddWordToResult
     * @return array
     *
     * Метод создаёт массив слов для поиска по изначальному слову используя генераторы (Generators)
     * Это позволяет использовать сгенерированный массив для поиска по БД с учетом ошибок
     *
     * Например, изначальный запрос: обркс
     * Метод предоставит готовый массив строк, который поможет найти 'абрикос' в БД
     */

    private function generate(string $word, bool $enableAddWordToResult = true): ?array
    {
        if (!$word) {
            return null;
        }

        if ($enableAddWordToResult) {
            $arResult = [
                [
                    'string' => $word,
                    'relevance' => 1
                ]
            ];
        }

        if (mb_strlen($word) < $this->minLen) {
            return $arResult;
        }

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

        $isEnglishWord = Language::isEnglish($word);

        if ($this->considerTranslitConversion && $isEnglishWord) {
            if ($translitedWord = Language::translit($word)) {
                if ($newResult = $this->generate($translitedWord, false)) {
                    $result = [
                        ...$result,
                        ...$newResult
                    ];
                }
            }
        }

        if ($this->considerRuWordsInEnLayout && $isEnglishWord) {
            if ($fixedWord = Language::convertRuStringInEnLayout($word)) {
                if ($newResult = $this->generate($fixedWord, false)) {
                    $result = [
                        ...$result,
                        ...$newResult
                    ];
                }
            }
        }

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

        if ($this->uniqueResults) {
            $arResult = array_diff_assoc($arResult, array_unique($arResult));
        }

        return $arResult;
    }

    /**
     * @param bool $flag
     * @return void
     *
     * Метод включает учет результата транслитерации слова
     *
     * Т.е. если определено, что слово введено на английском, происходит попытка транслитерации слова,
     * и повторной генерации с помощью всех доступных методов для полученного слова на русском языке
     */

    public function considerTranslitConversion(bool $flag = true): void
    {
        $this->considerTranslitConversion = $flag;
    }

    /**
     * @param bool $flag
     * @return void
     *
     * Метод включае учёт резульатат преобразования русского слова введенного в англ. раслкладке
     *
     * Т.е. если определено, что слово введено в английской раскладке, происходит повторная генерация вариаций для него
     */

    public function considerRuWordsInEnLayout(bool $flag = true): void
    {
        $this->considerRuWordsInEnLayout = $flag;
    }

    /**
     * @param bool $flag
     * @return void
     *
     * Метод включает/выключает уникализацию полученных результатов
     */

    public function setUniqueResults(bool $flag = true): void
    {
        $this->uniqueResults = $flag;
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