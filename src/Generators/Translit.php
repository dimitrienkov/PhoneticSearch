<?php

namespace PhoneticSearch\Generators;

class Translit implements GeneratorInterface
{
    private int $relevance = 1; //Индекс релевантности результата

    private array $arReplace = [
        'a' => 'а', 'b' => 'б', 'v' => 'в', 'g' => 'г', 'd' => 'д', 'e' => 'е', 'yo' => 'ё',
        'j' => 'ж', 'z' => 'з', 'i' => 'и', 'k' => 'к',
        'l' => 'л', 'm' => 'м', 'n' => 'н', 'o' => 'о', 'p' => 'п', 'r' => 'р', 's' => 'с', 't' => 'т',
        'y' => 'у', 'f' => 'ф', 'h' => 'х', 'c' => 'ц',
        'ch' => 'ч', 'sh' => 'ш', 'u' => 'у', 'ya' => 'я',
    ];

    private string $charsEn = 'a-zA-Z0-9\s`~!@#$%^&*()_+-={}|:;<>?,.\/\"\'\\\[\]';

    private string $charsRu = 'А-Яа-яЁё0-9\s`~!@#$%^&*()_+-={}|:;<>?,.\/\"\'\\\[\]';

    public function getString(string $word): ?string
    {
        $newWord = strtr($word, $this->arReplace);

        if (!$newWord || $newWord == $word) {
            return null;
        }

        return $newWord;
    }

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

    public function isEnglish(string $string): bool
    {
        return (bool)preg_match("/^[{$this->charsEn}]+$/", $string);
    }

    public function isRussian(string $string): bool
    {
        return (bool)preg_match("/^[{$this->charsRu}]+$/u", $string);
    }
}