<?php

namespace PhoneticSearch\Generators;

use PhoneticSearch\GeneratorInterface;
use PhoneticSearch\Language;

class RuWordInEnLayout implements GeneratorInterface
{
    //Индекс релевантности результата
    private int $relevance = 1;

    /**
     * @param string $word
     * @return string|null
     *
     * Метод прозводит преобразование русского слова, набранного в английской раскладке
     */

    public function getString(string $word): ?string
    {
        $newWord = Language::convertRuStringInEnLayout($word);

        if (!$newWord || $newWord == $word) {
            return null;
        }

        return $newWord;
    }

    /**
     * @param string $word
     * @return string|array|null
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