Feature: Context

    As a user of sjparkinson/behat-file-system-context
    I want step definitions that interact with a virtual file system
    So that I can test file system operations with behat

    Scenario: Set and checking file contents
        Given "/foo/bar.txt" contains:
        """
        Testing 1...2...3...
        """
        And the file "/bar/foo.txt" contains:
        """
        Testing 1...2...3...
        """
        Then "/foo/bar.txt" should contain:
        """
        Testing 1...2...3...
        """
        And the file "/bar/foo.txt" should contain:
        """
        Testing 1...2...3...
        """

    Scenario: Check a file exists
        Given "/foo/bar.txt" contains:
        """
        Hello World!
        """
        Then the file "/foo/bar.txt" should exist
        And "/foo/bar.txt" should exist

    Scenario: Check file starts with
        Given "/foo/bar.txt" contains:
        """
        Hello World!
        """
        Then the file "/foo/bar.txt" should start with:
        """
        Hello
        """
        And "/foo/bar.txt" should start with:
        """
        Hello
        """

    Scenario: Check file ends with
        Given "/foo/bar.txt" contains:
        """
        Hello World!
        """
        Then the file "/foo/bar.txt" should end with:
        """
        World!
        """
        And "/foo/bar.txt" should end with:
        """
        World!
        """

    Scenario: Print the contents of a file
        Given "/foo/bar.txt" contains:
        """
        Hello World!
        """
        Then print the contents of file "/foo/bar.txt"
