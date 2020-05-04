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
    $statsHost = 'http://' . $_SERVER['SERVER_NAME'] . ':14002';

    $output = [
      'containerName' => $containerName,
      'statsHost' => $statsHost,
      'status' => 'offline'
    ];

    $cmd = "docker ps -a --filter=\"name=^{$containerName}$\" --no-trunc --format \"{{json . }}\"";
    Logger::log("Running command: ($cmd)");
    $cmdOutput = trim(exec($cmd));
    // $cmdOutput = trim('{"Command":"\"docker-php-entrypoint apache2-foreground\"","CreatedAt":"2020-04-30 21:35:53 -0400 EDT","ID":"b7b0c14d76af396201c41ed3c3e5ebae34f15767083a13d6a0ef9a398e75a8f5","Image":"php:7.3-apache","Labels":"com.docker.compose.config-hash=597b5dd915ff90d76d5b2db2d3c8ebb8e0ede2a68401065bfb0702ce2c29c4ea,com.docker.compose.container-number=1,com.docker.compose.oneoff=False,com.docker.compose.project=qnap-storagnode-app,com.docker.compose.project.config_files=docker-compose.yml,com.docker.compose.project.working_dir=/Volumes/Workspace/Workspace/Utropicmedia/Storj/qnap-storagnode-app,com.docker.compose.service=qnap-storagnode-app.php,com.docker.compose.version=1.25.5","LocalVolumes":"0","Mounts":"/Volumes/Workspace/Workspace/Utropicmedia/Storj/qnap-storagnode-app/volumes/logs,/Volumes/Workspace/Workspace/Utropicmedia/Storj/qnap-storagnode-app/shared/web.new","Names":"qnap-storagnode-app.php","Networks":"qnap-storagnode-app_qnap-storagnode-app.network","Ports":"0.0.0.0:8000-\u003e80/tcp","RunningFor":"33 minutes ago","Size":"0B","Status":"Up 33 minutes"}');
    // $cmdOutput = trim('{"Command":"\"docker-php-entrypoint apache2-foreground\"","CreatedAt":"2020-04-30 21:35:53 -0400 EDT","ID":"b7b0c14d76af396201c41ed3c3e5ebae34f15767083a13d6a0ef9a398e75a8f5","Image":"php:7.3-apache","Labels":"com.docker.compose.config-hash=597b5dd915ff90d76d5b2db2d3c8ebb8e0ede2a68401065bfb0702ce2c29c4ea,com.docker.compose.container-number=1,com.docker.compose.oneoff=False,com.docker.compose.project=qnap-storagnode-app,com.docker.compose.project.config_files=docker-compose.yml,com.docker.compose.project.working_dir=/Volumes/Workspace/Workspace/Utropicmedia/Storj/qnap-storagnode-app,com.docker.compose.service=qnap-storagnode-app.php,com.docker.compose.version=1.25.5","LocalVolumes":"0","Mounts":"/Volumes/Workspace/Workspace/Utropicmedia/Storj/qnap-storagnode-app/volumes/logs,/Volumes/Workspace/Workspace/Utropicmedia/Storj/qnap-storagnode-app/shared/web.new","Names":"qnap-storagnode-app.php","Networks":"qnap-storagnode-app_qnap-storagnode-app.network","Ports":"0.0.0.0:8000-\u003e80/tcp","RunningFor":"33 minutes ago","Size":"0B","Status":"Exited (0) 33 minutes ago"}');
    LOGGER::log("Docker ps output: " . $cmdOutput);

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
