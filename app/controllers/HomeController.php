<?php

namespace App\controllers;

use App\core\Controller;
// use JasonGrimes\Paginator;
use App\models\HomeModel;

class HomeController extends Controller 
{

  public function users(int $id) 
  {
    // $obj = new HomeModel();
    $data =  $obj->users_data($id);

    
    echo $this->templates->render('layout/users', 
      [
        'title' => 'Пользователи',
        'users' => $data['result'],
        'flash_output' => $this->flash->display(),
        'login_state' => $this->login_state(),
        'is_admin' => $this->auth->hasRole(\Delight\Auth\Role::ADMIN),
        'auth_id' => $this->auth->id(),
        'paginator' => $data['paginator'],
      ]
    );
  }

  public function page_profile(int $id) 
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


 
  public function status_form(int $id)
  {

    // if(!$this->is_Admin() and $this->auth->id() !== $id) {
    //   $this->flash->error('Вы не можете редактировать других пользователей');
    //   Redirect::to("/users/1");
    //   exit();
    // }

    $user = $this->db->getOneById('users', $id);

    echo $this->templates->render('layout/status', 
      [
        'title' => 'Статус',
        'login_state' => $this->login_state(),
        'user' => $user,
      ]
    );
  }


  public function security_form(int $id)
  {
    // if(!$this->is_Admin() and $this->auth->id() !== $id) {
    //   $this->flash->error('Вы не можете редактировать других пользователей');
    //   Redirect::to("/users/1");
    //   exit;
    // }

    $result = $this->db->getOneById('users', $id);

    echo $this->templates->render('layout/security_form', 
      [
        'title' => 'Security',
        'user' => $result,
        'login_state' => $this->login_state(),
      ]
    );
  }

  

 

  public function create_user_form() 
  {
    // if(!$this->is_Admin()) 
    // {
    //   $this->flash->error('Доступ запрещен');
    //   Redirect::to("/users/1");
    //   exit;
    // }

    echo $this->templates->render('layout/create_user_form', 
      [
        'title' => 'Create new user',
        'flash_output' => $this->flash->display(),
        'login_state' => $this->login_state(),
      ]
    );
  }



  public function edit_user_form(int $id) 
  { 
       
    // if(!$this->is_Admin() and $this->auth->id() !== $id) {
    //   $this->flash->error('Вы не можете редактировать других пользователей');
    //   Redirect::to("/users/1");
    //   exit;
    // }

    $result = $this->db->getOneById('users', $id);

      echo $this->templates->render('layout/edit_user_form', 
        [
          'title' => 'Edit user',
          'user' => $result,
          'login_state' => $this->login_state(),
        ]
      );
  }

  public function login_state() 
  {
    return true;
  }



}

