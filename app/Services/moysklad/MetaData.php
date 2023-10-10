<?php

namespace App\moysklad;

use App\App;
use App\BaseModel;

class MetaData extends BaseModel
{
  public $href;
  public $metadataHref;
  public $type;
  public $mediaType = "application/json";

  public function __construct($arg)
  {
    $this->href = $arg['scope'].$arg['id'];
    $this->metadataHref = $arg['scope'].'metadata';
    $this->type = $arg['type'];
  }
}