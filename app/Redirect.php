<?php

namespace App;

class Redirect 
{
  public static function to($path)
  {
    header("Location: $path");
    exit;
  }
}