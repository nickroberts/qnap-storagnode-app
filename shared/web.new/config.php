<?php

require_once('./logger.php');

class Config {
  private $defaultConfig = array(
    'AuthKey' => null,
    'Identity' => null,
    'Port' => 28967,
    'Wallet' => null,
    'Allocation' => null,
    'Bandwidth' => null,
    'Email' => null,
    'Directory' => null,
  );

  public function __construct() {
    $this->configFile = "config.json";
  }

  public function readConfigFile() {
    try {
      if (!is_file($this->configFile)) {
        $this->save($this->defaultConfig);
      }
      $content = file_get_contents($this->configFile);
      $response = json_decode($content);
    } catch (\Exception $e) {
      $response = new StdClass();
    }
    return $response;
  }

  public function writeConfigFile($data) {
    $dataObject = (object) $data;
    $configData = $properties = array(
	    'AuthKey' => $dataObject->authKey,
	    'Identity' => $dataObject->identity,
	    'Port' => $dataObject->port,
	    'Wallet' => $dataObject->wallet,
	    'Allocation' => $dataObject->allocation,
	    'Bandwidth' => $dataObject->bandwidth,
	    'Email' => $dataObject->email,
	    'Directory' => $dataObject->directory
    );
    $this->save($configData);
    return $this->readConfigFile();
  }

  private function save($data) {
    file_put_contents($this->configFile, json_encode($data, JSON_PRETTY_PRINT));
  }
}

