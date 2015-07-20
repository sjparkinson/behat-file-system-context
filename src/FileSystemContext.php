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

namespace SamParkinson\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Vfs\FileSystem;
use Vfs\FileSystemBuilder;
use Vfs\Node\Directory;
use Vfs\Node\File;

/**
 * Defines filesystem steps.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class FileSystemContext implements Context
{
    /**
     * @var \Vfs\FileSystem
     */
    protected $fileSystem;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * Creates a new instance of FileSystemContext.
     *
     * @param string $scheme
     */
    public function __construct($scheme = 'vfs://')
    {
        $this->scheme = $scheme;
        $this->fileSystem = (new FileSystemBuilder(rtrim($this->scheme, ':/\\')))->build();
    }

    /**
     * @return \Vfs\FileSystem
     */
    public function getFileSystem()
    {
        return $this->fileSystem;
    }

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        $this->fileSystem->mount();
    }

    /**
     * @AfterScenario
     *
     * @param AfterScenarioScope $scope
     */
    public function afterScenario(AfterScenarioScope $scope)
    {
        $this->fileSystem->unmount();
    }

    /**
     * Transform standard filenames into a virtual file system path.
     *
     * @Transform :filename
     *
     * @param string $filename
     *
     * @return string
     */
    public function transformFilenames($filename)
    {
        return $this->scheme . $filename;
    }

    /**
     * @Given :filename contains:
     * @Given the file :filename contains:
     *
     * @param string       $filename
     * @param PyStringNode $contents
     */
    public function setFileContents($filename, PyStringNode $contents)
    {
        $path = dirname(substr($filename, strlen($this->scheme)));

        if (! is_dir($this->scheme . $path)) {
            mkdir($this->scheme . $path, 0777, true);
        }

        file_put_contents($filename, (string) $contents);
    }

    /**
     * @Then :filename should exist
     * @Then the file :filename should exist
     *
     * @param string $filename
     */
    public function assertFileExists($filename)
    {
        assertThat(sprintf('The file %s does not exist.', $filename), file_exists($filename));
    }

    /**
     * @Then :filename should contain:
     * @Then the file :filename should contain:
     *
     * @param string       $filename
     * @param PyStringNode $contents
     */
    public function assertFileContents($filename, PyStringNode $contents)
    {
        $this->assertFileExists($filename);

        assertThat(
            sprintf('The contents of %s is not what was expected.', $filename),
            file_get_contents($filename) === (string) $contents
        );
    }

    /**
     * @Then :filename should start with:
     * @Then the file :filename should start with:
     *
     * @param string       $filename
     * @param PyStringNode $contents
     */
    public function assertFileStartWith($filename, PyStringNode $contents)
    {
        $this->assertFileExists($filename);

        assertThat(
            sprintf('The contents of %s does not begin with what was expected.', $filename),
            file_get_contents($filename),
            startsWith((string) $contents)
        );
    }

    /**
     * @Then :filename should end with:
     * @Then the file :filename should end with:
     *
     * @param string       $filename
     * @param PyStringNode $contents
     */
    public function assertFileEndsWith($filename, PyStringNode $contents)
    {
        $this->assertFileExists($filename);

        assertThat(
            sprintf('The contents of %s does not end with what was expected.', $filename),
            file_get_contents($filename),
            endsWith((string) $contents)
        );
    }

    /**
     * @Then print the contents of :filename
     * @Then print the contents of file :filename
     *
     * @param string $filename
     */
    public function printFileContents($filename)
    {
        $this->assertFileExists($filename);

        echo(file_get_contents($filename));
    }
}
