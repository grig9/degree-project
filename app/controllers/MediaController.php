<?php

namespace App\controllers;

use App\controllers\Controller;

class MediaController extends Controller
{
  public function media_form(int $id) 
  {
    if(!$this->is_Admin() and $this->auth->id() !== $id) {
      $this->flash->error('Вы не можете редактировать других пользователей');
      Redirect::to("/users/1");
      exit;
    }

    $user = $this->db->getOneById('users2', $id);

    echo $this->templates->render('layout/media', 
      [
        'title' => 'Media',
        'flash_output' => $this->flash->display(),
        'login_state' => $this->auth->isLoggedIn(),
        'user' => $user,
      ]
    );
  }

  public function image($id) 
  {
    if( $_FILES['image']['error'] === 0 ) {
      $storage = new \Upload\Storage\FileSystem('../app/views/layout/img/demo/avatars/');
      $file = new \Upload\File('image', $storage);

      // Optionally you can rename the file on upload
      $new_filename = uniqid();
      $file->setName($new_filename);

      $data = array(
        'name'       => $file->getNameWithExtension(),
        'extension'  => $file->getExtension(),
        'mime'       => $file->getMimetype(),
        'size'       => $file->getSize(),
        'md5'        => $file->getMd5(),
        'dimensions' => $file->getDimensions()
      );
      try {
        // Success!
        $file->upload();

        // update image_name in table
        $this->db->updateById('users2', [
          'image_name' => $data['name']
        ], $id);

        $this->flash->success('Вы успешно загрузили изображение');
        Redirect::to("/media-form/$id");
        exit;
        
      } catch (\Exception $e) {
        // Fail!
        $errors = $file->getErrors();
      }
 
    } else {
      $this->flash->error('Выберите изображение');
      Redirect::to("/media-form/$id");
      exit;
    }

  }
}