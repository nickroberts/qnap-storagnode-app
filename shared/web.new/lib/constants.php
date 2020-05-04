<?php

define("DEFAULT_IMAGE_NAME", "storjlabs/storagenode");
define("DEFAULT_IMAGE_TAG", "beta");
define("DEFAULT_CONTAINER_NAME", "storjlabs-storagenode");

define("CONFIG_FILE_NAME", "config.json");

define("STORJ_LOG_DIRECTORY", "/var/log/storj/");
define("STORJ_LOG_FILE", STORJ_LOG_DIRECTORY . "storj.log");

define("IDENTITY_DIRECTORY", "/.root/some/directory");
define("IDENTITY_LOG_FILE", STORJ_LOG_DIRECTORY . "identity.log");

if (!is_dir(STORJ_LOG_DIRECTORY)) {
  mkdir(STORJ_LOG_DIRECTORY, 0777, true);
}

define("CONFIG_CONTAINER_NAME", "containerName");
define("CONFIG_IDENTITY_PATH", "identityPath");
define("CONFIG_HOSTNAME", "hostname");
define("CONFIG_WALLET_ADDRESS", "walletAddress");
define("CONFIG_STORAGE_ALLOCATION", "storageAllocation");
define("CONFIG_EMAIL_ADDRESS", "emailAddress");
define("CONFIG_STORAGE_DIRECTORY", "storageDirectory");
