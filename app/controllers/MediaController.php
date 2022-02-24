<?php

namespace App\controllers;

use App\controllers\Controller;
use App\File;
use App\Redirect;

class MediaController extends Controller
{
  public function media_form(int $id) 
  {
    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
      exit;
    }

    $user = $this->db->getOneById('users', $id);

    echo $this->templates->render('layout/media', 
      [
        'title' => 'Media',
        'flash_output' => $this->flash->display(),
        'login_state' => $this->auth->isLoggedIn(),
        'user' => $user,
      ]
    );
  }


  public function download_image() 
  {
    $id = $_POST['id'];

    $user = $this->db->getOneById('users', $id);

    unlink('../app/views/layout/img/demo/avatars/' . $user['image']);

    $new_filename = File::save();
    
    $this->db->updateById('users', 
      [
        'image' => $new_filename
      ], 
      
      $id);

    $this->flash->success('Вы успешно загрузили изобаржение');
    Redirect::to("/media-form/$id");
  }

}