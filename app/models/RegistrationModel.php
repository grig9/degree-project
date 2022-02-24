<?php

namespace App\models;

use App\Redirect;
use App\core\Model;

class RegistrationModel extends Model
{
  
  public function registration() 
  {
    try {

      $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'] = NULL);

      $this->flash->success('Вы успешно зарегестрировались.<br>Введите email и пароль, чтобы войти');
      Redirect::to("/");
      exit;
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      $this->flash->error('Неверный email');
      Redirect::to("/registration_form");
      exit;
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      $this->flash->error('Неверный пароль');
      Redirect::to("/registration_form");
      exit;
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $this->flash->error('Пользователь уже существует');
      Redirect::to("/registration_form");
      exit;
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      $this->flash->error('Слишком много запросов на регистрацию');
      Redirect::to("/registration_form");
      exit;
    }
  }

  public function email_verification()
  {
    try {
      $this->auth->confirmEmail('seDHjJSOosjCbmYd', 'DaJ_atlu_XNxkD7m');
  
   
      $this->flash->success('Email address has been verified');
      Redirect::to("/");
      exit;
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
      exit;
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      $this->flash->error('Слишком много запросов');
      Redirect::to("/registration_form");
      exit;
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      $this->flash->error('Слишком много запросов на рег');
      Redirect::to("/registration_form");
      exit;
    }
  }

}