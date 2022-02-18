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

    echo $this->templates->render('layout/users', 
      [
        'title' => 'Users',
        'users' => $result,
        'flash_output' => $this->flash->display(),

      ]
    );
  }


  public function index() 
  {
    $result = $this->db->getAll('books');
    echo $this->templates->render('homepage', 
      [
        'title' => 'This is a book store',
        'books' => $result,
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
      $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'] = NULL, function ($selector, $token) {
          echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
      });

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
    try {
      $this->auth->login($_POST['email'], $_POST['password']);
  
      echo 'User is logged in';
      Redirect::to("/users");
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        die('Wrong email address');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        die('Wrong password');
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
        die('Email not verified');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        die('Too many requests');
    }
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
      ]
    );
  }

  public function create_user_form() 
  {
    echo $this->templates->render('layout/create_user_form', 
      [
        'title' => 'Create new user',
        'flash_output' => $this->flash->display(),
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

    $user = $this->db->getOneById('users2', 91);

    $userByEmail = $this->db->getOneByEmail('users2', $email);


    if(!empty($userByEmail)) {     
      $this->flash->error('Пользователь с таким email существует! <br>Пожалуйста, ведите новый email');
      Redirect::to("/create-user-form");
    } 

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
    
    Redirect::to("/users");
    exit();
  }

  public function edit_user_form($id) 
  {
    $result = $this->db->getOneById('users2', $id);

    echo $this->templates->render('layout/edit_user_form', 
      [
        'title' => 'Edit user',
        'user' => $result,
      ]
    );
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

