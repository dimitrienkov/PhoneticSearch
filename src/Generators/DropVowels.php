<?php

namespace PhoneticSearch\Generators;

class DropVowels implements GeneratorInterface
{
    private string $removeVowels = 'ауоыэяюёиеьъ';

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
     * @param string $string
     * @return string|null
     *
     *  Метод удаляет все гласные буквы из строки (список гласных букв настраивается в свойстве removeVowels)
     */
    public function getString(string $string): ?string
    {
        $string = mb_strtolower($string);
        $string = preg_replace("/[{$this->removeVowels}]/ui", '', $string);

        if (mb_strlen($string) < $this->minLen) {
            return null;
        }

        return $string;
    }
}