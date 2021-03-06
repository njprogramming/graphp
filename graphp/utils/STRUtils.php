<?php

class STRUtils {

  public static function to64BitInt($string) {
    $hash = substr(md5($string), 17); // truncate to 64 bits
    return (int) base_convert($hash, 16, 10); // base 10
  }

  /**
   * Basic HTML escaping for user input
   * @param  string $str String to be escaped.
   * @return string
   */
  public static function esc($str) {
    $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
    return htmlentities($str, ENT_QUOTES, 'UTF-8');
  }

}

class_alias('STRUtils', 'STR');
