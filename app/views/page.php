<?php $this->layout('layout', ['title' => $title]) ?>

<?php

if(isset($_SESSION['flash_messages']) && !empty($_SESSION['flash_messages'])) {
  echo flash()->display();
}

;?>


<div class="container">
  <div class="row">
    <div class="col mt-3">
      <h1 class="text-center"><?=$this->e($title)?></h1>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit fuga non veritatis vel mollitia, cum dolore necessitatibus quis aliquam atque voluptas ex eaque rerum dolor ducimus illo impedit facere quibusdam.</p>
    </div>
  </div>
</div>