<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Fabien Udriot <fabien.udriot@ecodev.ch>
*  All rights reserved
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
* A copy is found in the textfile GPL.txt and important notices to the license
* from the author is found in LICENSE.txt distributed with these scripts.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * This class is used to download sources
 * 
 * @author Fabien Udriot <fabien.udriot@ecodev.ch>
 *
 * $Id: AbstractHandler.php 2355 2011-01-06 22:23:08Z fab1en $
 */
 
 abstract class BaseTask extends Task{

	/**
	 * @var array
	 */
	protected $commands = array();
	
	/**
	 * @var Configuration
	 */
	protected $configuration;
	
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
			if($this->dryRun) {
				console($command);
			}
			elseif ($this->verbose) {
				system($command, $result);
			}
			else {
				exec($command, $result);
			}
		}
		return $result;
	}

	/**
	 * This method is used to log.
	 *
	 * @return void
	 */
	public function log($message = '') {
		if (is_array($message)) {
			foreach ($message as $line) {
				$this->project->log('     [' . $this->taskName . '] ' . trim($line));
			}
		}
		else {
			$this->project->log('     [' . $this->taskName . '] ' . trim($message));
		}
	}

	/**
	 * This method is used to debug.
	 *
	 * @return void
	 */
	public function debug($message = '') {
		$this->project->log('      []');
		if(is_array($message) || is_object($message)) {
			print_r($message);
		}
		elseif (is_bool($message)) {
			var_dump($message);
		}
		else {
			print $message . chr (10);
		}
	}
}

?>