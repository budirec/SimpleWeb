<article id="main-carausel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <?php $ct = count($carausel); ?>
    <?php for ($i = 0; $i < $ct; $i++) : ?>
      <li data-target="main-carausel" data-slide-to="<?= $i ?>"<?= ($i === 0 ? ' class="active"' : ''); ?>></li>
    <?php endfor; ?>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <?php for ($i = 0; $i < $ct; $i++) : ?>
      <div class="item<?= ($i === 0 ? ' active' : '') ?>">
        <img src="<?= $carausel[$i]->img ?>" alt="<?= $carausel[$i]->header ?>">
        <div class="carousel-caption">
          <h3><?= $carausel[$i]->header ?></h3>
          <p><?= $carausel[$i]->description ?></p>
        </div>
      </div>
    <?php endfor; ?>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#main-carausel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#main-carausel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</article>

<article>
  <?= $content; ?>
</article>