<?php

namespace SamParkinson\Behat\Test;

use Exception;
use Hamcrest\MatcherAssert;
use PHPUnit_Framework_TestCase;

/**
 * A test case that links hamcrests assertion count with PHPUnit.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class HamcrestTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function runBare()
    {
        MatcherAssert::resetCount();

        try {
            parent::runBare();
        } catch (Exception $e) {
            // rethrown below
        }

        $this->addToAssertionCount(MatcherAssert::getCount());

        if (isset($e)) {
            throw $e;
        }
    }
}
