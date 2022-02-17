<html class="min-vh-100">
  <head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <title><?=$this->e($title);?></title>
  </head>
  <body class="pb-5 d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="/">My book store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/">HomePage</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/contacts">Contacts</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/registration/form">Registration</a>
            </li>
          </ul>
          <ul class="navbar-nav mx-5">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/login/form">Login</a>
            </li>
          </ul>
          
        </div>
      </div>
    </nav>
 
    <div class="wrapper flex-grow-1">
      <?=$this->section('content')?>
    </div>

    <footer class="footer bg-light">
      <div class="container-fluid">
        <div class="row">
            <div class="col my-3">
              <h2 class="text-center">Footer</h2>
            </div>
        </div>
      </div>
    </footer>
  </body>
</html>