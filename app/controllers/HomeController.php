<?php

namespace App\controllers;

use App\exceptions\NotEnoughMoneyException;
use App\exceptions\AccountIsBlockException;

use League\Plates\Engine;
use Delight\Auth\Auth;
use Faker\Factory;
use App\controllers\Redirect;
use App\controllers\Controller;
use JasonGrimes\Paginator;

class HomeController extends Controller 
{
  
  public function fake_posts()
  {
    $faker = Factory::create();

    for ($i = 0; $i < 100; $i++) {
      $this->db->insert('posts',
        [
          'title' => $faker->word(3, true),
          'content' => $faker->paragraphs(10, true),
        ]
      );
    }
  }

  public function users(int $id) 
  {
    $itemsPerPage = 9;
    $currentPage = $id;
    $totalItems = $this->db->getAllCount('users2');
    $urlPattern = '/users/(:num)';

    $result = $this->db->getAllPaginator('users2', $itemsPerPage, $currentPage);

    $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

    echo $this->templates->render('layout/users', 
      [
        'title' => 'Пользователи',
        'users' => $result,
        'flash_output' => $this->flash->display(),
        'login_state' => $this->login_state(),
        'is_admin' => $this->auth->hasRole(\Delight\Auth\Role::ADMIN),
        'auth_id' => $this->auth->id(),
        'paginator' => $paginator,
      ]
    );
  }

  public function page_profile(int $id) 
  {
    $result = $this->db->getOneById('users2', $id);

    echo $this->templates->render('layout/page_profile', 
      [
        'title' => 'Профиль',
        'user' => $result,
        'flash_output' => $this->flash->display(),
        'login_state' => $this->login_state(),
      ]
    );
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

  public function status_form(int $id)
  {

    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
      exit();
    }


    $user = $this->db->getOneById('users2', $id);

    echo $this->templates->render('layout/status', 
      [
        'title' => 'Статус',
        'login_state' => $this->login_state(),
        'user' => $user,
      ]
    );
  }

  public function about() 
  {
    try 
    {
      $this->test(105);
    } 
    catch (NotEnoughMoneyException $excepiton) 
    {
      flash()->error('Ваш баланс меньше чем 105' );
    }
     catch (AccountIsBlockException $excepiton) 
    {
      flash()->error($excepiton->getMessage());
    }

    echo $this->templates->render('page', 
      [
        'title' => 'About',
      ]
    );
  }

  function test($amount = 1) {
    $start = 100;

    // throw new AccountIsBlockException('Your account is blocked');
  
    if($amount > $start) {
      throw new NotEnoughMoneyException('Your balance is less than '. $amount);
    } else {
      echo 'Выводим '. $amount . ' средств';
    }
  }

  public function security_form(int $id)
  {
    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
      exit;
    }

    $result = $this->db->getOneById('users2', $id);

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
    if(!$this->is_Admin()) 
    {
      $this->flash->error('Вы не можете добавлять новых пользователей');
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
    $name = $_POST['name'];
    $position = $_POST['position'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $status = $_POST['status'];
    $image_name = $_POST['image_name'];
    $vk = $_POST['vk'];
    $telegram = $_POST['telegram'];
    $instagram = $_POST['instagram'];

    $userByEmail = $this->db->getOneByEmail('users2', $email);

    if(!empty($userByEmail)) {     
      $this->flash->error('Пользователь с таким email существует! <br>Пожалуйста, введите новый email');
      Redirect::to("/create-user-form");
      exit;
    } 

    try {
      $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
      $this->db->insert('users2', [ 
        'name' => $name,
        'position' => $position,
        'phone' => $phone,
        'address' => $address,
        'email' => $email,
        'password' => $password,
        'status' => $status,
        'image_name' => $image_name,
        'vk' => $vk,
        'telegram' => $telegram,
        'instagram' => $instagram,
        'role' => 'user'
       ]);
  
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
      $this->flash->error('Пользователь с таким email уже существует! <br>Пожалуйста, ведите новый email');
      Redirect::to("/create-user-form");
      exit;
    }

  }


  public function edit_user_form(int $id) 
  { 
       
    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
      exit;
    }

    $result = $this->db->getOneById('users2', $id);

      echo $this->templates->render('layout/edit_user_form', 
        [
          'title' => 'Edit user',
          'user' => $result,
          'login_state' => $this->login_state(),
        ]
      );
  }

  public function edit_user(int $id)
  {
    $data = [ 
      'name' => $_POST['name'],
      'position' => $_POST['position'],
      'phone' => $_POST['phone'],
      'address' => $_POST['address'],
    ];
  
    $this->db->updateById('users2', $data, $id);
    
    $this->flash->success('Профиль успешно обновлен!');
    Redirect::to("/users/1");
    exit;
  }

  public function user_delete(int $id)
  {
    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
      exit;
    }

    $this->db->deleteById('users2', $id);
    $this->flash->warning('Профиль успешно удален!');
    Redirect::to("/users/1");
    exit;
  }

}

