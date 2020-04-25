<?php

class Util {
  public static function dd ($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
  }

  public static function getPostData($MY_POST) {
    if(!empty($MY_POST)) {
      $post = $MY_POST;
    } else {
      $post = json_decode(file_get_contents("php://input"));
    }
    if(json_last_error() == JSON_ERROR_NONE) {
      return (object) json_decode(json_encode($post), true);
    }
    return (object) [];
  }
}
