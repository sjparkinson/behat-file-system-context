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
        $this->context->getFileSystem()->mount();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->context->getFileSystem()->unmount();
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function getPyStringNode($string)
    {
        return new PyStringNode([$string], 0);
    }

    public function testSetFileContentsShouldSetFileContents()
    {
        $contents = 'foo';

        $this->context->setFileContents($this->scheme . '/bar', $this->getPyStringNode($contents));

        assertThat($contents, is(identicalTo(file_get_contents($this->scheme . '/bar'))));
    }

    public function testAssertFileExistsShouldNotThrowIfFileExist()
    {
        file_put_contents($this->scheme . '/foo', 'bar');

        $this->context->assertFileExists($this->scheme . '/foo');

        assertThat('Checking a file exists should not raise an exception.', true, is(true));
    }

    public function testAssertFileExistsShouldThrowIfFileDoesNotExist()
    {
        $this->setExpectedException('Hamcrest\AssertionError');

        $this->context->assertFileExists($this->scheme . '/foo');
    }

    public function testAssertFileContentsShouldNotThrowIfContentsMatchesExpected()
    {
        $contents = 'bar';

        file_put_contents($this->scheme . '/foo', $contents);

        $this->context->assertFileContents($this->scheme . '/foo', $this->getPyStringNode($contents));

        assertThat('Checking the contents of a file that exists should not raise an exception.', true, is(true));
    }

    public function testAssertFileContentsShouldThrowIfContentsDoesNotMatchExpected()
    {
        $this->setExpectedException('Hamcrest\AssertionError');

        file_put_contents($this->scheme . '/foo', 'bar');

        $this->context->assertFileContents($this->scheme . 'foo', $this->getPyStringNode('foo'));
    }

    public function testAssertFileContentsShouldThrowIfFileDoesNotExist()
    {
        $this->setExpectedException('Hamcrest\AssertionError');

        $this->context->assertFileContents($this->scheme . 'foo', $this->getPyStringNode('bar'));
    }

    public function testAssertFileStartWithShouldNotThrowIfContentsStartWithExpected()
    {
        file_put_contents($this->scheme . '/foo', 'foobar');

        $this->context->assertFileStartWith($this->scheme . 'foo', $this->getPyStringNode('foo'));

        assertThat('Checking the beginning contents of a file that exists should not raise an exception.', true, is(true));
    }

    public function testAssertFileStartWithShouldThrowIfContentsDoesNotBeginWithExpected()
    {
        $this->setExpectedException('Hamcrest\AssertionError');

        file_put_contents($this->scheme . '/foo', 'bar');

        $this->context->assertFileStartWith($this->scheme . '/foo', $this->getPyStringNode('foo'));
    }

    public function testAssertFileStartWithShouldThrowIfFileDoesNotExist()
    {
        $this->setExpectedException('Hamcrest\AssertionError');

        $this->context->assertFileStartWith($this->scheme . '/foo', $this->getPyStringNode('bar'));
    }

    public function testAssertFileEndsWithShouldNotThrowIfContentsEndsWithExpected()
    {
        file_put_contents($this->scheme . '/foo', 'foobar');

        $this->context->assertFileEndsWith($this->scheme . '/foo', $this->getPyStringNode('bar'));

        assertThat('Checking the end contents of a file that exists should not raise an exception.', true, is(true));
    }

    public function testAssertFileEndsWithShouldThrowIfContentsDoesNotEndWithExpected()
    {
        $this->setExpectedException('Hamcrest\AssertionError');

        file_put_contents($this->scheme . '/foo', 'foo');

        $this->context->assertFileEndsWith($this->scheme . '/foo', $this->getPyStringNode('bar'));
    }

    public function testAssertFileEndsWithShouldThrowIfFileDoesNotExist()
    {
        $this->setExpectedException('Hamcrest\AssertionError');

        $this->context->assertFileEndsWith($this->scheme . '/foo', $this->getPyStringNode('bar'));
    }

    public function testPrintFileContentsShouldOutputTheFileContents()
    {
        file_put_contents($this->scheme . '/foo', 'bar');

        $this->expectOutputString('bar');

        $this->context->printFileContents($this->scheme . '/foo');
    }

    public function testPrintFileContentsShouldThrowIfFileDoesNotExist()
    {
        $this->setExpectedException('Hamcrest\AssertionError');

        $this->context->printFileContents($this->scheme . '/foo');
    }
}
