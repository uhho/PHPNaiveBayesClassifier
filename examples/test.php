<?php

require_once('../lib/NBC.php');

$nbc = new NBC();

/**
 * training data comes from "Nineteen Eighty-Four" written by George Orwell
 * @link http://en.wikiquote.org/wiki/Nineteen_Eighty-Four
 */
$nbc->train(new FileDataSource('polish.txt'), 'polish');
$nbc->train(new FileDataSource('english.txt'), 'english');
$nbc->train(new FileDataSource('japanese.txt'), 'japanese');

echo $nbc->classify("This color is very bright.") . PHP_EOL;
echo $nbc->classify("人生　の　意味　は　何　です　か？") . PHP_EOL;
echo $nbc->classify("Byłem ostatni w kolejce") . PHP_EOL;

?>