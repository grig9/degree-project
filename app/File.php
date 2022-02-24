<?php

namespace App;


class File 
{
  public static function save()
  {
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

    $file->upload();

    return $data['name'];
  }
}