<?php

namespace App\controllers;

use App\Redirect;
use App\controllers\Controller;

use JasonGrimes\Paginator;

class HomeController extends Controller 
{

  public function show_users(int $id) 
  {

    $itemsPerPage = 6;
    $currentPage = $id;
    $totalItems = $this->db->getAllCount('users');
    $urlPattern = '/users/(:num)';

    $result = $this->db->getAllPaginator('users', $itemsPerPage, $currentPage);

    $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

    echo $this->templates->render('layout/users', 
      [
        'title' => 'Пользователи',
        'users' => $result,
        'flash_output' => $this->flash->display(),
        'login_state' => $this->login_state(),
        'is_admin' => $this->is_Admin(),
        'auth_id' => $this->auth->id(),
        'paginator' => $paginator,
      ]
    );
  }

  public function show_user_profile(int $id) 
  {
    $result = $this->db->getOneById('users', $id);

    echo $this->templates->render('layout/page_profile', 
      [
        'title' => 'Профиль',
        'user' => $result,
        'flash_output' => $this->flash->display(),
        'login_state' => $this->login_state(),
      ]
    );
  }

  public function show_status_form(int $id)
  {

    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
      exit;
    }

    $user = $this->db->getOneById('users', $id);

    echo $this->templates->render('layout/status', 
      [
        'title' => 'Статус',
        'login_state' => $this->login_state(),
        'user' => $user,
      ]
    );
  }

  public function set_user_status() 
  {
    // d($_POST);die;
    $this->db->updateById('users', 
    ['status_user' => $_POST['status_user']],
    $_POST['id']);
    
    $this->flash->success('Вы успешно обновили статус');
    Redirect::to("/users/1");
  }

  public function show_security_form(int $id)
  {
    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
      exit;
    }

    $result = $this->db->getOneById('users', $id);

    echo $this->templates->render('layout/security_form', 
      [
        'title' => 'Security',
        'user' => $result,
        'login_state' => $this->login_state(),
        'flash_output' => $this->flash->display()
      ]
    );
  }

  public function security()
  {
    try {
      if ($this->auth->reconfirmPassword($_POST['password'])) {
        $this->auth->changeEmail($_POST['newEmail'], function ($selector, $token) {
            echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email to the *new* address)';

            echo '<a href="/verification/' . urlencode($selector) . '/' . urlencode($token) . '">Email изменен</a>';
        });

        echo 'The change will take effect as soon as the new email address has been confirmed';
      }
      else {
        $this->flash->error('Не верный пароль!');
        Redirect::to("/security-user/$_POST[id]");
        exit;
      }
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      $this->flash->error('Не верный эл.адрес!');
      Redirect::to("/security-user/$_POST[id]");
      exit;
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $this->flash->error('Тако эл.адрес уже существует!');
      Redirect::to("/security-user/$_POST[id]");
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
      $this->flash->error('Эл.адрес не подтвержден!');
      Redirect::to("/security-user/$_POST[id]");
      exit;
    }
    catch (\Delight\Auth\NotLoggedInException $e) {
      $this->flash->error('Вы не авторизированы!');
      Redirect::to("/");
      exit;
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      $this->flash->error('Too many requests!');
      Redirect::to("/users/1");
    }
  }

  public function verification($selector, $token) 
  {
    try {
      $this->auth->confirmEmail($selector, $token);

      $this->flash->success('Email address has been verified');
      Redirect::to("/users/1");
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      die('Invalid token');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
      die('Token expired');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $this->flash->error('Такой email уже существует!');
      Redirect::to("/");
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      $this->flash->error('Too many requests!');
      Redirect::to("/users/1");
    }
  }

  public function show_edit_user_form(int $id) 
  { 
       
    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
    }

    $result = $this->db->getOneById('users', $id);

      echo $this->templates->render('layout/edit_user_form', 
        [
          'title' => 'Edit user',
          'user' => $result,
          'login_state' => $this->login_state(),
        ]
      );
  }

  public function edit_user()
  {
    $data = [ 
      'username' => $_POST['name'],
      'position' => $_POST['position'],
      'phone' => $_POST['phone'],
      'address' => $_POST['address'],
    ];
  
    $this->db->updateById('users', $data, $_POST['id']);
    
    $this->flash->success('Профиль успешно обновлен!');
    Redirect::to("/users/1");
  }

  public function user_delete(int $id)
  {

    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
    }

    if($this->is_Admin())
    {
      try {
        $this->auth->admin()->deleteUserById($id);
        $this->flash->warning('Профиль успешно удален!');
        Redirect::to("/users/1");
      }
      catch (\Delight\Auth\UnknownIdException $e) {
        $this->flash->error('Такого пользователя не существует');
        Redirect::to("/users/1");
      }
    }

    if($this->auth->id() === $id) {
      try {

        try {
          $this->auth->logOutEverywhere();
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
          $this->flash->error("Вы не были авторизирвованы");
          Redirect::to("/");
          exit;
        }

        $this->auth->admin()->deleteUserById($id);
        $this->flash->warning('Вы успешно удалили свой профиль!');

        Redirect::to("/users/1");
      }
      catch (\Delight\Auth\UnknownIdException $e) {
        $this->flash->error('Такого пользователя не существует');
        Redirect::to("/users/1");
      }
    }

    

  }

}

