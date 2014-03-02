<?php

/**
 * This class is used to get a diff from the remote.
 */
require_once('BaseTask.php');

class GitDiffRemoteTask extends BaseTask {

	/**
	 * Main entry point.
	 *
	 * @throws Exception
	 * @return void
	 */
    public function main() {

		// commands that will retrieve the status of the remote working copy
	    $command = sprintf("ssh %s 'cd %s; /usr/local/git/bin/git diff'",
		    $this->getRemoteServerCredentials(),
		    $this->getRemoteDirectoryHome()
	    );

	    // default value for $results
		$results = array();

		// if dryRun is set then, the command line is printed out
		if ($this->isDryRun()) {
			$this->log($command);
		}
		else {
			$results = $this->execute($command);
			$this->log($results);
		}

	}
}