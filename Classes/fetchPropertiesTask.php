<?php

/**
 * This class is used to get the status of remote source code
 */
require_once('BaseTask.php');

class FetchPropertiesTask extends BaseTask {

	/**
	 * Main entry point.
	 *
	 * @throws Exception
	 * @return void
	 */
	public function main() {
		parent::main();

		if (!file_exists($this->getProject()->getProperty('file.properties'))) {

			// TRUE means it is a development instance
			// -> fetch remote configuration
			if (!$this->get('isProduction')) {

				/** @var RemoteCredentialsService $remoteCredentialsService */
				$remoteCredentialsService = new RemoteCredentialsService($this);
				$remoteConfiguration = $remoteCredentialsService->getConfiguration();
				$this->setProperties($remoteConfiguration);
			}


			/** @var LocalCredentialsService $localCredentialsService */
			$localCredentialsService = new LocalCredentialsService($this);
			$localConfiguration = $localCredentialsService->getConfiguration();
			$this->setProperties($localConfiguration);

			$configuration = array_merge($remoteConfiguration, $localConfiguration);
			$this->writeFileProperties($configuration);
		}
	}

	/**
	 * @param array $configuration
	 * @return void
	 */
	protected function setProperties($configuration = array()) {
		foreach ($configuration as $propertyName => $value) {
			$this->getProject()->setProperty($propertyName, $value);
		}
	}

	/**
	 * @param array $configuration
	 * @return void
	 */
	protected function writeFileProperties($configuration = array()) {

		$content = array();
		foreach ($configuration as $propertyName => $value) {
			$content[] = $propertyName . ' = ' . $value;
		}

		// write file
		file_put_contents($this->getProject()->getProperty('file.properties'), implode("\n", $content));
	}
}