<?php

namespace App\core;

use Delight\Auth\Auth;
use App\QueryBuilder;
use Tamtamchik\SimpleFlash\Flash;

abstract class Model 
{
  protected $db;
  protected $auth;
  protected $flash;

  public function __construct(QueryBuilder $qb, Auth $auth, Flash $flash) 
  {
    $this->db = $qb;
    $this->auth = $auth;
    $this->flash = $flash;
  }

  public function is_Admin()
  {
    if ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
      return true;
    } else {
      return false;
    }
  }

  
}