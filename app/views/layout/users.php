<?php $this->layout('templates/template', ['title' => $title, 'login_state' => $login_state]) ?>

<main id="js-page-content" role="main" class="page-content mt-3">

    <?= $flash_output ?>

    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-users'></i> Список пользователей
        </h1>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <!-- Add button -->
            <?php echo ($is_admin) ? '<a class="btn btn-success" href="/create-user-form">Добавить</a>' : '' ;?>

            <div class="border-faded bg-faded p-3 mb-g d-flex mt-3">
                <input type="text" id="js-filter-contacts" name="filter-contacts" class="form-control shadow-inset-2 form-control-lg" placeholder="Найти пользователя">
                <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="radio" name="contactview" id="grid" checked="" value="grid"><i class="fas fa-table"></i>
                    </label>
                    <label class="btn btn-default">
                        <input type="radio" name="contactview" id="table" value="table"><i class="fas fa-th-list"></i>
                     </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="js-contacts">
        <?php foreach($users as $user) :?>
                    
        <div class="col-xl-4" id="js-contacts">
            <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="<?=strtolower($user['username']) ?>">
                <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                    <div class="d-flex flex-row align-items-center">
                        <span class="status status-<?php if($user['status_user'] === 'online') {
                                                            echo "success";
                                                        } elseif($user['status_user'] === 'away') {
                                                            echo "warning";
                                                        } elseif($user['status_user'] === 'not_disturb') {
                                                            echo "danger";
                                                        } else {
                                                            echo "secondary";
                                                        }
                                                    ;?> mr-3">
                            <span class="rounded-circle profile-image d-block " style="background-image:url('../app/views/layout/img/demo/avatars/<?php echo !empty($user['image']) ? $user['image'] : 'no_image.png' ?>'); background-size: cover;"></span>
                        </span>
                        <div class="info-card-text flex-1">
  
                            <?php if( $is_admin or ($auth_id === (int)$user['id']) )  :?>
                                <a href="/page-profile/<?= $user['id'] ;?>" class="fs-xl text-truncate text-truncate-lg text-info">
                                    <?=$user['username'] ?>
                                </a>
                                <a href="#" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                    <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/edit-user-form/<?=$user['id']?>">
                                        <i class="fa fa-edit"></i>
                                        Редактировать</a>
                                    <a class="dropdown-item" href="/security-user/<?=$user['id']?>">
                                        <i class="fa fa-lock"></i>
                                        Безопасность</a>
                                    <a class="dropdown-item" href="/status-user/<?=$user['id']?>">
                                        <i class="fa fa-sun"></i>
                                        Установить статус</a>
                                    <a class="dropdown-item" href="/media-form/<?=$user['id']?>">
                                        <i class="fa fa-camera"></i>
                                        Загрузить аватар
                                    </a>
                                    <a href="/user-delete/<?=$user['id']?>" class="dropdown-item" onclick="return confirm('are you sure?');">
                                        <i class="fa fa-window-close"></i>
                                        Удалить
                                    </a>
                                </div>
                            <?php else: ?>
                                <!-- Name php -->
                                <a href="/page-profile/<?= $user['id'] ;?>" class="fs-xl text-truncate text-truncate-lg text-info">
                                    <?= $user['username'] ;?> 
                                </a>
                                            
                            <?php endif ;?>

                            <span class="text-truncate text-truncate-xl"><?=$user['position'] ?></span>
                        </div>
                        <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                            <span class="collapsed-hidden">+</span>
                            <span class="collapsed-reveal">-</span>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 collapse show">
                    <div class="p-3">
                        <a href="<?=$user['phone'] ?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                            <i class="fas fa-mobile-alt text-muted mr-2"></i><?=$user['phone'] ?></a>
                        <a href="mailto:<?=$user['email'] ?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                            <i class="fas fa-mouse-pointer text-muted mr-2"></i><?=$user['email'] ?></a>
                        <address class="fs-sm fw-400 mt-4 text-muted">
                            <i class="fas fa-map-pin mr-2"></i><?=$user['address'] ?></address>
                        <div class="d-flex flex-row">
                            <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:#4680C2">
                                <i class="fab fa-vk"></i>
                            </a>
                            <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:#38A1F3">
                                <i class="fab fa-telegram"></i>
                            </a>
                            <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:#E1306C">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php endforeach ;?>
    </div>
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
</main>

<footer class="page-footer" role="contentinfo">
    <div class="d-flex align-items-center flex-1 text-muted">
        <span class="hidden-md-down fw-700">2022 © Учебный проект</span>
    </div>
    <div>
        <ul class="list-table m-0">
            <li><a href="intel_introduction.html" class="text-secondary fw-700">Home</a></li>
            <li class="pl-3"><a href="info_app_licensing.html" class="text-secondary fw-700">About</a></li>
        </ul>
    </div>
</footer>