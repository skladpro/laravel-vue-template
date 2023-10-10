<?php

namespace App\Services\moysklad;


class Helpers
{
    public static function normalizePhone($phone){
        if (empty($phone)){
            return "";
        }
        $result = preg_replace('/^\+7|^8|^7|[\- \(\)]+/', '', $phone);
        return '7' . $result;
    }

    public static function getLastUrlPart($url){
      return array_pop(explode('/', $url));
    }

    public static function prettyJsonEncode($arr){
      return json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function dataJsonEncode($arr){
      return json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

}
