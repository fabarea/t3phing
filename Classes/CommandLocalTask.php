<?php

/**
 * This class is used to run local command.
 */
require_once('BaseTask.php');

class CommandLocalTask extends BaseTask
{

    /**
     * @var string
     */
    protected $command = '';

    /**
     * Main entry point.
     *
     * @return void
     */
    public function main()
    {

        // commands that will retrieve the status of the remote working copy
        $command = $this->command;

        // if dryRun is set then, the command line is printed out
        if ($this->isDryRun()) {
            $this->log($command);
        } else {
            $results = $this->execute($command);
            if (!empty($results)) {
                $this->log($results);
            }
        }
    }

    /**
     * Set the remote path on the server
     *
     * @param string $value
     * @return void
     */
    public function setCommand($value)
    {
        $this->command = $value;
    }

}