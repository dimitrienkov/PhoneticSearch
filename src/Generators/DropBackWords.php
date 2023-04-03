<?php

namespace PhoneticSearch\Generators;

use PhoneticSearch\GeneratorInterface;

class DropBackWords implements GeneratorInterface
{
    //Индекс релевантности результата
    private int $relevance = 1;

    //Список окончаний для удаления
    private string $backWords = 'ый|ой|ая|ое|ые|ому|а|о|у|е|ого|ему|и|ство|ых|ох|ия|ий|ь|я|он|ют|ат';

    /**
     * @param string $word
     * @return string|null
     *
     * Метод для получения слова с удаленным окончанием
     */
    public function getString(string $word): ?string
    {
        $newWord = preg_replace(sprintf("/(%s)$/i", $this->backWords), '', $word);

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