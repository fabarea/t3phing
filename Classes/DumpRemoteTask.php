<?php

/**
 * This class is used to remotely dump the database.
 */
require_once('BaseTask.php');

class DumpRemoteTask extends BaseTask {

	/**
	 * @var array
	 */
	protected $additionalIgnoreTables = array();

	/**
	 * @var array
	 */
	protected $properties = array();

	/**
     * Main entry point.
	 *
     * @return void
     */
    public function main() {

	    // This will not work for TYPO3 < 6.0
	    $cacheTables = array(
		    'be_sessions',
		    'sys_log',
		    'sys_history',
		    'sys_registry',
		    'sys_file_processedfile',
		    'sys_lockedrecords',
		    'tx_extensionmanager_domain_model_extension',
		    'cf_cache_hash',
		    'cf_cache_hash_tags',
		    'cf_cache_pages',
		    'cf_cache_pages_tags',
		    'cf_cache_pagesection',
		    'cf_cache_pagesection_tags',
		    'cf_cache_rootline',
		    'cf_cache_rootline_tags',
		    'cf_extbase_object',
		    'cf_extbase_object_tags',
		    'cf_extbase_reflection',
		    'cf_extbase_reflection_tags',
		    'cf_extbase_typo3dbbackend_tablecolumns',
		    'cf_extbase_typo3dbbackend_tablecolumns_tags',
		    'tx_realurl_chashcache',
		    'tx_realurl_errorlog',
		    'tx_realurl_pathcache',
		    'tx_realurl_redirects',
		    'tx_realurl_uniqalias',
		    'tx_realurl_urldecodecache',
		    'tx_realurl_urlencodecache',
	    );

		$cacheTables = array_merge($cacheTables, $this->additionalIgnoreTables);
	    $options = '';
		foreach ($cacheTables as $table) {
			$options .= sprintf(" --ignore-table=%s.%s",
				$this->getDatabase(),
				$table
			);
		}

		// commands that will retrieve the status of the remote working copy
		$command = 'ssh ' . $this->getRemoteServerCredentials() . " '";

		// creates a light dump of the database
		$command .= sprintf("mysqldump -u %s -p\"%s\" -h %s -e -Q %s %s > %s/%s.sql; ",
			$this->getUsername(),
			$this->getPassword(),
			$this->getHost(),
			$options,
			$this->getDatabase(),
			$this->getRemoteDirectoryTemp(),
			$this->getDatabase(),
			$this->getDatabase()

		);

		// creates a dump of cache tables
		$command .= sprintf("mysqldump -u %s -p\"%s\" -h %s -e -Q --no-data %s %s >> %s/%s.sql ; ",
			$this->getUsername(),
			$this->getPassword(),
			$this->getHost(),
			$this->getDatabase(),
			implode(' ', $cacheTables),
			$this->getRemoteDirectoryTemp(),
			$this->getDatabase()
		);

		$command .= sprintf("cd %s ; ", $this->getRemoteDirectoryTemp());
		$command .= sprintf("tar -cjf %s.bz2 %s.sql ; ",
			$this->getDatabase(),
			$this->getDatabase()
		);

		$command .= "'";

		// if dryRun is set then, the command line is printed out
		if ($this->isDryRun()) {
			$this->log($command);
		}
		else {
			$results = $this->execute($command);
			if (!empty($results)) {
				$this->log($results);
			}
		}
	}

    /**
     * @param string $value
     * @return void
     */
    public function setAdditionalIgnoreTables($value){
		$tables = explode(',', $value);
		$this->additionalIgnoreTables = array_map ('trim', $tables);
    }

	/**
	 * @return string
	 */
	protected function getDatabase() {
		return $this->get($this->remoteDatabaseProperty);
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->get($this->remoteUsernameProperty);
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->get($this->remotePasswordProperty);
	}

	/**
	 * @return string
	 */
	public function getHost() {
		return $this->get($this->remoteHostProperty);
	}

	/**
	 * @return string
	 */
	public function getMysqlBinary() {
		return $this->get($this->remoteMysqlBinaryProperty);
	}

}
