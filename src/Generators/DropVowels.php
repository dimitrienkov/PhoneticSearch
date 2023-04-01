<?php

namespace PhoneticSearch\Generators;

use PhoneticSearch\GeneratorInterface;

class DropVowels implements GeneratorInterface
{
    private int $relevance = 1; //Индекс релевантности результата

    private string $charsVowels = 'ауоыэяюёиеьъ'; //Список символов которые буду удалены из строки

    private int $minLen = 3; //Минимальная длина слова для преобразования

    /**
     * @param string $word
     * @return string|null
     *
     *  Метод удаляет все гласные буквы из строки (список гласных букв настраивается в свойстве removeVowels)
     */

    public function getString(string $word): ?string
    {
        $word = mb_strtolower($word);
        $word = preg_replace(sprintf("/[%s]/ui", $this->charsVowels), '', $word);

        if (mb_strlen($word) < $this->minLen) {
            return null;
        }

        return $word;
    }

    /**
     * @param string $word
     * @return array|null
     *
     *  Метод возвращает результат преобразования в виде массиве содеражего итоговое слово с индексом релевантности
     */

    public function getResult(string $word): ?array
    {
        $word = $this->getString($word);

        if (!$word) {
            return null;
        }

        return [
            'string' => $word,
            'relevance' => $this->relevance,
        ];
    }
}