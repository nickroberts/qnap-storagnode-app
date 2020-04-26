<?php

require_once('./logger.php');

class Config {
  public function __construct() {
    $this->configFile = realpath("config.json");
  }

  public function readConfigFile() {
    try {
      if (is_file($this->configFile)) {
        $content = file_get_contents($this->configFile);
      } else {
        $content = "";
      }
      $response = json_decode($content);
    } catch (\Exception $e) {
      $response = new StdClass();
    }
    return $response;
  }

  public function writeConfigFile($data) {
    $dataObject = (object) $data;
    $configData = $properties = array(
	    'Identity' => $dataObject->identity,
	    'Port' => $dataObject->port,
	    'Wallet' => $dataObject->wallet,
	    'Allocation' => $dataObject->allocation,
	    'Bandwidth' => $dataObject->bandwidth,
	    'Email' => $dataObject->email,
	    'Directory' => $dataObject->directory
    );
    file_put_contents($this->configFile, json_encode($configData));
    return $this->readConfigFile();
  }
}

