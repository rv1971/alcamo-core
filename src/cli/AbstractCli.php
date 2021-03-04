<?php

namespace alcamo\cli;

abstract class AbstractCli extends GetOpt
{
    public function getVerbosity(): int
    {
        return $this->getOption('verbose') - $this->getOption('quiet');
    }

    public function process($arguments = null)
    {
        parent::process($arguments);

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
