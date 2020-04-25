<?php

require_once('./classes/config.php');
require_once('./classes/util.php');

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


$data = Util::getPostData(array());

$config = new Config();
$configFileData = $config->readConfigFile();
$configFileData->Identity = "nice";
$newConfigFileData = $config->writeConfigFile($configFileData);

Util::dd($newConfigFileData);

try {
  switch ($data->action) {
    case 'config':
      $response = new ApiSuccessResponse($data);
    break;
    case '':
    default:
      $response = new ApiErrorResponse('Valid action is required', 400);
  }
} catch (Exception $e) {
  $response = new ApiErrorResponse('Server error', 500);
}


$response->respond();

?>
