<?php

namespace App\Services\moysklad;

abstract class BaseModel
{
  public function __construct($arguments)
  {
    foreach ($arguments as $var => $value) {
      $this->{$var} = $value;
    }
  }

  public function __set($name, $value)
  {
    if (method_exists($this, "set" . ucfirst($name))) {
      $this->{"set" . ucfirst($name)}($value);
      return;
    }
    $this->$name = $value;
  }

  public function getAsArray(): array
  {
    $params = [];
    foreach(get_class_vars(get_class($this)) as $param => $value){
      if (!empty($this->{$param}) && $this->{$param}[0] !== '_'){
        $params[$param] = (string) $this->$param;
      }
    }
    return $params;
  }

}
