<?php

require 'vendor/autoload.php';

$wordsGenerator = new \PhoneticSearch\WordsGenerator();

$string = 'abrikos';
$result = $wordsGenerator->generateFromString($string);

echo '<pre>';
print_r($result);
