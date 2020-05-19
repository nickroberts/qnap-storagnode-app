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
    $this->versionScript = realpath(__DIR__ . "/../scripts/version.sh");
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
    // $cmdOutput = '{
    //   "Command": "\"docker-php-entrypoint apache2-foreground\"",
    //   "CreatedAt": "2020-05-18 23:41:19 -0400 EDT",
    //   "ID": "51a7e3d8d1a1c0a6717b34713b68a89813a4a40de7a4a8511548011c238eb9cd",
    //   "Image": "php:7.3-apache",
    //   "Labels": "com.docker.compose.container-number=1,com.docker.compose.oneoff=False,com.docker.compose.project=qnap-storagnode-app,com.docker.compose.project.config_files=docker-compose.yml,com.docker.compose.project.working_dir=/Volumes/Workspace/Workspace/Utropicmedia/Storj/qnap-storagnode-app,com.docker.compose.service=qnap-storagnode-app.php,com.docker.compose.version=1.25.5,com.docker.compose.config-hash=597b5dd915ff90d76d5b2db2d3c8ebb8e0ede2a68401065bfb0702ce2c29c4ea",
    //   "LocalVolumes": "0",
    //   "Mounts": "/Volumes/Workspace/Workspace/Utropicmedia/Storj/qnap-storagnode-app/volumes/logs,/Volumes/Workspace/Workspace/Utropicmedia/Storj/qnap-storagnode-app/shared/web.new",
    //   "Names": "qnap-storagnode-app.php",
    //   "Networks": "qnap-storagnode-app_qnap-storagnode-app.network",
    //   "Ports": "0.0.0.0:8000-\u003e80/tcp",
    //   "RunningFor": "13 hours ago",
    //   "Size": "0B",
    //   "Status": "Up 13 hours"
    // }';
    Logger::log("Check command output: " . $cmdOutput);

    $dockerInfo = (array) json_decode($cmdOutput);
    if (json_last_error() == JSON_ERROR_NONE) {
      $output['dockerInfo'] = $dockerInfo;
      // Util::dd(strpos($dockerInfo['Status'], 'Exited') === false);
      // Util::dd(strpos($dockerInfo['Status'], 'Up') === true);
      if (strpos($dockerInfo['Status'], 'Up') >= 0 && strpos($dockerInfo['Status'], 'Exited') === false) {
        $output['status'] = 'online';

        // Parse and get the version.
        $versionScript = $this->versionScript;
        $versionCmd = "/bin/bash {$versionScript} {$configFileData->containerName}";
        $versionOutput = trim(shell_exec($versionCmd));
//         $versionOutput = trim("Release build
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
      }
    }

    $output = Util::convertKeysToCamelCase($output);

    return (object) $output;
  }

  public function start() {
    Logger::log("Starting storj server.");
    $output = [];
    $startScript = $this->startScript;
    $configFileData = $this->configFileData;
    $imageName = DEFAULT_IMAGE_NAME;
    $imageTag = DEFAULT_IMAGE_TAG;
    $cmd = "/bin/bash {$startScript} {$configFileData->containerName} {$imageName}:{$imageTag} {$configFileData->hostname} {$configFileData->identityPath} {$configFileData->walletAddress} {$configFileData->storageAllocation} {$configFileData->emailAddress} {$configFileData->storageDirectory}";
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

  public function restart() {
    Logger::log("Restarting storj server.");
    $output = [];
    $stopOutput = $this->stop();
    $output['stopOutput'] = $stopOutput;
    if ($stopOutput) {
      $startOutput = $this->start();
      $output['startOutput'] = $startOutput;
    }
    Logger::log("Restarted storj server.");
    return $output;
  }

  public function update() {
    Logger::log("Updating storj server.");
    $output = [];
    $stopOutput = $this->stop();
    $output['stopOutput'] = $stopOutput;
    if ($stopOutput) {
      $updateScript = $this->updateScript;
      $imageName = DEFAULT_IMAGE_NAME;
      $imageTag = DEFAULT_IMAGE_TAG;
      $updateCmd = "/bin/bash {$updateScript} {$imageName}:{$imageTag}";
      Logger::log("Running command: $updateCmd");
      $updateCmdOutput = shell_exec($updateCmd);
      $output['updateCmdOutput'] = $updateCmdOutput;
      if ($updateCmdOutput) {
        $startOutput = $this->start();
        $output['startOutput'] = $startOutput;
      }
    }
    Logger::log("Updated storj server.");
    return $output;
  }
}
