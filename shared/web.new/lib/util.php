<?php

class Util {
  public static function d ($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
  }

  public static function dd ($data) {
    Util::d($data);
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

  public static function toCamelCase($string){
    if (ctype_upper($string)) {
      $string = ucwords(strtolower($string));
    }
    $string = preg_replace_callback('/\\b(\\w)/', function($m) {
      return strtoupper($m[0]);
    }, $string);
    $string = str_replace(' ', '', $string);
    $string[0] = strtolower($string[0]);
    return $string;
  }

  public static function convertKeysToCamelCase($arr) {
    $output = [];
    ksort($arr, SORT_NATURAL | SORT_FLAG_CASE);
    foreach ($arr as $key => $value) {
      $k = Util::toCamelCase($key);
      $output[$k] = is_array($value) ? Util::convertKeysToCamelCase($value) : $value;
    }
    return $output;
  }

  public static function formatDate($date = null) {
    $datetime = new DateTime();
    return $datetime->format(DateTime::ISO8601);
  }
}
