<?php


require_once('LocalCredentialsService.php');
require_once('RemoteCredentialsService.php');

/**
 * Base class
 */
abstract class BaseTask extends Task {

	/**
	 * @var array
	 */
	protected $commands = array();

	/**
	 * @var array
	 */
	protected $configuration;

	/**
	 * @var array
	 */
	protected $properties;

	/**
	 * @var boolean
	 */
	protected $dryRun = FALSE;

	/**
	 * @var boolean
	 */
	protected $verbose = FALSE;

	/**
	 * @var string
	 */
	protected $localMysqlBinaryProperty = 'local.mysql.binary';

	/**
	 * @var string
	 */
	protected $localDirectoryTempProperty = 'local.directory.temp';

	/**
	 * @var string
	 */
	protected $localDatabaseProperty = 'local.database.name';

	/**
	 * @var string
	 */
	protected $localUsernameProperty = 'local.database.username';

	/**
	 * @var string
	 */
	protected $localPasswordProperty = 'local.database.password';

	/**
	 * @var string
	 */
	protected $localHostProperty = 'local.database.host';

	/**
	 * @var string
	 */
	protected $remoteServerCredentialsProperty = 'remote.server.credentials';

	/**
	 * @var string
	 */
	protected $remoteDirectoryHomeProperty = 'remote.directory.home';

	/**
	 * @var string
	 */
	protected $remoteDirectoryTempProperty = 'remote.directory.temp';
	/**
	 * @var string
	 */
	protected $remoteDirectoryCredentialsProperty = 'remote.database.credentials';

	/**
	 * @var string
	 */
	protected $remoteDatabaseProperty = 'remote.database.name';

	/**
	 * @var string
	 */
	protected $remoteUsernameProperty = 'remote.database.username';

	/**
	 * @var string
	 */
	protected $remotePasswordProperty = 'remote.database.password';

	/**
	 * @var string
	 */
	protected $remoteHostProperty = 'remote.database.host';

	/**
	 * Tells whether the dry run flag is found.
	 *
	 * @return bool
	 */
	public function isDryRun() {
		return $this->isPropertySet('dryRun') && $this->getProject()->getProperty('dryRun');
	}

	/**
	 * Analyse the given property and tell whether it has been set.
	 *
	 * @param $propertyName
	 * @return bool
	 */
	public function isPropertySet($propertyName) {
		$propertyValue = $this->getProject()->getProperty($propertyName);
		return substr($propertyValue, 0, 1) !== '$';
	}

	/**
	 * Execute the commands
	 *
	 * @param mixed $commands
	 * @return array
	 */
	public function execute($commands) {

		if (is_string($commands)) {
			$commands = array($commands);
		}

		if ($this->isDryRun()) {
			$this->log($commands);
			return array();
		}

		$result = array();
		foreach ($commands as $command) {
			if ($this->dryRun) {
				console($command);
			} elseif ($this->verbose) {
				system($command, $result);
			} else {
				exec($command, $result);
			}
		}
		return $result;
	}

	/**
	 * This method is used to log.
	 *
	 * @param string $message
	 * @return void
	 */
	public function log($message = '') {
		if (is_array($message)) {
			foreach ($message as $line) {
				$this->project->log('     [' . $this->taskName . '] ' . trim($line));
			}
		} else {
			$this->project->log('     [' . $this->taskName . '] ' . trim($message));
		}
	}

	/**
	 * This method is used to debug.
	 *
	 * @param string $message
	 * @return void
	 */
	public function debug($message = '') {
		$this->project->log('      []');
		if (is_array($message) || is_object($message)) {
			print_r($message);
		} elseif (is_bool($message)) {
			var_dump($message);
		} else {
			print $message . chr(10);
		}
	}

	/**
	 * @param $propertyName
	 * @throws Exception
	 * @return string
	 */
	public function get($propertyName) {
		if ($this->getProject()->getProperty($propertyName) === null) {
			throw new Exception(sprintf('I could not find a value for property "%s"', $propertyName), 1393770269);
		}
		return $this->getProject()->getProperty($propertyName);
	}

	/**
	 * @return string
	 */
	public function getLocalDirectoryTemp() {
		return $this->get($this->localDirectoryTempProperty);
	}

	/**
	 * @return string
	 */
	public function getRemoteServerCredentials() {
		return $this->get($this->remoteServerCredentialsProperty);
	}

	/**
	 * @return string
	 */
	public function getRemoteDirectoryHome() {
		return $this->get($this->remoteDirectoryHomeProperty);
	}

	/**
	 * @return string
	 */
	public function getRemoteDirectoryTemp() {
		return $this->get($this->remoteDirectoryTempProperty);
	}

	/**
	 * @return string
	 */
	public function getRemoteDatabaseCredentials() {
		return $this->get($this->remoteDirectoryCredentialsProperty);
	}

	/**
	 * @return string
	 */
	public function getLocalDatabaseProperty() {
		return $this->localDatabaseProperty;
	}

	/**
	 * @return string
	 */
	public function getLocalHostProperty() {
		return $this->localHostProperty;
	}

	/**
	 * @return string
	 */
	public function getLocalPasswordProperty() {
		return $this->localPasswordProperty;
	}

	/**
	 * @return string
	 */
	public function getLocalUsernameProperty() {
		return $this->localUsernameProperty;
	}

	/**
	 * @return string
	 */
	public function getRemoteUsernameProperty() {
		return $this->remoteUsernameProperty;
	}

	/**
	 * @return string
	 */
	public function getRemoteDatabaseProperty() {
		return $this->remoteDatabaseProperty;
	}

	/**
	 * @return string
	 */
	public function getRemoteHostProperty() {
		return $this->remoteHostProperty;
	}

	/**
	 * @return string
	 */
	public function getRemotePasswordProperty() {
		return $this->remotePasswordProperty;
	}

}