<?php

require 'vendor/autoload.php';

$start = microtime(true);

$stringVariations = new \PhoneticSearch\StringVariations();
$stringVariations->considerTranslitConversion();
$stringVariations->considerRuWordsInEnLayout();
$stringVariations->setUniqueResults();


$query = 'мучисты ррастене';

echo '<pre>Initial query: ' . $query;

$result = $stringVariations->generateFromString($query);

$diff = microtime(true) - $start;

$sortRelevance = function ($a, $b) {
    return $a['relevance'] > $b['relevance'];
};

usort($result, $sortRelevance);

echo '<pre>';
print_r('Diff: '.$diff);

echo '<pre>';
print_r($result);
