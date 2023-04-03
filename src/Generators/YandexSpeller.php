<?php

namespace PhoneticSearch\Generators;

use PhoneticSearch\GeneratorInterface;

class YandexSpeller implements GeneratorInterface
{
    //Индекс релевантности результата
    private int $relevance = 1;

    //Yandex Speller API Endpoint
    const apiEndpoint = 'https://speller.yandex.net/services/spellservice.json/checkText';

    /**
     * @param string $word
     * @return string|null
     *
     * Метод для получения преобразованной строки
     */

    public function getString(string $word): ?string
    {
        // TODO: Implement getString() method.

        return null;
    }

    /**
     * @param string $word
     * @return string|array|null
     *
     *  Метод возвращает результат преобразования в виде массиве содеражего итоговое слово с индексом релевантности
     */

    public function getResult(string $word): ?array
    {
        $correctedWords = $this->getCorrected($word);

        if (!$correctedWords) {
            return null;
        }

        $arResult = [];

        foreach ($correctedWords as $correctedWord) {
            $arResult[] = [
                'string' => $correctedWord,
                'relevance' => $this->relevance
            ];
        }

        return $arResult;
    }

    /**
     * @param string|null $word
     * @return array|null
     *
     * Метод для отправки запроса к сервису Yandex Speller
     *
     * https://yandex.ru/dev/speller/doc/dg/reference/checkText.html
     */

    private function getCorrected(?string $word): ?array
    {
        if (!$word) {
            return null;
        }

        $data = http_build_query([
            'text' => $word
        ]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, self::apiEndpoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result, true);

        if ($corrected = $result[0]['s']) {
            return $corrected;
        }

        return null;
    }
}
