<?php

namespace App\core;

use League\Plates\Engine;

abstract class Controller 
{

  protected $templates;

  public function __construct(Engine $engine) 
  {
    $this->templates = $engine;
  }

  

  
}