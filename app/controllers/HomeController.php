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
use App\controllers\File;

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
        'is_admin' => $this->auth->hasRole(\Delight\Auth\Role::ADMIN),
        'auth_id' => $this->auth->id(),
        'paginator' => $paginator,
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
    ['status_site' => $_POST['status']],
    $_POST['id']);
    
    $this->flash->success('Вы успешно обновили статус');
    Redirect::to("/users/1");
  }

  public function security_form(int $id)
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
      ]
    );
  }

  public function security()
  {

    $this->auth->reconfirmPassword($_POST['password']);

    try {
      if ($this->auth->reconfirmPassword($_POST['password'])) {
        $this->auth->changeEmail($_POST['newEmail'], function ($selector, $token) {
            echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email to the *new* address)';
            echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
            echo '  For SMS, consider using a third-party service and a compatible SDK';
            echo "<br>";
            echo "<a href='https://localhost/verification' . \urlencode($selector) . '&token=' . \urlencode($token)'></a>";
        });

        echo 'The change will take effect as soon as the new email address has been confirmed';
      }
      else {
          echo 'We can\'t say if the user is who they claim to be';
      }
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        die('Invalid email address');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
        die('Email address already exists');
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
        die('Account not verified');
    }
    catch (\Delight\Auth\NotLoggedInException $e) {
        die('Not logged in');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        die('Too many requests');
    }
  }

  public function verification() 
  {
    try {
      $this->auth->confirmEmail('vBiXFsQ7QY4NfjFm', 'KaylB5vx_kbpp7Pi');

      $this->flash('Email address has been verified');
      Redirect::to("/users/1");
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

  public function create_user_form() 
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
    $position = $_POST['position'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $status_site = $_POST['status_site'];
    $vk = $_POST['vk'];
    $telegram = $_POST['telegram'];
    $instagram = $_POST['instagram'];

    try {
      $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);

      $image = File::save();

      $this->db->insert('users', [ 
        'position' => $position,
        'phone' => $phone,
        'address' => $address,
        'status_site' => $status_site,
        'image' => $image,
        'vk' => $vk,
        'telegram' => $telegram,
        'instagram' => $instagram
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
      $this->flash->error('Пользователь с таким email уже существует! <br>Пожалуйста, ведите другой email');
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
    exit;
  }

  public function user_delete($id)
  {

    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
      exit;
    }

    try {
      $this->auth->admin()->deleteUserById($id);
      $this->flash->warning('Профиль успешно удален!');
      Redirect::to("/users/1");
    }
    catch (\Delight\Auth\UnknownIdException $e) {
      $this->flash->error('Пользователя не существует');
      Redirect::to("/users/1");
      exit;
    }

  }

  // examples extenstions
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

}

