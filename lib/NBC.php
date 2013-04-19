<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DataSource.php';

/**
 * Naive Bayes Classifier
 *
 * @author Lukasz Krawczyk <contact@lukaszkrawczyk.eu>
 * @copyright Copyright © 2013 Lukasz Krawczyk
 * @license MIT
 * @link http:/www.lukaszkrawczyk.eu
 */
class NBC {

    private $vocabulary = array();
    private $classes = array();
    private $classTokenCounter = array();
    private $classDocumentCounter = array();
    private $tokenCounter = 0;
    private $documentCounter = 0;

    /**
     * Training classificator
     *
     * @param IDataSource $dataSource
     * @param string $class
     */
    public function train(IDataSource $dataSource, $class) {
        // class initialization
        if (!in_array($class, $this->classes)) {
            $this->classes[] = $class;
            $this->classTokenCounter[$class] = 0;
            $this->classDocumentCounter[$class] = 0;
        }

        // train class using provided documents
        while ($document = $dataSource->getNextDocument()) {
            $this->documentCounter++;
            $this->classDocumentCounter[$class]++;

            // add all documents tokens to global vocabulary
            foreach ($this->tokenise($document) as $token) {
                $this->vocabulary[$token][$class] = isset($this->vocabulary[$token][$class])
                    ? $this->vocabulary[$token][$class] + 1
                    : 1;
                $this->classTokenCounter[$class]++;
                $this->tokenCounter++;
            }
        }
    }

    /**
     * Classifying document
     *
     * @param string $document
     * @param boolean $showProbabilities - return posterior probabilities as a result
     * @return string | array
     */
    public function classify($document, $showProbabilities = false) {

        $tokens = $this->tokenise($document);
        $posteriors = array();

        // for each class count posterior probability
        foreach ($this->classes as $class) {
            $posteriors[$class] = $this->posterior($tokens, $class);
        }

        arsort($posteriors);
        return ($showProbabilities) ? $posteriors : key($posteriors);
    }

    /**
     * Counting posterior probability
     *
     * @param array $tokens
     * @param string $class
     * @return float
     */
    private function posterior($tokens, $class) {
        $posterior = 1;
        foreach ($tokens as $token) {
            $count = isset($this->vocabulary[$token][$class])
                ? $this->vocabulary[$token][$class]
                : 0;
            // multiply by token probability, add Laplace smoothing
            $posterior *= ($count + 1) / ($this->classTokenCounter[$class] + $this->tokenCounter);
        }
        $posterior = $this->prior($class) * $posterior;
        return $posterior;
    }

    /**
     * Counting prior probability for given class
     *
     * @param string $class
     * @return float
     */
    private function prior($class) {
        return $this->classDocumentCounter[$class] / $this->documentCounter;
    }

    /**
     * Tokenize given text
     * Only for strings divided by space
     *
     * @param string $text
     * @return array
     */
    private function tokenise($text) {
        mb_internal_encoding("utf-8");
        mb_regex_encoding("utf-8");
        $text = mb_strtolower(mb_convert_kana($text, 'as'));
        // remove all non alphanumeric characters from string
        $text = mb_ereg_replace("[;,.\-\–\?\!。、？！]+", '', $text);
        return preg_split('/\s+/', $text);
    }
}

?>