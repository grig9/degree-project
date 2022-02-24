<?php

namespace App\controllers;

use App\core\Controller;

class LoginController extends Controller
{
  public function login_form() 
  {
    echo $this->templates->render('layout/login_form', 
      [
        'title' => 'Login',
        // 'flash_output' => $this->flash->display(),
      ]
    );
  }
}