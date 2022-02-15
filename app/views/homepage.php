<?php $this->layout('layout', ['title' => 'HomePage']) ?>



<div class="container">
  <div class="row">
    <div class="col mt-3">
      <h1 class="text-center"><?=$this->e($title)?></h1>
    </div>
  </div>
  <div class="row">
    <div class="col mt-5">
      <a href="/add/book" class="btn btn-success">Add book</a>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Author</th>
            <th scope="col">Price</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($books as $book): ?>
            <tr>
              <td scope="row"><?= $book['id'] ;?></td>
              <td class="text-capitalize"><a href="/show/<?= $book['id'] ;?>"><?= $book['title'] ;?></a></td>
              <td class="text-capitalize"><?= $book['author'] ;?></td>
              <td class="fw-bold"><?= $book['price'] ;?></td>
              <td>
                <a href="/edit/book/<?= $book['id'] ;?>" class="btn btn-warning">Edit</a>
                <a href="/delete/book/<?= $book['id'] ;?>" class="btn btn-danger" onclick="return confirm('are you sure')">Delete</a>
              </td>
            </tr>
          <?php endforeach ;?>
        </tbody>
      </table>
    </div>
  </div>
</div>