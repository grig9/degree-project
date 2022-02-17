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
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ab ullam numquam id! Tempore itaque dolores consequuntur eaque error dolorem autem accusantium unde, qui, deleniti, fugiat dolorum. Enim, repudiandae corrupti.</p>
    </div>
  </div>
</div>