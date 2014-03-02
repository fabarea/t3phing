<?php

/**
 * This class try to fix the environment.
 */
require_once('BaseTask.php');

class FixSettingsTask extends BaseTask {

	/**
	 * Main entry point.
	 *
	 * @throws Exception
	 * @return void
	 */
	public function main() {

		if (!file_exists($this->getSampleFile())) {
			throw new Exception(sprintf('I could not find file "%s"', $this->getSampleFile()));
		}
		$content = file_get_contents($this->getSampleFile());
		$localDomainGuessed = basename($this->getProject()->getBasedir());
		$content = str_replace('CHANGE_ME_DOMAIN', 'CHANGE_ME_' . $localDomainGuessed ,$content);

		file_put_contents($this->getTargetFile(), $content);
	}

	/**
	 * @return string
	 */
	public function getTargetFile() {
		return sprintf('%s/configuration/PhingSettings.xml',
			$this->getProject()->getBasedir()
		);
	}

	/**
	 * @return string
	 */
	public function getSampleFile(){
		return sprintf('%s/%s/Sample/PhingSettings.xml',
			$this->getProject()->getBasedir(),
			$this->get('build.home')
		);
	}
}