<?php

/**
 * Local Credentials Service
 */
class LocalCredentialsService
{

    /**
     * @var Task
     */
    protected $task;

    /**
     * @var array
     */
    protected $configuration = array();

    /**
     * @param BaseTask $baseTask
     */
    public function __construct(BaseTask $baseTask)
    {
        $this->task = $baseTask;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->task->getProject();
    }

    /**
     * @throws Exception
     * @return array
     */
    public function getConfiguration()
    {

        if ($this->getProject()->getProperty('local.database.credentials') === null) {
            throw new Exception('I could not find a value for property "local.database.credentials"', 1393766584);
        }
        $credentialsFile = $this->getProject()->getProperty('local.database.credentials');
        if (!file_exists($credentialsFile)) {
            throw new Exception(sprintf('File "%s" does not exist', $credentialsFile), 1393766585);
        }

        // Load local configuration
        $_configuration = require($credentialsFile);

        // In case the config is an array
        if (!empty($_configuration['DB'])) {
            $configuration = array(
                $this->task->getLocalDatabaseProperty() => $_configuration['DB']['database'],
                $this->task->getLocalUsernameProperty() => $_configuration['DB']['username'],
                $this->task->getLocalPasswordProperty() => $_configuration['DB']['password'],
                $this->task->getLocalHostProperty() => empty($_configuration['DB']['host']) ? 'localhost' : $_configuration['DB']['host'],
            );
        } else {
            $configuration = array(
                $this->task->getLocalDatabaseProperty() => $GLOBALS['TYPO3_CONF_VARS']['DB']['database'],
                $this->task->getLocalUsernameProperty() => $GLOBALS['TYPO3_CONF_VARS']['DB']['username'],
                $this->task->getLocalPasswordProperty() => $GLOBALS['TYPO3_CONF_VARS']['DB']['password'],
                $this->task->getLocalHostProperty() => empty($GLOBALS['TYPO3_CONF_VARS']['DB']['host']) ? 'localhost' : $GLOBALS['TYPO3_CONF_VARS']['DB']['host'],
            );
        }

        return $configuration;
    }
}