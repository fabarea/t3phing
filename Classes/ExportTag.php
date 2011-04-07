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

class ExportTag extends BaseTask {
	
	/**
	 * @var string
	 */
	protected $master = '';

	/**
	 * @var string
	 */
	protected $version = '';

	/**
	 * @var string
	 */
	protected $coreHome = '';

    /**
     * Main entry point.
	 *
     * @return void
     */
    public function main() {

		// Initialize task
		$this->initialize();

		// Makes sure the master exists
		if (! file_exists($this->master)) {
			throw new Exception ("Exception thrown #1301712636:\n\n master does not exist : \"" . $this->master . "\"\n\n", 1301712636);
		}


		// Makes sure the version is defined
		if ($this->version == '') {
			throw new Exception ("Exception thrown #1301712863:\n\n version number is empty : \"" . $this->version . "\"\n\n", 1301712863);
		}

		$tagName = 'TYPO3_' . str_replace('.', '-', $this->version);
		$tagPath = '/t3core/typo3_src-' . $this->version . '.tag';

		// Makes sure the master exists
		if (file_exists($tagPath)) {
			throw new Exception ("Exception thrown #1301714684:\n\n tag directory already exists : \"" . $tagPath . "\"\n\n", 1301714684);
		}

		// commands to be run
		$command = 'mkdir ' . $tagPath . ' ; ';
		$command .= 'cd ' . $this->master . ' ; ';
		$command .= 'git archive ' . $tagName . ' | tar -x -C ' . $tagPath;


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
     * Set the master path
	 *
     * @param string $value
     * @return void
     */
    public function setMaster($value){
        $this->master = $value;
    }

    /**
     * Set the version number
	 *
     * @param string $value
     * @return void
     */
    public function setVersion($value){
        $this->version = $value;
    }
    /**
     * Set the core home path
	 *
     * @param string $value
     * @return void
     */
    public function setCoreHome($value){
        $this->coreHome = $value;
    }

}

?>