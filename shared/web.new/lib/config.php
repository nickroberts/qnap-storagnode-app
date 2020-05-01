<?php

require_once(__DIR__ . '/constants.php');
require_once(__DIR__ . '/logger.php');

class Config {
  private $defaultConfig = array(
    "authenticationKey" => null,
    "containerName" => DEFAULT_CONTAINER_NAME,
    "identityPath" => null,
    "imageName" => DEFAULT_IMAGE_NAME,
    "imageTag" => DEFAULT_IMAGE_TAG,
		"hostname" => null,
		"walletAddress" => null,
		"storageAllocation" => null,
		"emailAddress" => null,
		"storageDirectory" => null
  );

  public function __construct() {
    $this->configFile = CONFIG_FILE_NAME;
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
    $this->save($dataObject);
    return $this->readConfigFile();
  }

  public function validateConfigFile() {
    $config = (array) $this->readConfigFile();
    $output = ['valid' => true];
    $errors = [];
    $requiredFields = [
      "identityPath",
      "hostname",
      "walletAddress",
      "storageAllocation",
      "emailAddress",
      "storageDirectory"
    ];
    foreach ($requiredFields as $field) {
      if (!$config[$field]) {
        $errors[$field] = "${field} is required";
      }
    }
    if (count($errors) > 0) {
      $output['valid'] = false;
      $output['errors'] = $errors;
    }
    return (object) $output;
  }

  private function save($data) {
    file_put_contents($this->configFile, json_encode($data, JSON_PRETTY_PRINT));
  }
}

