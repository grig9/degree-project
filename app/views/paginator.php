<?php $this->layout('layout', ['title' => $title]) ?>

<div class="container">
  <div class="row">
    <div class="col">
      <h1 class="text-center"><?=$this->e($title)?></h1>
    </div>
  </div>
  <?php foreach($posts as $post) : ?>
    <div class="row">
      <div class="col">
        <h3 class="text-center text-capitalize">
          <?= $post['title'] ?>
        </h3>
        <p>
          <?= $post['content'] ?>
        </p>
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