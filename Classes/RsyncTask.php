<?php

/**
 * This class is used to Rsync with the remote.
 */
require_once('BaseTask.php');

class RsyncTask extends BaseTask {

	/**
	 * @var string
	 */
	protected $remoteDirectory;

	/**
	 * @var string
	 */
	protected $localDirectory;

	/**
     * Main entry point.
	 *
     * @return void
     */
    public function main() {

		// Makes sure it is possible to connecto to the server
		if (! file_exists($this->localDirectory) && ! $this->isDryRun()) {
			mkdir($this->localDirectory);
		}

		// commands that will retrieve the status of the remote working copy
		$command = "rsync -a --delete " . $this->getRemoteServerCredentials() . ':' . $this->remoteDirectory . ' ' . $this->localDirectory;

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