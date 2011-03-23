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

require_once('Classes/BaseTask.php');

class CheckLocal extends BaseTask {

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
		if (! file_exists($this->directory)) {
			throw new Exception ("Exception thrown #1300533385:\n\n local directory does not exist : \"" . $this->directory . "\"\n\n", 1300533385);
		}
		
		// commands that will retrieve the status of the remote working copy
		$command = "svn status " . $this->directory;

		$results = array();
		
		// if dryRun is set then, the command line is printed out
		if ($this->properties['dryRun']) {
			$this->log($command);
		}
		else {
			$results = $this->execute($command);
		}

		// analyse the result
		$exceptions = array();
		foreach ($results as $result) {
			if (preg_match('/^M /i', $result)) {
				$exceptions[] = $result;
			}
		}

		if (! empty($exceptions)) {
			throw new Exception ("Exception thrown #1300377961: files are not yet committed on the local working copy\n\n" . implode("\n",  $exceptions) . '\n\n', 1300377961);
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


}

?>