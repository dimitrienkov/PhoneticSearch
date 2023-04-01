<?php

namespace PhoneticSearch;

class WordsGenerator
{
    private array $generators = [
        \PhoneticSearch\Generators\DropVowels::class
    ];

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @param string $query
     * @return array
     *
     * Метод создаёт массив слов для поиска по изначальному слову используя генераторы (Generators)
     * Это позволяет использовать сгенерированный массив для поиска по БД с учетом ошибок
     *
     * Например, изначальный запрос: обркс
     * Метод предоставит готовый массив строк, который поможет найти 'абрикос' в БД
     */
    public function generate(string $query): array
    {
        $arResult = [];

        $query = trim($query);

        foreach ($this->generators as $generator) {
            $generator = new $generator;

            if ($result = $generator->getString($query)) {
                $arResult[] = $result;
            }
        }

        return $arResult;
    }
}