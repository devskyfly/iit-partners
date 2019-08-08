<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    // define public methods as commands
    public function build()
    {
        $this->makeDoc();
    }

    public function makeDoc()
    {
        $this->_cleanDir("docs/api");
        $this->_exec("./vendor/bin/phpdoc -d components -d controllers/rest -d models -d widgets -t docs/api --template=\"responsive-twig\"");
    }
}