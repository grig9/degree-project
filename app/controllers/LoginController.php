<?php

namespace App\controllers;

use App\controllers\Controller;

class LoginController extends Controller
{
  public function login_form() 
  {
    echo $this->templates->render('layout/login_form', 
      [
        'title' => 'Login',
        'flash_output' => $this->flash->display(),
      ]
    );
  }

  public function login() 
  {
    if (isset($_POST['remember']) == 1) {
      // keep logged in for one year
      $rememberDuration = (int) (60 * 60 * 24 * 365.25);
    }
    else {
      // do not keep logged in after session ends
      $rememberDuration = null;
    }

    try {
      $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);
      
      $this->flash->success('<b>Поздравляю!</b> Вы успешно авторизировались.');
      Redirect::to("/users/1");
      exit;
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      $this->flash->error('Не верный эл.адрес!');
      Redirect::to("/");
      exit;
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      $this->flash->error('Не верный пароль!');
      Redirect::to("/");
      exit;
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
      $this->flash->error('Эл.адрес не подтвержден!');
      Redirect::to("/");
      exit;
    }
    catch (\Delight\Auth\TooManyRequestsException $e) { 
      $this->flash->error('Слишком много запросов на авторизацию');
      Redirect::to("/registration_form");
      exit;
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      $this->flash->error('Too many requests!');
      Redirect::to("/");
    }
  }
}