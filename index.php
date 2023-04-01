<?php

require 'vendor/autoload.php';

$wordsGenerator = new \PhoneticSearch\WordsGenerator();

$result = $wordsGenerator->generate('проверка генерации');

echo '<pre>';
print_r($result);
