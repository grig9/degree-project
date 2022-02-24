<?php

namespace App\controllers;

class Redirect 
{
  public static function to($path)
  {
    header("Location: $path");
    exit;
  }
}