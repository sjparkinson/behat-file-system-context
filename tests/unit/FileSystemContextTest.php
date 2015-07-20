<?php

/**
 * This file is part of sjparkinson\behat-file-system-context.
 *
 * Copyright (c) 2015 Samuel Parkinson <sam.james.parkinson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license http://github.com/sjparkinson/behat-file-system-context/blob/master/LICENSE MIT
 */

use Behat\Gherkin\Node\PyStringNode;
use SamParkinson\Behat\Context\FileSystemContext;
use SamParkinson\Behat\Test\HamcrestTestCase;

/**
 * Tests the FileSystemContext class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class FileSystemContextTest extends HamcrestTestCase
{
    /**
     * @var FileSystemContext
     */
    protected $context;

    /**
     * @var string
     */
    protected $scheme = 'test://';

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->context = new FileSystemContext($this->scheme);
    }

    /**
     * @dataProvider filenamesProvider
     *
     * @param string $filename
     * @param string $expected
     */
    public function testTransformFilenameShouldAppendVirtualScheme($filename, $expected)
    {
        $transformed = $this->context->transformFilenames($filename);

        assertThat('The transformed filename does not match what was expected.', $expected, is(identicalTo($transformed)));
    }

    /**
     * Provide filenames and expected result after transformation.
     *
     * @return array
     */
    public function filenamesProvider()
    {
        return [
            ['/foo.txt', 'test:///foo.txt'],
            ['/foo/bar.php', 'test:///foo/bar.php'],
            ['/foo/bar/', 'test:///foo/bar/'],
        ];
    }
}
