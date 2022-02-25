<?php

namespace App\controllers;

use League\Plates\Engine;
use Delight\Auth\Auth;
use App\QueryBuilder;
use Tamtamchik\SimpleFlash\Flash;
use App\Redirect;

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

  public function is_Admin()
  {
    if ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
      return true;
    } else {
      return false;
    }
  }

  public function login_state() 
  {
    if ($this->auth->isLoggedIn()) {
      // echo 'User is signed in';
      return true;
    }
    else {
      // echo 'User is not signed in yet';
      return false;
    }
  }

  public function logout() 
  {
    try {
      $this->auth->logOutEverywhere();
      $this->flash->success("Вы вышли из системы");
      Redirect::to("/");
      exit;
    }
    catch (\Delight\Auth\NotLoggedInException $e) {
      $this->flash->error("Вы не были авторизирвованы");
      Redirect::to("/");
      exit;
    }
  }

  
}