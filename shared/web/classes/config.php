<?php

class Config {
  public function __construct() {
    $this->filename = "config.json";
    $this->platformBase   = $_SERVER['DOCUMENT_ROOT'];
    $this->moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
    $this->scriptsBase    = $moduleBase . '/scripts' ;
    $this->file           = $moduleBase  . DIRECTORY_SEPARATOR . $this->filename;
    $this->startScript    = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodestart.sh' ;
    $this->stopScript     = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodestop.sh' ;
    $this->updateScript	= $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodeupdate.sh' ;
    $this->checkScript    = $scriptsBase . DIRECTORY_SEPARATOR . 'checkStorj.sh' ;
    $this->storageBinary  = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenode' ;
    $this->yamlPath	= $scriptsBase . DIRECTORY_SEPARATOR . 'docker-compose_base.yml' ;
  }

  public function readConfigFile() {
    try {
      return json_decode(file_get_contents($this->filename));
    } catch (Exception $e) {
      return new StdClass();
    }
  }

  public function writeConfigFile($data) {
    file_put_contents($this->filename, json_encode($data));
    return $this->readConfigFile();
  }
}

