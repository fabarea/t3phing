<?php

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
	 * Initialize project
	 *
	 * @return void
	 */
	public function initialize() {

		// Get project properties
		$this->properties = $this->project->getProperties();
	}

	/**
	 * Tells whether the dry run flag is detected.
	 *
	 * @return bool
	 */
	public function isDryRun() {

		return $this->properties['dryRun'] === 'true' || $this->properties['dryRun'] === TRUE;
	}

	/**
	 * This method is used to generate the commands to be executed in the handler
	 *
	 * @return void
	 */
//	abstract public function perform();

	/**
	 * This method is used to execute the commands
	 *
	 * @param mixed $commands
	 * @return array
	 */
	public function execute($commands) {
		if (is_string($commands)) {
			$commands = array($commands);
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
}