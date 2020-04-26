<?php

require_once('./config.php');
require_once('./logger.php');
require_once('./scripts.php');
require_once('./util.php');

// Logger::logEnvironment();

// Base class definition

class ApiResponse {
  public $data;
  public $error;
  public $statusCode = 200;

  public function respond()
  {
    $this->setHeaders();
    echo $this->getResponse();
  }

  protected function getResponse () {
    $response = array(
      "statusCode" => $this->statusCode
    );
    if ($this->data) {
      $response["data"] = $this->data;
    }
    if ($this->error) {
      $response["error"] = $this->error;
    }
    return json_encode($response);
  }

  private function setHeaders() {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("HTTP/1.1 ". $this->statusCode);
  }
}

class ApiSuccessResponse extends ApiResponse {
  public function __construct($data) {
    $this->data = $data;
  }
}

class APiErrorResponse extends ApiResponse {
  public function __construct($error, $statusCode) {
    $this->error = $error;
    $this->statusCode = $statusCode;
  }
}

// Actual api functionality

function handlePostRequest() {
  $config = new Config();
  $scripts = new Scripts();
  $data = Util::getPostData();
  switch ($data->action) {
    case 'config':
      $configFileData = $config->writeConfigFile($data->data);
      return new ApiSuccessResponse($configFileData);
    break;
    case 'start':
      $output = $scripts->start();
      return new ApiSuccessResponse(['output' => $output]);
    case 'check':
      $output = $scripts->check();
      return new ApiSuccessResponse(['output' => $output]);
    case 'stop':
      $output = $scripts->stop();
      return new ApiSuccessResponse(['output' => $output]);
    case 'update':
      $output = $scripts->update();
      return new ApiSuccessResponse(['output' => $output]);
    case '':
    default:
      return new ApiErrorResponse('Valid action is required', 400);
  }
}

function handleGetRequest() {
  $config = new Config();
  switch ($_GET['action']) {
    case 'config':
      $configFileData = $config->readConfigFile();
      return new ApiSuccessResponse($configFileData);
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
  $response = new ApiErrorResponse('Server error', 500);
}


$response->respond();

?>
