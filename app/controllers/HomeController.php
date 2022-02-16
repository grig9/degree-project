<?php

namespace App\controllers;

use App\exceptions\NotEnoughMoneyException;
use App\exceptions\AccountIsBlockException;
use League\Plates\Engine;
use App\database\Connection;
use Delight\Auth\Auth;
use Faker\Factory;
use JasonGrimes\Paginator;

class HomeController 
{
  private $config; 
  private $db;
  private $templates;
  private $auth;

  public function __construct() 
  {
    $this->config = include __DIR__ . "/../database/config.php";
    $this->db = include __DIR__ . "/../database/start.php";
    $this->templates = new Engine('../app/views');
    $this->auth = new Auth(Connection::make($this->config['database']));
  }


  public function paginator($params)
  {
    $posts = $this->db->getAllPaginator('posts', 5, $params['id']);
    // $posts = $this->db->getAll('posts');
    
    // d($posts);die;
    $totalItems = 100;
    $itemsPerPage = 5;
    $currentPage = $params['id'];
    $urlPattern = '/paginator/page/(:num)';

    $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    
    // $paginator->getPages();
    // d($paginator);die;
    echo $this->templates->render('page', 
    [
      'title' => 'Paginator',
      'paginator' => $paginator
    ]
  );
  }

  public function fake_posts()
  {
    // $faker = Factory::create();

    // for ($i = 0; $i < 30; $i++) {
    //   $this->db->insert('posts',
    //     [
    //       'title' => $faker->word(3, true),
    //       'content' => $faker->text(),
    //     ]
    //   );
    // }
  }

  public function users() 
  {
    $result = $this->db->getAll('users2');

    echo $this->templates->render('test/users', 
      [
        'title' => 'Users',
        'users' => $result,
      ]
    );
  }

  
  public function index($vars) 
  {
    $result = $this->db->getAll('books');
    echo $this->templates->render('homepage', 
      [
        'title' => 'This is a book store',
        'books' => $result,
      ]
    );
  }

  public function registration() 
  {
    try {
      $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
          echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
      });
  
      echo 'We have signed up a new user with the ID ' . $userId;
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        die('Invalid email address');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        die('Invalid password');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
        die('User already exists');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        die('Too many requests');
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
    echo $this->templates->render('form', 
      [
        'title' => 'Login',
        'button' => 'Login',
        'action' => '/login'
      ]
    );
  }

  public function login() 
  {
    try {
      $this->auth->login($_POST['email'], $_POST['password']);
  
      echo 'User is logged in';
      header("Location: /");
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

  public function registration_form() 
  {
    echo $this->templates->render('form', 
      [
        'title' => 'Registration form',
        'button' => 'Registration',
        'action' => '/registration'
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

  public function contacts() 
  {
    echo $this->templates->render('page', 
      [
        'title' => 'Contacts',
      ]
    );
  }

  public function show($params) 
  {
    $id = $params['id'];

    $result = $this->db->getOneById('books', $id);

    echo $this->templates->render('page', 
      [
        'title' => $result['title'],
      ]
    );
  }

  public function add_book() 
  {
    echo $this->templates->render('add_book', 
      [
        'title' => 'Add book',
      ]
    );
  }

  public function create() 
  {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    $this->db->insert('books', [ 
      'title' => $title,
      'author' => $author,
      'price' => $price
     ]);
    header('Location: /');
    exit();
  }

  public function edit_book($params) 
  {
    $id = $params['id'];

    $result = $this->db->getOneById('books', $id);

    echo $this->templates->render('edit_book', 
      [
        'title' => 'Edit book',
        'book' => $result,
      ]
    );
  }

  public function update_by_id($params)
  {
    $id = $params['id'];
 
    $data = [ 
      'title' => $_POST['title'],
      'author' => $_POST['author'],
      'price' => $_POST['price']
    ];
  
    $this->db->updateById('books', $data, $id);
  
    header('Location: /');
    exit();
  }

  public function delete_by_id($params)
  {
    $id = $params['id'];

    $this->db->deleteById('books', $id);
    header('Location: /');
    exit();
  }

}

