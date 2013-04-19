# Naive Bayes Classifier for PHP #

Naive Bayes Classifier is a probabilistic classifier of previously unseen data based around the Bayes theorem rule.
Generally, other (more complex) algorithms giving better accuracy, but if NBC is trained well on a large data set it can give suprisingly good results for much less effort.

Naive Bayes Classifier may be used in:
- Spam detection
- Automatic assignment of categories to a set of items
- Automatic detection of the primary language

Learn more about Naive Bayes Classifier at https://en.wikipedia.org/wiki/Naive_Bayes_classifier

## Usage ##
```php
require_once('../lib/NBC.php');

$nbc = new NBC();

$nbc->train(new FileDataSource('polish.txt'), 'polish');
$nbc->train(new FileDataSource('english.txt'), 'english');
$nbc->train(new FileDataSource('japanese.txt'), 'japanese');

echo $nbc->classify("This color is very bright.") . PHP_EOL;
echo $nbc->classify("人生　の　意味　は　何　です　か？") . PHP_EOL;
echo $nbc->classify("Byłem ostatni w kolejce.");
```

## Sample output ##
```php
'english'
'japanese'
'polish'
```

## Further releases ##
- Saving trained classifier into file
- Tokenizer improvement (now working only with documents where tokens are separated by space)
- MySQL data source
- MongoDB data source
