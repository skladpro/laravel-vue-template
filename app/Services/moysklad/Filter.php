<?php
/**
 * Created by PhpStorm.
 * User: Professional
 * Date: 16.06.2023
 * Time: 22:37
 */

namespace App\moysklad;


use App\BaseModel;

class Filter extends BaseModel
{
  protected $field;
  protected $operator;
  protected $value;
}