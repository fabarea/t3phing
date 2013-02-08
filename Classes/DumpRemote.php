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
 * This class is used to get the status of remote source code
 *
 * @author Fabien Udriot <fabien.udriot@ecodev.ch>
 *
 * $Id: CheckRemote.php 2392 2011-02-15 16:28:16Z fab1en $
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

?>