<?php

namespace App\controllers;

use App\controllers\Controller;


class RegistrationController extends Controller
{
  
  public function registration_form() 
  {
    echo $this->templates->render('layout/registration_form', 
      [
        'title' => 'Registration form',
        'flash_output' => $this->flash->display(),
      ]
    );
  }

}