<?php

/**
 * This class is used to get a diff from the remote.
 */
require_once('BaseTask.php');

class GitDiffRemote extends BaseTask {

	/**
	 * @var string
	 */
	protected $credentials = '';

	/**
	 * @var string
	 */
	protected $directory = '';

	/**
	 * Main entry point.
	 *
	 * @throws Exception
	 * @return void
	 */
    public function main() {

		// Initialize task
		$this->initialize();

		// Makes sure it is possible to connecto to the server
		if ($this->credentials == '') {
			throw new Exception ("Exception thrown #1300533385: credentials is empty can not connect to the server\n", 1300533385);
		}

		// commands that will retrieve the status of the remote working copy
	    $command = sprintf("ssh %s 'cd %s; /usr/local/git/bin/git diff'",
		    $this->credentials,
		    $this->directory
	    );

	    // default value for $results
		$results = array();

		// if dryRun is set then, the command line is printed out
		if ($this->properties['dryRun'] === 'true' || $this->properties['dryRun'] === TRUE) {
			$this->log($command);
		}
		else {
			$results = $this->execute($command);
			$this->log($results);
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
	    $value = str_replace('/htdocs', '', $value);
        $this->directory = $value;
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

}