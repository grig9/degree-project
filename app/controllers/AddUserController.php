<?php

namespace App\controllers;

use App\Redirect;
use App\controllers\Controller;
use App\File;

class AddUserController extends Controller
{
  public function show_create_user_form() 
  {
    if(!$this->is_Admin()) 
    {
      $this->flash->error('Доступ запрещен');
      Redirect::to("/users/1");
      exit;
    }

    echo $this->templates->render('layout/create_user_form', 
      [
        'title' => 'Create new user',
        'flash_output' => $this->flash->display(),
        'login_state' => $this->login_state(),
      ]
    );
  }

  public function create_user() 
  {
    try {
      $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);

      
      if($_FILES['image']['error'] === UPLOAD_ERR_OK ) {
        $image = File::save();
      }

      $this->db->updateById('users', [ 
        'position' => $_POST['position'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'status_user' => $_POST['status_user'],
        'image' => $image,
        'vk' => $_POST['vk'],
        'telegram' => $_POST['telegram'],
        'instagram' => $_POST['instagram']
       ], $userId);
  
      $this->flash->success('Вы успешно добавили нового пользователя с ID ' . $userId);
      Redirect::to("/users/1");
      exit;
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      $this->flash->error('Не верный ввод email');
      Redirect::to("/create-user-form");
      exit;
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      $this->flash->error('Введите пароль');
      Redirect::to("/create-user-form");
      exit;
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $this->flash->error('Пользователь с таким email уже существует! <br>Пожалуйста, ведите другой email');
      Redirect::to("/create-user-form");
      exit;
    }

  }
}