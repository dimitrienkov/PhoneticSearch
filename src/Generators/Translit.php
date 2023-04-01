<?php

namespace PhoneticSearch\Generators;

use PhoneticSearch\GeneratorInterface;
use PhoneticSearch\Language;

class Translit implements GeneratorInterface
{
    private int $relevance = 1; //Индекс релевантности результата

    /**
     * @param string $word
     * @return string|null
     *
     * Метод производит транслитерацию слово используя заданный массив $arReplace
     */

    public function getString(string $word): ?string
    {
        $newWord = Language::translit($word);

        if (!$newWord || $newWord == $word) {
            return null;
        }

        return $newWord;
    }

    /**
     * @param string $word
     * @return array|null
     *
     *  Метод возвращает результат преобразования в виде массиве содеражего итоговое слово с индексом релевантности
     */

    public function getResult(string $word): ?array
    {
        $newWord = $this->getString($word);

        if (!$newWord) {
            return null;
        }

        return [
            'string' => $newWord,
            'relevance' => $this->relevance
        ];
    }
}