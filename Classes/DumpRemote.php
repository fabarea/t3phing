<?php

/**
 * This class is used to remotely dump the database.
 */
require_once('BaseTask.php');

class DumpRemote extends BaseTask {

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
	protected $host = '';

	/**
	 * @var array
	 */
	protected $additionalIgnoreTables = array();

	/**
	 * @var string
	 */
	protected $password = '';

	/**
	 * @var string
	 */
	protected $username = '';

	/**
	 * @var string
	 */
	protected $database = '';

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

		// Initialize task
		$this->initialize();

		$tables[] = 'cache_imagesizes';
		$tables[] = 'cache_md5params';
		$tables[] = 'cache_treelist';
		$tables[] = 'cache_typo3temp_log';
		$tables[] = 'sys_log';

		$tables = array_merge($tables, $this->additionalIgnoreTables);
	    $options = '';
		foreach ($tables as $table) {
			$options .= " --ignore-table=" . $this->database . "." . $table;
		}

		// commands that will retrieve the status of the remote working copy
		$command = 'ssh ' . $this->credentials . " '";

	    if (! empty($this->host)) {
	        $this->host = ' -h ' . $this->host;
	    }

		// creates a light dump of the database
		$command .= "mysqldump -u " . $this->username . " -p" . $this->password . $this->host . " -e -Q " . $options . " " . $this->database . " > " . $this->directory .  $this->database . ".sql ; ";

		// creates a dump of cache tables
		$command .= "mysqldump -u " . $this->username . " -p" . $this->password . $this->host . " -e -Q --no-data " . $this->database . ' ' . implode(' ', $tables) . " >> " . $this->directory .  $this->database . ".sql ; ";

		$command .= "cd " . $this->directory . " ; ";
		$command .= "tar -cjf ".  $this->database . ".bz2 " . $this->database . ".sql ; ";
		$command .= "'";

		// if dryRun is set then, the command line is printed out
		if ($this->properties['dryRun'] === 'true' || $this->properties['dryRun'] === TRUE) {
			$this->log($command);
		}
		else {
			$results = $this->execute($command);
			if (!empty($results)) {
				$this->log($results);
			}
		}
	}

	// -------------------------------
    // Set properties from XML
    // -------------------------------

    /**
     * Set the remote path on the server
	 *
     * @param string $value
     * @return void
     */
    public function setDirectory($value){
        $this->directory = $value;
    }

    /**
     * Set the database
	 *
     * @param string $value
     * @return void
     */
    public function setDatabase($value){
        $this->database = $value;
    }

    /**
     * Set the username
	 *
     * @param string $value
     * @return void
     */
    public function setUsername($value){
        $this->username = $value;
    }

    /**
     * Set the password
	 *
     * @param string $value
     * @return void
     */
    public function setPassword($value){
        $this->password = $value;
    }

    /**
     * Set the credentials information
	 *
     * @param string $value
     * @return void
     */
    public function setCredentials($value){
	    $this->credentials = $value;
    }

    /**
     * Set the host
	 *
     * @param string $value
     * @return void
     */
    public function setHost($value){
        $this->host = $value;
    }

    /**
     * Set the credentials information
	 *
     * @param string $value
     * @return void
     */
    public function setAdditionalIgnoreTables($value){
		$tables = explode(',', $value);
		$this->additionalIgnoreTables = array_map ('trim', $tables);
    }

}