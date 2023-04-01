<?php

namespace PhoneticSearch\Generators;

interface GeneratorInterface
{

    /**
     * @param string $word
     * @return string|array|null
     *
     * Метод для получения преобразованной строки
     */
    public function getResult(string $word);

}