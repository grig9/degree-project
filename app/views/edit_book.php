<?php $this->layout('layout', ['title' => $title]) ?>

<div class="container">
  <div class="row">
    <div class="col mt-3">
      <h1 class="text-center"><?=$this->e($title)?></h1>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col-8 mx-auto">
      <form action="/update/book/<?=$this->e($book['id'])?>" method="post">
        <div class="row mb-3">
          <label for="title" class="col-sm-2 col-form-label">Title</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="title" name="title" value="<?=$this->e($book['title'])?>">
          </div>
        </div>
        <div class="row mb-3">
          <label for="author" class="col-sm-2 col-form-label">Author</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="auhtor" name="author" value="<?=$this->e($book['author'])?>">
          </div>
        </div>
        <div class="row mb-3">
          <label for="price" class="col-sm-2 col-form-label">Price</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="auhtor" name="price" value="<?=$this->e($book['price'])?>">
          </div>
        </div>
        <button type="submit" class="px-5 btn btn-success float-end">Update</button>
      </form>
    </div>
  </div>
</div>