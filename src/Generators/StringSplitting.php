<?php

namespace PhoneticSearch\Generators;

class StringSplitting implements GeneratorInterface
{
    private int $minLen = 6;

    public function getResult(string $word): ?array
    {
        $strLen = mb_strlen($word);

        if ($strLen < $this->minLen) {
            return null;
        }

        $arResult = [];

        if ($strLen > 6 && $strLen <= 9) {
            if ($result = $this->splitString($word, 3)) {
                $arResult = array_merge($arResult, $this->genResult($result, 4));
            }
        }

        if ($strLen >= 10 && $strLen <= 12) {
            if ($result = $this->splitString($word, 4)) {
                $arResult = array_merge($arResult, $this->genResult($result, 3));
            }
        }

        if ($strLen >= 13) {
            if ($result = $this->splitString($word, 5)) {
                $arResult = array_merge($arResult, $this->genResult($result, 2));
            }
        }

        return $arResult;
    }

    private function splitString(string $word, int $len): ?array
    {
        if (!$word || !$len) {
            return null;
        }

        $arSplit = array_chunk(preg_split('//u', $word, null, PREG_SPLIT_NO_EMPTY), $len);

        if (!$arSplit) {
            return null;
        }

        $arStrings = [];

        foreach ($arSplit as $item) {
            if (count($item) >= $len) {
                $arStrings = array_merge($arStrings, [implode('', $item)]);
            }
        }

        return $arStrings;
    }

    /**
     * @param string $string
     * @param int $relevance
     * @return array|null
     *
     * Метод генерирует массив с результатом, содеражащий строку результата и индекст релевентности
     */
    private function genResult(array $syllables, int $relevance): ?array
    {
        if (!$syllables || !$relevance) {
            return null;
        }

        $arResult = [];

        foreach ($syllables as $syllable) {
            $arResult[] = [
                'string' => $syllable,
                'relevance' => $relevance
            ];
        }

        return $arResult;
    }
}