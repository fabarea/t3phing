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

class SvnCheckoutLocal extends BaseTask {

	/**
	 * @var string
	 */
	protected $directory = '';

	/**
	 * @var string
	 */
	protected $repository = '';

    /**
     * Main entry point.
	 *
     * @return void
     */
    public function main() {

		// Initialize task
		$this->initialize();

		// Makes sure it is possible to connecto to the server
		if (! file_exists($this->directory)) {
			throw new Exception ("Exception thrown #1300533385:\n\n local directory does not exist : \"" . $this->directory . "\"\n\n", 1300533385);
		}

		if ($this->repository == '') {
			throw new Exception ("Exception thrown #1301672969:\n\n no repository is defined : \"" . $this->repository . "\"\n\n", 1301672969);
		}
		
		// commands that will retrieve the status of the remote working copy
		$command = "svn checkout -q " . $this->repository . ' ' . $this->directory;

		// default value for $results
		$results = array();

		// if dryRun is set then, the command line is printed out
		if ($this->properties['dryRun'] === 'true' || $this->properties['dryRun'] === TRUE) {
			$this->log($command);
		}
		else {
			$results = $this->execute($command);
			if (! empty($results)) {
				$this->log($results);
			}
		}
	}

	// -------------------------------
    // Set properties from XML
    // -------------------------------

    /**
     * Set the local directory
	 *
     * @param string $value
     * @return void
     */
    public function setDirectory($value){
        $this->directory = $value;
    }

    /**
     * Set the remote repository
	 *
     * @param string $value
     * @return void
     */
    public function setRepository($value){
        $this->repository = $value;
    }


}

?>