<?php
/**
 * Created by PhpStorm.
 * User: Professional
 * Date: 16.06.2023
 * Time: 6:36
 */

namespace App\Services\moysklad;


class Resource
{
  /**
   * @param $name
   * @return false|int
   */
  public static function getCreateTime($name)
  {
    return filemtime(SITE_PATH . "res/" . $name);
  }

  /**
   * @param $name
   * @return array
   */
  public static function getJson($name, $default = '')
  {
    if (!file_exists(SITE_PATH . "res/" . $name)) {
      return $default;
    }
    $cont = file_get_contents(SITE_PATH . "res/" . $name);
    return (array)json_decode($cont, JSON_OBJECT_AS_ARRAY | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  }

  /**
   * @param $name
   * @param $json
   */
  public static function putJson($name, $json)
  {
    file_put_contents(SITE_PATH . "res/" . $name, Helpers::dataJsonEncode($json));
  }

  /**
   * @param $name
   * @param $data
   */
  public static function putRaw($name, $data)
  {
    file_put_contents(SITE_PATH . "res/" . $name, $data);
  }

  /**
   * @param $name
   * @param $data
   */
  public static function getRaw($name)
  {
    return file_get_contents(SITE_PATH . "res/" . $name);
  }

  /**
   * @param $name
   * @return bool
   */
  public static function exists($name)
  {
    return file_exists(SITE_PATH . "res/" . $name);
  }
}
