<?php
// контроллер объединяет модель с видом
// модель отвечает за бизнес-логику
// вид отвечает за вывод данных на экран
namespace App\core;


use App\core\Model;
use League\Plates\Engine;

abstract class Controller
{

  protected $templates;

  public function __construct(Engine $engine) 
  {
    $this->templates = $engine;
  }

  

  
}