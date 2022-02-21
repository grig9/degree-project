<?php

namespace App\controllers;

use League\Plates\Engine;
use Delight\Auth\Auth;
use App\QueryBuilder;
use Tamtamchik\SimpleFlash\Flash;

abstract class Controller 
{
  protected $db;
  protected $templates;
  protected $auth;
  protected $flash;
  protected $paginator;

  public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth, Flash $flash) 
  {
    $this->db = $qb;
    $this->templates = $engine;
    $this->auth = $auth;
    $this->flash = $flash;
  }
}