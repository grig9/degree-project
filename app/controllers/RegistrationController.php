<?php

namespace App\controllers;

<<<<<<< Updated upstream
use App\controllers\Controller;

class RegistrationController extends Controller
{
=======
use App\controllers\MainController;

class RegistrationController extends MainController
{
  
>>>>>>> Stashed changes
  public function registration_form() 
  {
    echo $this->templates->render('layout/registration_form', 
      [
        'title' => 'Registration form',
        'flash_output' => $this->flash->display(),
      ]
    );
  }

  public function registration() 
  {
    try {
<<<<<<< Updated upstream
      $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'] = NULL);

      $this->flash->success('Вы успешно зарегестрировались.');
      Redirect::to("/");
      exit();
=======
      $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'] = NULL);
      
      $this->flash->success('Вы успешно зарегестрировались.');
      Redirect::to("/");
>>>>>>> Stashed changes
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        $this->flash->error('Неверный email');
        Redirect::to("/registration_form");
<<<<<<< Updated upstream
        exit();
=======
>>>>>>> Stashed changes
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        $this->flash->error('Неверный пароль');
        Redirect::to("/registration_form");
<<<<<<< Updated upstream
        exit();
=======
>>>>>>> Stashed changes
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
        $this->flash->error('Пользователь уже существует');
        Redirect::to("/registration_form");
<<<<<<< Updated upstream
        exit();
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        $this->flash->error('Слишком много запросов на регистрацию');
        Redirect::to("/registration_form");
        exit();
    }
  }

  public function email_verification()
  {
    try {
      $this->auth->confirmEmail('seDHjJSOosjCbmYd', 'DaJ_atlu_XNxkD7m');
  
   
      $this->flash->success('Email address has been verified');
      Redirect::to("/");
      exit();
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        die('Invalid token');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
        die('Token expired');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $this->flash->error('Пользователь уже существует');
      Redirect::to("/registration_form");
      exit();
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      $this->flash->error('Слишком много запросов');
      Redirect::to("/registration_form");
      exit();
    }
  }
=======
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        $this->flash->error('Слишком много запросов на рег');
        Redirect::to("/registration_form");
    }
  }

>>>>>>> Stashed changes
}