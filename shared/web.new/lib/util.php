<?php

class Util {
  public static function dd ($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
  }

  public static function getPostData() {
    if(!empty($_POST)) {
      $post = $_POST;
    } else {
      $post = json_decode(file_get_contents("php://input"));
    }
    if(json_last_error() == JSON_ERROR_NONE) {
      return (object) json_decode(json_encode($post), true);
    }
    return (object) [];
  }

  public static function getQueryData() {
    if(!empty($_GET)) {
      return (object) json_decode(json_encode($_GET), true);
    }
    return (object) [];
  }
}
