<?php

/**
 * This class is used to deeply clear the cache from the command line.
 */
require_once('BaseTask.php');

class ClearDatabaseCacheTask extends BaseTask {

	/**
	 * Main entry point.
	 *
	 * @return void
	 */
	public function main() {

		parent::main();

		$commands = array();
		foreach ($this->getCacheTables() as $cacheTable) {
			$commands[] = sprintf('%s -u %s -p%s -e "TRUNCATE table %s;" %s',
				$this->getMysqlBinary(),
				$this->getUsername(),
				$this->getPassword(),
				$cacheTable,
				$this->getDatabase()
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
				$this->getDatabase(),
				$prefix
			);
			$command = sprintf('%s -u %s -p%s -e "%s"',
				$this->getMysqlBinary(),
				$this->getUsername(),
				$this->getPassword(),
				$request
			);

			// get the result
			exec($command, $result);

			if (!empty($result[1]) && $result[1] != 'NULL') {
				$cacheTables = array_merge($cacheTables, explode(',', $result[1]));
			}

		}
		return $cacheTables;
	}

	/**
	 * @return string
	 */
	protected function getDatabase() {
		return $this->get($this->localDatabaseProperty);
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->get($this->localUsernameProperty);
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->get($this->localPasswordProperty);
	}

	/**
	 * @return string
	 */
	public function getHost() {
		return $this->get($this->localHostProperty);
	}

	/**
	 * @return string
	 */
	public function getMysqlBinary() {
		return $this->get($this->localMysqlBinaryProperty);
	}

}