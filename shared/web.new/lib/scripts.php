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
    $this->checkScript = realpath(__DIR__ . "/../scripts/check.sh");
    $this->startScript = realpath(__DIR__ . "/../scripts/start.sh");
    $this->stopScript = realpath(__DIR__ . "/../scripts/stop.sh");
    $this->testScript = realpath(__DIR__ . "/../scripts/test.sh");
    $this->updateScript = realpath(__DIR__ . "/../scripts/update.sh");
    $this->configFileData = $config->readConfigFile();
  }

  public function check() {
    Logger::log("Checking storj server.");
    $containerName = $this->configFileData->containerName ? $this->configFileData->containerName : DEFAULT_CONTAINER_NAME;
    $statsHost = 'http://' . $_SERVER['SERVER_NAME'] . ':14002';

    $output = [
      'containerName' => $containerName,
      'statsHost' => $statsHost,
      'status' => 'offline'
    ];

    $checkScript = $this->checkScript;
    $configFileData = $this->configFileData;
    $cmd = "/bin/bash {$checkScript} {$configFileData->containerName}";
    Logger::log("Running command: $cmd");
    $cmdOutput = shell_exec($cmd);
    Logger::log("Check command output: " . $cmdOutput);

    $dockerInfo = (array) json_decode($cmdOutput);
    if (json_last_error() == JSON_ERROR_NONE) {
      $output['dockerInfo'] = $dockerInfo;
      // Util::dd(strpos($dockerInfo['Status'], 'Exited') === false);
      // Util::dd(strpos($dockerInfo['Status'], 'Up') === true);
      if (strpos($dockerInfo['Status'], 'Up') >= 0 && strpos($dockerInfo['Status'], 'Exited') === false) {
        $output['status'] = 'online';
      }
    }

    // TODO: Parse and get the version.
    $versionCmd = "docker exec `docker ps -l -q -f 'name={$containerName}'` sh -c \"/app/storagenode version\"";
    $versionOutput = trim(exec($cmd));
//     $versionOutput = trim("Release build
// Version: v1.1.1
// Build timestamp: 01 Apr 20 15:19 UTC
// Git commit: 17923e6fd199e2b33a6ef5853a76f9be68322e79");

  if ($versionOutput) {
    $versionInfo = [];
      $lines = explode("\n", $versionOutput);
      foreach ($lines as $key => $value) {
        $split = explode(":", $value, 2);
        if (count($split) > 1) {
          $versionInfo[$split[0]] = trim($split[1]);
        }
      }
      $output['versionInfo'] = $versionInfo;
    }

    $output = Util::convertKeysToCamelCase($output);

    return (object) $output;
  }

  public function start() {
    Logger::log("Starting storj server.");
    $output = [];
    $startScript = $this->startScript;
    $configFileData = $this->configFileData;
    $cmd = "/bin/bash {$startScript} {$configFileData->containerName} {$configFileData->hostname} {$configFileData->identityPath} {$configFileData->walletAddress} {$configFileData->storageAllocation} {$configFileData->emailAddress} {$configFileData->storageDirectory}";
    Logger::log("Running command: $cmd");
    $cmdOutput = shell_exec($cmd);
    Logger::log("Start command output: " . $cmdOutput);
    $output['output'] = $cmdOutput;
    return $output;
  }

  public function stop() {
    Logger::log("Stopping storj server.");
    $output = [];
    $stopScript = $this->stopScript;
    $configFileData = $this->configFileData;
    $cmd = "/bin/bash {$stopScript} {$configFileData->containerName}";
    Logger::log("Running command: $cmd");
    $cmdOutput = shell_exec($cmd);
    Logger::log("Stop command output: " . $cmdOutput);
    $output['output'] = $cmdOutput;
    return $output;
  }

  public function update() {
    Logger::log("Updating storj server.");
    // $output = shell_exec("/bin/bash {$this->updateScript} {$this->configFile} {$this->configFileData->Port} {$this->configFileData->Wallet} {$this->configFileData->Email} {$this->configFileData->Bandwidth} {$this->configFileData->Allocation} {$this->configFileData->Directory} {$this->configFileData->Identity} {$this->serverAddress} 2>&1");
    Logger::log("Update command output: " . $output);
    return $output;
  }
}
