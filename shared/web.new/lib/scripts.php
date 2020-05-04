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
    $this->startScript = '../scripts/storagenodestart.sh';
    $this->stopScript = '../scripts/storagenodestop.sh';
    $this->testScript = '../scripts/test.sh';
    $this->updateScript = '../scripts/storagenodeupdate.sh';
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
    $ip = exec("ip -4 -o addr show eth0 | awk '{print $4}' | cut -d "/" -f 1");
    $containerName = $this->configFileData->containerName ? $this->configFileData->containerName : DEFAULT_CONTAINER_NAME;
    $port = $hostnameParts[2];
    $cmd = "docker run -d --restart no -p {$port}:28967 -p 14002:14002 -e WALLET=\"{$this->configFileData->walletAddress}\" -e EMAIL=\"{$this->configFileData->hostname}\" -e ADDRESS=\"{$ip}:{$port}\" -e STORAGE=\"{$this->configFileData->storageAllocation}\" -v {$this->configFileData->identityPath}:/app/identity -v {$this->configFileData->storageDirectory}:/app/config --name {$containerName} {$DEFAULT_IMAGE_NAME}:{$DEFAULT_IMAGE_TAG}";
    Logger::log("Running command: " . $cmd);
    $output = exec($cmd);
    Logger::log("Start command output: " . $output);
    return $output;
  }

  public function stop() {
    Logger::log("Stopping storj server.");
    $containerName = $this->configFileData->containerName ? $this->configFileData->containerName : DEFAULT_CONTAINER_NAME;
    $cmd = "docker stop {$containerName}";
    Logger::log("Running command: " . $cmd);
    $output = exec($cmd);
    Logger::log("Stop command output: " . $output);
    $cmd = "docker rm -f {$containerName}";
    Logger::log("Running command: " . $cmd);
    $output = exec($cmd);
    Logger::log("Remove command output: " . $output);
    return $output;
  }

  public function update() {
    Logger::log("Updating storj server.");
    // $output = shell_exec("/bin/bash {$this->updateScript} {$this->configFile} {$this->configFileData->Port} {$this->configFileData->Wallet} {$this->configFileData->Email} {$this->configFileData->Bandwidth} {$this->configFileData->Allocation} {$this->configFileData->Directory} {$this->configFileData->Identity} {$this->serverAddress} 2>&1");
    Logger::log("Update command output: " . $output);
    return $output;
  }
}
