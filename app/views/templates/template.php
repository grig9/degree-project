<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
      <?=$this->e($title);?>
    </title>
    <meta name="description" content="<?=$this->e($title);?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="../app/views/layout/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="../app/views/layout/css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="../app/views/layout/css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="../app/views/layout/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="../app/views/layout/css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="../app/views/layout/css/fa-regular.css">
</head>
<body class="mod-bg-1 mod-nav-link">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">  
      <a class="navbar-brand d-flex align-items-center fw-500" href="/users"><img alt="logo" class="d-inline-block align-top mr-2" src="../app/views/layout/img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/users">Главная <span class="sr-only">(current)</span></a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <?php if (!$login_state) :?>
            <li class="nav-item">
              <a class="nav-link" href="/">Войти</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="/logout">Выйти</a>
            </li>
          <?php endif ;?>
        </ul>
      </div>
    </nav>
    <!-- main -->
    <?=$this->section('content')?>
    <!-- main end -->
    <nav aria-label="Page paginator">
      <ul class="pagination justify-content-center">
          <?php if ($paginator->getPrevUrl()): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo $paginator->getPrevUrl(); ?>">&laquo; Previous</a>
              </li>
          <?php endif; ?>

          <?php foreach ($paginator->getPages() as $page): ?>
              <?php if ($page['url']): ?>
                  <li class="page-item <?php echo $page['isCurrent'] ? 'active' : ''; ?>">
                      <a class="page-link" href="<?php echo $page['url']; ?>"><?php echo $page['num']; ?></a>
                  </li>
              <?php else: ?>
                  <li class="disabled"><span><?php echo $page['num']; ?></span></li>
              <?php endif; ?>
          <?php endforeach; ?>

          <?php if ($paginator->getNextUrl()): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo $paginator->getNextUrl(); ?>">Next &raquo;</a>
              </li>
          <?php endif; ?>
      </ul>
    </nav>
</body>

  <script src="app/views/layout/js/vendors.bundle.js"></script>
    <script src="app/views/layout/js/app.bundle.js"></script>
    <script>
      
        $(document).ready(function()
        {
          $('input[type=radio][name=contactview]').change(function()
            {
              if (this.value == 'grid')
              {
                $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                $('#js-contacts .js-expand-btn').addClass('d-none');
                $('#js-contacts .card-body + .card-body').addClass('show');

              }
              else if (this.value == 'table')
              {
                $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                $('#js-contacts .js-expand-btn').removeClass('d-none');
                $('#js-contacts .card-body + .card-body').removeClass('show');
              }

            });

            //initialize filter
            initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });

    </script>


</html>