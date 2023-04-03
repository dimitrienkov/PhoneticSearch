<?php

namespace PhoneticSearch;

interface GeneratorInterface
{
    /**
     * @param string $word
     * @return string|null
     *
     * Метод для получения преобразованной строки
     */

    public function getString(string $word): ?string;

    /**
     * @param string $word
     * @return string|array|null
     *
     *  Метод возвращает результат преобразования в виде массиве содеражего итоговое слово с индексом релевантности
     */

    public function getResult(string $word): ?array;
}