<?php

namespace alcamo\cli;

use GetOpt\ArgumentException;

abstract class AbstractCli extends GetOpt
{
    public function getVerbosity(): int
    {
        return $this->getOption('verbose') - $this->getOption('quiet');
    }

    public function process($arguments = null)
    {
        try {
            parent::process($arguments);
        } catch (ArgumentException $e) {
            $this->showHelp();
            exit;
        }

        if ($this->getOption('help')) {
            $this->showHelp();
            exit;
        }
    }

    public function showHelp()
    {
        echo $this->getHelpText();
    }
}
