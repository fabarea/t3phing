<?php

/**
 * This class is used to Rsync with the remote.
 */
require_once('BaseTask.php');

class Rsync extends BaseTask {

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
     * @return void
     */
    public function main() {

		// Initialize task
		$this->initialize();

		// Makes sure it is possible to connecto to the server
		if (! file_exists($this->localDirectory) &&
				! ($this->properties['dryRun'] === 'true' || $this->properties['dryRun'] === TRUE)) {
			mkdir($this->localDirectory);
		}

		// commands that will retrieve the status of the remote working copy
		$command = "rsync -a --delete " . $this->credentials . ':' . $this->remoteDirectory . ' ' . $this->localDirectory;

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
     * Set the credentials information
	 *
     * @param string $value
     * @return void
     */
    public function setCredentials($value){
        $this->credentials = $value;
    }

    /**
     * Set the local directory
	 *
     * @param string $value
     * @return void
     */
    public function setRemoteDirectory($value){
        $this->remoteDirectory = $value;
    }

    /**
     * Set the remote path on the server
	 *
     * @param string $value
     * @return void
     */
    public function setLocalDirectory($value){
        $this->localDirectory = $value;
    }

}