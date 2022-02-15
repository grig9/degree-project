<?php $this->layout('layout', ['title' => $title]) ?>

<div class="container">
  <div class="row">
    <div class="col mt-3">
      <h1 class="text-center"><?=$this->e($title)?></h1>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col-8 mx-auto">
      <form action="<?=$this->e($action)?>" method="post">
        <div class="row mb-3">
          <label for="username" class="col-sm-2 col-form-label">User name</label>
          <div class="col-sm-10">
            <input type="username" class="form-control" id="username" name="username">
          </div>
        </div>
        <div class="row mb-3">
          <label for="email" class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email">
          </div>
        </div>
        <div class="row mb-3">
          <label for="password" class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-10">
            <input type="password" class="form-control" id="password" name="password">
          </div>
        </div>
        <button type="submit" class="px-5 btn btn-success float-end"><?=$this->e($button)?></button>
      </form>
    </div>
  </div>
</div>