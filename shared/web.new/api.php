<?php

require_once(__DIR__ . '/lib/config.php');
require_once(__DIR__ . '/lib/constants.php');
require_once(__DIR__ . '/lib/logger.php');
require_once(__DIR__ . '/lib/response.php');
require_once(__DIR__ . '/lib/scripts.php');
require_once(__DIR__ . '/lib/util.php');


function exception_error_handler($errno, $errstr, $errfile, $errline ) {
  throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler("exception_error_handler");

function handlePostRequest() {
  $config = new Config();
  $scripts = new Scripts();
  $data = Util::getPostData();
  switch ($data->action) {
    case 'config':
      $configFileData = $config->writeConfigFile($data->data);
      return new ApiSuccessResponse($configFileData);
    break;
    case 'update':
      $output = $scripts->update();
      return new ApiSuccessResponse($output);
    case '':
    default:
      return new ApiErrorResponse('Valid action is required', 400);
  }
}

function handleGetRequest() {
  $config = new Config();
  $scripts = new Scripts();
  $query = Util::getQueryData();
  switch ($_GET['action']) {
    case 'check':
      $output = $scripts->check();
      return new ApiSuccessResponse($output);
    case 'config':
      $configFileData = $config->readConfigFile();
      return new ApiSuccessResponse($configFileData);
    case 'start':
      $output = $scripts->start();
      return new ApiSuccessResponse($output);
    case 'stop':
      $output = $scripts->stop();
      return new ApiSuccessResponse($output);
    case 'validate':
      $output = $config->validateConfigFile();
      return new ApiSuccessResponse($output);
    case 'tail':
    case 'tailIdentity':
      $output = Logger::tail($query->lines);
      return new ApiSuccessResponse($output);
  }
  return new ApiErrorResponse('Not found', 404);
}

try {
  switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
      $response = handlePostRequest();
      break;
    case "GET":
      $response = handleGetRequest();
      break;
    default:
      $response = new ApiErrorResponse('Not found', 404);
  }
} catch (Exception $e) {
  // $message = $e->getMessage() ? $e->getMessage() : "Server Error";
  $response = new ApiErrorResponse("Server Error", 500);
}


$response->respond();

?>
