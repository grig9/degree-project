<?php

namespace App\controllers;

use App\exceptions\NotEnoughMoneyException;
use App\exceptions\AccountIsBlockException;
use League\Plates\Engine;
use Delight\Auth\Auth;
use Faker\Factory;
use JasonGrimes\Paginator;
use App\QueryBuilder;
use App\controllers\Redirect;
use Tamtamchik\SimpleFlash\Flash;

class HomeController 
{
  private $db;
  private $templates;
  private $auth;
  public $flash;

  public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth, Flash $flash) 
  {
    $this->db = $qb;
    $this->templates = $engine;
    $this->auth = $auth;
    $this->flash = $flash;
  }

  public function paginator($id)
  {
    $itemsPerPage = 5;
    $currentPage = $id;
    $totalItems = $this->db->getAllCount('posts');
    $urlPattern = '/paginator/page/(:num)';

    $posts = $this->db->getAllPaginator('posts', $itemsPerPage, $currentPage);

    $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    
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

  public function users() 
  {
    $result = $this->db->getAll('users2');

    // $this->auth->admin()->addRoleForUserById(2, \Delight\Auth\Role::ADMIN);
    // d($this->auth->id());die;

    echo $this->templates->render('layout/users', 
      [
        'title' => 'Users',
        'users' => $result,
        'flash_output' => $this->flash->display(),
        'login_state' => $this->login_state(),
        'is_admin' => $this->auth->hasRole(\Delight\Auth\Role::ADMIN),
        'auth_id' => $this->auth->id(),
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
    // d($_POST);
    try {
      $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'] = NULL);

      echo 'We have signed up a new user with the ID ' . $userId;
      $this->flash->success('Вы успешно зарегестрировались.');
      Redirect::to("/");
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        // die('Invalid email address');
        $this->flash->error('Неверный email');
        Redirect::to("/registration_form");
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        // die('Invalid password');
        $this->flash->error('Неверный пароль');
        Redirect::to("/registration_form");
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
        // die('User already exists');
        $this->flash->error('Пользователь уже существует');
        Redirect::to("/registration_form");
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        // die('Too many requests');
        $this->flash->error('Слишком много запросов на рег');
        Redirect::to("/registration_form");
    }
  }

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
  
      echo 'User is logged in';
      $this->flash->success('<b>Поздравляю!</b> Вы успешно авторизировались.');
      Redirect::to("/users");
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      $this->flash->error('Не верный эл.адрес!');
      Redirect::to("/");
        // die('Wrong email address');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      $this->flash->error('Не верный пароль!');
      Redirect::to("/");
        // die('Wrong password');
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
      $this->flash->error('Эл.адрес не подтвержден!');
      Redirect::to("/");
        // die('Email not verified');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        die('Too many requests');
    }
  }

  public function logout() 
  {
    try {
      $this->auth->logOutEverywhere();
      $this->flash->success("Вы вышли из системы");
      Redirect::to("/");
    }
    catch (\Delight\Auth\NotLoggedInException $e) {
      $this->flash->error("Вы не ввошли в систему");
      Redirect::to("/");
      // die('Not logged in');
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


  public function show($id) 
  {
    $result = $this->db->getOneById('books', $id);

    echo $this->templates->render('page', 
      [
        'title' => $result['title'],
        'books' => $result,
      ]
    );
  }

  public function security_form($id)
  {
    // 'flash_output' => $this->flash->display(),
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

    try {
      // $userId = $auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
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
  
      $this->flash->success('We have signed up a new user with the ID ' . $userId);
      Redirect::to("/users");
      exit();
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      $this->flash->error('Не верный ввод email');
      Redirect::to("/create-user-form");
        // die('Invalid email address');
      exit();
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      $this->flash->error('Введите пароль');
      Redirect::to("/create-user-form");
        // die('Invalid password');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $this->flash->error('Пользователь с таким email уже существует! <br>Пожалуйста, ведите новый email');
      Redirect::to("/create-user-form");
      // die('User already exists');
    }


    if(!empty($userByEmail)) {     
      $this->flash->error('Пользователь с таким email существует! <br>Пожалуйста, ведите новый email');
      Redirect::to("/create-user-form");
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

  public function edit_user_form(int $id) 
  { 
    
    if($this->is_Admin()) {
      try {
        $userId = $this->auth->admin()->createUser("safas@asdfasd.ru", "1", NULL);
    
        echo 'We have signed up a new user with the ID ' . $userId;
      }
      catch (\Delight\Auth\InvalidEmailException $e) {
          die('Invalid email address');
      }
    } 
    
    echo "asldfjkslad";

    exit();

    $currenAuthUserId = $this->auth->id();
   
    if($currenAuthUserId === $id) 
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

  public function edit_user($id)
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

  public function user_delete($id)
  {
    $this->db->deleteById('users2', $id);

    $this->flash->success('Профиль успешно удален!');
    Redirect::to("/users");
    exit();
  }

}

