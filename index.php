<?php

require 'vendor/autoload.php';

$stringVariations = new \PhoneticSearch\StringVariations();
$stringVariations->considerTranslitConversion();
$stringVariations->considerRuWordsInEnLayout();
$stringVariations->setUniqueResults();


$string = 'f,hbrjcjdsq';
$result = $stringVariations->generateFromString($string);

echo '<pre>';
print_r($result);
