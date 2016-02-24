<?php

/**
 * This class try to fix the environment.
 */
require_once('BaseTask.php');

class FixSettingsTask extends BaseTask
{

    /**
     * @var string
     */
    protected $configurationDirectory = 'configuration';

    /**
     * Main entry point.
     *
     * @throws Exception
     * @return void
     */
    public function main()
    {

        if (!file_exists($this->getSampleFile())) {
            throw new Exception(sprintf('I could not find file "%s"', $this->getSampleFile()));
        }

        $content = file_get_contents($this->getSampleFile());

        $properties = array(
            'local.domain' => '',
            'has.server.deployment' => 'true',
            'local.database.credentials' => 'configuration/Settings.php',
            'remote.server.credentials' => '',
            'remote.domain' => '',
            'remote.directory.home' => '',
            'remote.database.credentials' => 'configuration/Settings.php',
        );
        foreach ($properties as $propertyName => $defaultValue) {
            $value = trim($this->get($propertyName)) ? trim($this->get($propertyName)) : $defaultValue;
            $content = str_replace('__' . $propertyName, $value, $content);
        }

        if (!is_dir($this->getTargetDirectory())) {
            mkdir($this->getTargetDirectory());
        }

        file_put_contents($this->getTargetFile(), $content);
    }

    /**
     * @return string
     */
    public function getTargetDirectory()
    {
        return sprintf('%s/%s',
            $this->getProject()->getBasedir(),
            $this->configurationDirectory
        );
    }

    /**
     * @return string
     */
    public function getTargetFile()
    {
        return sprintf('%s/%s/PhingSettings.xml',
            $this->getProject()->getBasedir(),
            $this->configurationDirectory
        );
    }

    /**
     * @return string
     */
    public function getSampleFile()
    {
        return sprintf('%s/%s/Sample/PhingSettings.xml',
            $this->getProject()->getBasedir(),
            $this->get('build.home')
        );
    }
}