<?php

namespace PhoneticSearch\Generators;

class DropVowels implements GeneratorInterface
{
    private int $relevance = 1; //Индекс релевантности результата

    private string $charsVowels = 'ауоыэяюёиеьъ';

    private int $minLen = 3;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if ($params) {
            foreach ($params as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @param string $word
     * @return array|null
     *
     *  Метод удаляет все гласные буквы из строки (список гласных букв настраивается в свойстве removeVowels)
     */
    public function getResult(string $word): ?array
    {
        $word = mb_strtolower($word);
        $word = preg_replace("/[{$this->charsVowels}]/ui", '', $word);

        if (mb_strlen($word) < $this->minLen) {
            return null;
        }

        return [
            'string' => $word,
            'relevance' => $this->relevance,
        ];
    }

}