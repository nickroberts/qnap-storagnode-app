<?php

require_once(__DIR__ . '/constants.php');

class Logger {
  public static function log($message) {
    $file = STORJ_LOG_FILE;
    $message = preg_replace('/\n$/', '', $message);
    file_put_contents($file, Util::formatDate() . " | " . $message . "\n", FILE_APPEND);
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

  public static function tail($lines = 50, $filepath = STORJ_LOG_FILE, $adaptive = true) {
    $f = @fopen($filepath, "rb");
		if ($f === false) return false;
		if (!$adaptive) $buffer = 4096;
		else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
		fseek($f, -1, SEEK_END);
		if (fread($f, 1) != "\n") $lines -= 1;
		$output = '';
		$chunk = '';
		while (ftell($f) > 0 && $lines >= 0) {
			$seek = min(ftell($f), $buffer);
			fseek($f, -$seek, SEEK_CUR);
			$output = ($chunk = fread($f, $seek)) . $output;
			fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
			$lines -= substr_count($chunk, "\n");
    }
		while ($lines++ < 0) {
			$output = substr($output, strpos($output, "\n") + 1);
		}
		fclose($f);
		return trim(implode("\n", array_reverse(explode("\n", $output))));
		// return trim($output);
	}
}
