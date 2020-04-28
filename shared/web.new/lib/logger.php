<?php

require_once(__DIR__ . '/constants.php');

class Logger {
  public static function log($message) {
    $file = STORJ_LOG_FILE;
    $message = preg_replace('/\n$/', '', $message);
    $datetime = new DateTime();
    $timestamp = $datetime->format(DateTime::ISO8601) . " | ";
    file_put_contents($file, $timestamp . $message . "\n", FILE_APPEND);
  }

  public static function logEnvironment() {
    Logger::log(
        "\n----------------------------------------------\n"
      . "ENV is : " . print_r($_ENV, true)
      . "POST is : " . print_r($_POST, true)
      . "SERVER is : " . print_r($_SERVER, true)
      . "----------------------------------------------\n"
    );
  }

  public static function tail($lines = 10, $file = STORJ_LOG_FILE) {
    $handle = fopen($file, "r");
    $linecounter = $lines;
    $pos = -2;
    $beginning = false;
    $text = array();
    while ($linecounter > 0) {
        $t = " ";
        while ($t != "\n") {
            if(fseek($handle, $pos, SEEK_END) == -1) {
                $beginning = true;
                break;
            }
            $t = fgetc($handle);
            $pos --;
        }
        $linecounter --;
        if ($beginning) {
            rewind($handle);
        }
        $text[$lines-$linecounter-1] = fgets($handle);
        if ($beginning) break;
    }
    fclose ($handle);
    return join($text);
  }
}
