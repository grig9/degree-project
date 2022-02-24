<?php $this->layout('templates/template', ['title' => $title, 'login_state' => $login_state]) ?>

<?php 
    $list_status = ['online' => 'Онлайн', 'away' => 'Отошел', 'not_disturb' => 'Не беспокоить']
;?>

<main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-sun'></i> Установить статус
            </h1>
        </div>
        <form action="/set-user-status" method="POST">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Установка текущего статуса</h2>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- status -->
                                        <div class="form-group">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <label class="form-label" for="example-select">Выберите статус</label>
                                            <select class="form-control" id="example-select" name="status_user">
                                            <!-- status options -->
                                                <?php foreach($list_status as $status_key => $status_value): ?>
                                                    <option value="<?= $status_key ;?>" 
                                                        <?php
                                                            if ( $user['status_user'] === $status_key) {
                                                                echo 'selected';
                                                            } 
                                                        ;?>
                                                    >
                                                    <?= $status_value ;?>
                                                    </option>
                                                <?php endforeach ;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                        <button class="btn btn-warning">Set Status</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
</main>