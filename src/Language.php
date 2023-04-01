<?php

namespace PhoneticSearch;

class Language
{
    //Регулярное выражение для опредления английских слов
    private static string $charsEn = 'a-zA-Z0-9\s`~!@#$%^&*()_+-={}|:;<>?,.\/\"\'\\\[\]';

    //Регулярное выражение для определения русских слов
    private static string $charsRu = 'А-Яа-яЁё0-9\s`~!@#$%^&*()_+-={}|:;<>?,.\/\"\'\\\[\]';

    //Массив для транслитерации
    private static array $charsTranslit = [
        'a' => 'а', 'b' => 'б', 'v' => 'в', 'g' => 'г', 'd' => 'д', 'e' => 'е', 'yo' => 'ё',
        'j' => 'ж', 'z' => 'з', 'i' => 'и', 'k' => 'к',
        'l' => 'л', 'm' => 'м', 'n' => 'н', 'o' => 'о', 'p' => 'п', 'r' => 'р', 's' => 'с', 't' => 'т',
        'y' => 'у', 'f' => 'ф', 'h' => 'х', 'c' => 'ц',
        'ch' => 'ч', 'sh' => 'ш', 'u' => 'у', 'ya' => 'я',
    ];

    //Массив для преобразования русского слова введенного на английской раскладке
    private static array $charsRuWordInEn = [
        'q' => 'й', 'w' => 'ц', 'e' => 'у', 'r' => 'к', 't' => 'е', 'y' => 'н', 'u' => 'г',
        'i' => 'щ', 'p' => 'з', 'a' => 'ф', 's' => 'ы', 'd' => 'в',
        'f' => 'а', 'g' => 'п', 'h' => 'р', 'j' => 'о', 'k' => 'л', 'l' => 'д', 'z' => 'я', 'x' => 'ч',
        'c' => 'с', 'v' => 'м', 'b' => 'и', 'n' => 'т', 'm' => 'ь', '[' => 'х', ']' => 'ъ', ';' => 'ж', '\'' => 'э',
        ',' => 'б', '.' => 'ю'
    ];

    /**
     * @param string $string
     * @return string
     *
     * Метод выполняет транслитерацию с помощью массива $charsTranslit
     */

    public static function translit(string $string): string
    {
        return strtr($string, self::$charsTranslit);
    }

    /**
     * @param string $string
     * @return string
     *
     * Метод выполняет преобразование русского слова введенного на английской раскладке,
     * используя массив $charsRuWordInEn
     */

    public static function convertRuStringInEnLayout(string $string): string
    {
        return strtr($string, self::$charsRuWordInEn);
    }

    /**
     * @param string $string
     * @return bool
     *
     * Метод определяет относится ли слово к английскому языку
     */

    public static function isEnglish(string $string): bool
    {
        $chars = self::$charsEn;

        return (bool)preg_match("/^[{$chars}]+$/", $string);
    }

    /**
     * @param string $string
     * @return bool
     *
     * Метод определяет относится ли слово к русскому языку
     */

    public static function isRussian(string $string): bool
    {
        $chars = self::$charsRu;

        return (bool)preg_match("/^[{$chars}]+$/u", $string);
    }
}