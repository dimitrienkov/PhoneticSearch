<?php

require 'vendor/autoload.php';

$stringVariations = new \PhoneticSearch\StringVariations();
$stringVariations->considerTranslitConversion();


$string = 'abrikos';
$result = $stringVariations->generateFromString($string);

echo '<pre>';
print_r($result);
