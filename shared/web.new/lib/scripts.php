<?php

require_once(__DIR__ . '/constants.php');
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/util.php');

class Scripts {
  private $configFile;
  private $serverAddress;
  private $checkScript;
  private $startScript;
  private $stopScript;
  private $testScript;
  private $updateScript;

  public function __construct() {
    $config = new Config();
    $this->configFile = $config->configFile;
    $this->serverAddress = $_SERVER['SERVER_ADDR'];
    $this->checkScript = realpath(__DIR__ . '/scripts/checkStorj.sh');
    $this->startScript = realpath(__DIR__ . '/scripts/storagenodestart.sh');
    $this->stopScript = realpath(__DIR__ . '/scripts/storagenodestop.sh');
    $this->testScript = realpath(__DIR__ . '/scripts/test.sh');
    $this->updateScript = realpath(__DIR__ . '/scripts/storagenodeupdate.sh');
    $this->configFileData = $config->readConfigFile();
  }

  public function check() {
    Logger::log("Checking storj server.");
    $containerName = $this->configFileData->containerName ? $this->configFileData->containerName : DEFAULT_CONTAINER_NAME;
    $cmd = "docker ps -a --filter=\"name=^{$containerName}$\" --format \"{{json . }}\"";
    Logger::log("Running command: ($cmd)");
    $output = exec($cmd);
    LOGGER::log("Docker ps output: " . $output);
    $statsHost = 'http://' . $_SERVER['SERVER_NAME'] . ':14002';

    // TODO: Parse and get the version.
    // Release build
    // Version: v1.1.1
    // Build timestamp: 01 Apr 20 15:19 UTC
    // Git commit: 17923e6fd199e2b33a6ef5853a76f9be68322e79
    // $versionCmd = "docker exec `docker ps -l -q -f 'name={$containerName}'` sh -c \"/app/storagenode version\"";
    // $versionOutout = exec($cmd);

    if (!$output) {
      return json_decode(json_encode([
        'containerName' => $containerName,
        'statsHost' => $statsHost
        // 'versionOutout' => $versionOutout
      ]));
    }
    $output->containerName = $containerName;
    $output->statsHost = $statsHost;
    // $output->versionOutout = $versionOutout;
    return (object) json_decode($output);
  }

  public function start() {
    Logger::log("Starting storj server.");
    $output = shell_exec("/bin/bash {$this->startScript} {$this->configFileData->Port} {$this->configFileData->Wallet} {$this->configFileData->Email} {$this->configFileData->Bandwidth} {$this->configFileData->Allocation} {$this->configFileData->Directory} {$this->configFileData->Identity} 2>&1");
    LOGGER::log($output);
    return $output;
  }

  public function stop() {
    Logger::log("Stopping storj server.");
    $output = shell_exec("/bin/bash {$this->stopScript} 2>&1 ");
    LOGGER::log($output);
    return $output;
  }

  public function update() {
    Logger::log("Updating storj server.");
    $output = shell_exec("/bin/bash {$this->updateScript} {$this->configFile} {$this->configFileData->Port} {$this->configFileData->Wallet} {$this->configFileData->Email} {$this->configFileData->Bandwidth} {$this->configFileData->Allocation} {$this->configFileData->Directory} {$this->configFileData->Identity} {$this->serverAddress} 2>&1");
    LOGGER::log($output);
    return $output;
  }
}
