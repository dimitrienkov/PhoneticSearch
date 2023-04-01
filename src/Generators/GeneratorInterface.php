<?php

namespace PhoneticSearch\Generators;

interface GeneratorInterface
{
    /**
     * @param string $string
     * @return string|null
     *
     * Метод для получения преобразованной строки
     */
    public function getString(string $string): ?string;
}