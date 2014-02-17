<?php

/**
 * This class is used to deeply clear the cache from the command line.
 */
require_once('BaseTask.php');

class ClearCacheTask extends BaseTask {

	/**
	 * @var string
	 */
	protected $credentials = '';

	/**
	 * @var string
	 */
	protected $directory = '';

	/**
	 * @var string
	 */
	protected $database;

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string
	 */
	protected $host;

	/**
	 * Main entry point.
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();

		$commands = array();
		foreach ($this->getCacheTables() as $cacheTable) {
			$commands[] = sprintf('mysql -u root -p%s -e "TRUNCATE table %s;" %s',
				$this->password,
				$cacheTable,
				$this->database
			);
		}

		// if dryRun is set then, the command line is printed out
		if ($this->isDryRun()) {
			$this->log($commands);
		} else {
			$results = $this->execute($commands);
			if (!empty($results)) {
				$this->log($results);
			}
		}
	}

	/**
	 * Returns the cache tables.
	 *
	 * @return array
	 */
	public function getCacheTables() {

		$cacheTables = array(
			#'be_sessions',
			'cache_imagesizes',
			#'sys_log',
			'sys_lockedrecords',
			#'sys_history',
			#'sys_registry',
			'sys_file_processedfile',
			#'sys_refindex',
			#'tx_extensionmanager_domain_model_extension',
		);

		// Get a list of cache table which should be cleared beforehand and merge them into the $tables variable.
		$tablePrefixes = array(
			'cf_',
			'index_',
			'tx_realurl_',
		);

		foreach ($tablePrefixes as $prefix) {
			$result = array();

			$request = sprintf("SELECT GROUP_CONCAT(DISTINCT table_name) FROM information_schema.tables WHERE table_schema = '%s' AND table_name like '%s%%';",
				$this->database,
				$prefix
			);
			$command = sprintf('mysql -u root -p%s -e "%s"',
				$this->password,
				$request
			);
			// get the result
			exec($command, $result);

			if (!empty($result[1])) {
				$cacheTables = array_merge($cacheTables, explode(',', $result[1]));
			}
		}
		return $cacheTables;
	}

	// -------------------------------
	// Set properties from XML
	// -------------------------------

	/**
	 * Set the database
	 *
	 * @param string $value
	 * @return void
	 */
	public function setDatabase($value) {
		$this->database = $value;
	}

	/**
	 * Set the username
	 *
	 * @param string $value
	 * @return void
	 */
	public function setUsername($value) {
		$this->username = $value;
	}

	/**
	 * Set the password
	 *
	 * @param string $value
	 * @return void
	 */
	public function setPassword($value) {
		$this->password = $value;
	}

	/**
	 * Set the password
	 *
	 * @param string $value
	 * @return void
	 */
	public function setHost($value) {
		$this->host = $value;
	}

}