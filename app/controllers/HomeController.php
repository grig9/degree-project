<?php

namespace App\controllers;

use App\exceptions\NotEnoughMoneyException;
use App\exceptions\AccountIsBlockException;
<<<<<<< Updated upstream
=======

use League\Plates\Engine;
use Delight\Auth\Auth;
>>>>>>> Stashed changes
use Faker\Factory;
use App\controllers\Redirect;
use App\controllers\Controller;

class HomeController extends  Controller 
{
  

  public function paginator($id)
  {
    $itemsPerPage = 5;
    $currentPage = $id;
    $totalItems = $this->db->getAllCount('posts');
    $urlPattern = '/paginator/page/(:num)';

    $posts = $this->db->getAllPaginator('posts', $itemsPerPage, $currentPage);


    d($this->paginator);die;
    // $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    
    echo $this->templates->render('paginator', 
    [
      'title' => 'Paginator',
      'paginator' => $paginator,
      'posts' => $posts
    ]
  );
  }

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

  public function users($id) 
  {
    $itemsPerPage = 9;
    $currentPage = $id;
    $totalItems = $this->db->getAllCount('users2');
    $urlPattern = '/users/(:num)';

    $result = $this->db->getAllPaginator('users2', $itemsPerPage, $currentPage);

    $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

    // $result = $this->db->getAll('users2');

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

  public function page_profile($id) 
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

<<<<<<< Updated upstream
=======
 

  public function email_verification()
  {
    try {
      $this->auth->confirmEmail('seDHjJSOosjCbmYd', 'DaJ_atlu_XNxkD7m');
  
      echo 'Email address has been verified';
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        die('Invalid token');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
        die('Token expired');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
        die('Email address already exists');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        die('Too many requests');
    }
  }

>>>>>>> Stashed changes
  public function logout() 
  {
    try {
      $this->auth->logOutEverywhere();
      $this->flash->success("Вы вышли из системы");
      Redirect::to("/");
      exit();
    }
    catch (\Delight\Auth\NotLoggedInException $e) {
      $this->flash->error("Вы не ввошли в систему");
      Redirect::to("/");
<<<<<<< Updated upstream
      exit();
=======
>>>>>>> Stashed changes
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

  public function status_form($id)
  {
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

  public function security_form($id)
  {
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
    if(!$this->is_Admin()) 
    {
      $this->flash->error('Вы не можете добавлять новых пользователей');
      Redirect::to("/users");
      exit();
    }

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
      $this->flash->error('Пользователь с таким email существует! <br>Пожалуйста, ведите новый email');
      Redirect::to("/create-user-form");
      exit();
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
      Redirect::to("/users");
      exit();
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      $this->flash->error('Не верный ввод email');
      Redirect::to("/create-user-form");
      exit();
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      $this->flash->error('Введите пароль');
      Redirect::to("/create-user-form");
      exit();
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $this->flash->error('Пользователь с таким email уже существует! <br>Пожалуйста, ведите новый email');
      Redirect::to("/create-user-form");
      exit();
    }

  }

  public function is_Admin()
  {
    if ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
      return true;
    } else {
      return false;
    }
  }


  public function checkLoggedInAndRedirect()
  {
    if(!$this->auth->check())
    {
      $this->flash->success('Вы не авторизированы!');
      Redirect::to("/");
      exit();
    }
  }

  public function edit_user_form(int $id) 
  { 
       
    $currenAuthUserId = $this->auth->id();
   
    if($this->is_Admin() or $currenAuthUserId === $id) 
    {
      $result = $this->db->getOneById('users2', $id);

      echo $this->templates->render('layout/edit_user_form', 
        [
          'title' => 'Edit user',
          'user' => $result,
          'login_state' => $this->login_state(),
        ]
      );
    } else {
      $this->flash->error("Вы не можете редактировать других пользователей");
      Redirect::to("/users");
      exit();
    }
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
    Redirect::to("/users");
    exit();
  }

  public function user_delete(int $id)
  {
    $this->checkLoggedInAndRedirect();

    if($this->is_Admin()) {

      $this->db->deleteById('users2', $id);
      $this->flash->success('Профиль успешно удален!');
      Redirect::to("/users");
    }
    // if(this->auth is admin or authUserId === $userId) 
    if($this->auth->getUserId() === $id) {
      $this->db->deleteById('users2', $id);

      $this->flash->success('Профиль успешно удален!');
      Redirect::to("/users");
    }
  

    $this->flash->success('Вы не можете редактировать профиль!');
    Redirect::to("/users");
    exit();
  }

}

