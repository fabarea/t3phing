<?php

/**
 * Remote Credentials Service
 */
class RemoteCredentialsService
{

    /**
     * @var BaseTask
     */
    protected $task;

    /**
     * @param BaseTask $baseTask
     */
    public function __construct(BaseTask $baseTask)
    {
        $this->task = $baseTask;
    }

    /**
     * Main entry point.
     *
     * @throws Exception
     * @return array
     */
    public function getConfiguration()
    {
        $configuration = array();

        $command = sprintf('scp %s:%s %s',
            $this->task->getRemoteServerCredentials(),
            $this->task->getRemoteDatabaseCredentials(),
            $this->getTempDatabaseCredentials()
        );
        exec($command);


        if (!file_exists($this->getTempDatabaseCredentials())) {
            throw new Exception('I could not retrieve "remote.database.credentials" properly', 1393770482);
        }

        $_configuration = require($this->getTempDatabaseCredentials());

        // In case the config is an array
        if (!empty($_configuration['DB'])) {
            $configuration = array(
                $this->task->getRemoteDatabaseProperty() => $_configuration['DB']['database'],
                $this->task->getRemoteUsernameProperty() => $_configuration['DB']['username'],
                $this->task->getRemotePasswordProperty() => $_configuration['DB']['password'],
                $this->task->getRemoteHostProperty() => empty($_configuration['DB']['host']) ? 'localhost' : $_configuration['DB']['host'],
            );
        } else {
            $configuration = array(
                $this->task->getRemoteDatabaseProperty() => $GLOBALS['TYPO3_CONF_VARS']['DB']['database'],
                $this->task->getRemoteUsernameProperty() => $GLOBALS['TYPO3_CONF_VARS']['DB']['username'],
                $this->task->getRemotePasswordProperty() => $GLOBALS['TYPO3_CONF_VARS']['DB']['password'],
                $this->task->getRemoteHostProperty() => empty($GLOBALS['TYPO3_CONF_VARS']['DB']['host']) ? 'localhost' : $GLOBALS['TYPO3_CONF_VARS']['DB']['host'],
            );
        }

        // Leave behind clean...
        $this->cleanUp();
        return $configuration;
    }

    /**
     * @return array
     */
    protected function cleanUp()
    {

        // clean up environment.
        if (file_exists($this->getTempDatabaseCredentials())) {
            unlink($this->getTempDatabaseCredentials());
        }
    }

    /**
     * @return array
     */
    protected function getTempDatabaseCredentials()
    {
        return rtrim($this->task->getLocalDirectoryTemp(), '/') . '/' . basename($this->task->getRemoteDatabaseCredentials());
    }

}