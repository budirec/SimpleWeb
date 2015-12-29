<section class="row">
  <aside class="col-lg-2 col-md-12 col-sm-12">
    <article class="visible-lg-inline-block">
      <header><h4>Projects:</h4></header>
      <ul>
        <?php foreach ($projects as $value) : ?>
        <li>
          <a href="#" data-url="<?=base_url('project/view/'.$value->id);?>">
            <?= bootstrap3_progress_bar($value->name, $value->progress); ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </article>
  </aside>

  <section class="col-lg-7 col-md-12 col-sm-12" id="mainContent">
    <?php if(!empty($carausel)) : ?>
    <article id="main-carausel" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <?php $ct = count($carausel); ?>
        <?php for ($i = 0; $i < $ct; $i++) : ?>
          <li data-target="main-carausel" data-slide-to="<?= $i ?>"<?= ($i === 0 ? ' class="active"' : ''); ?>></li>
        <?php endfor; ?>
      </ol>

      <div class="carousel-inner" role="listbox">
        <?php for ($i = 0; $i < $ct; $i++) : ?>
          <div class="item<?= ($i === 0 ? ' active' : '') ?>">
            <img src="<?= base_url('public/img/' . $carausel[$i]->img) ?>" alt="<?= $carausel[$i]->header ?>"
                 class="center-block">
              <h3><?= $carausel[$i]->header ?></h3>
              <p><?= $carausel[$i]->description ?></p>
          </div>
        <?php endfor; ?>
      </div>

      <a class="left carousel-control" href="#main-carausel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#main-carausel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </article>
    <?php endif; ?>

    <article>
      <?= $welcome->content; ?>
    </article>
  </section>

  <aside class="col-lg-3 col-md-12 col-sm-12">
    <!-- News --------------------------------------------------------------------------------------------------------->
    <section class="well well-sm" id="indo-news">
      <header><h4>Berita Basileia Indonesia:</h4></header>
      <div class="news-container">
        <button type="button" id="indo-news-btn" data-news-id="0" 
                data-url="<?= base_url('news/index_id/id'); ?>" 
                class="btn btn-default btn-block">Load more</button>
      </div>
    </section>
    
    <section class="well well-sm" id="usa-news">
      <header><h4>Berita Basileia USA:</h4></header>
      <div class="news-container">
        <button type="button" id="usa-news-btn" data-news-id="0" 
                data-url="<?= base_url('news/index_id/en'); ?>" 
                class="btn btn-default btn-block">Load more</button>
      </div>
    </section>
    
    <script src="<?= base_url('public/js/home/news.js'); ?>"></script>
    <!-- News --------------------------------------------------------------------------------------------------------->
    
    <!-- Testimony ---------------------------------------------------------------------------------------------------->
    <section class="well well-sm">
      <header><h3>Testimoni:</h3></header>
      
      <form id="testimony-form" action="<?= base_url('testimony/add'); ?>">
        <div class="form-group">
          <textarea name="content" class="form-control" id="testimony-content" rows="3" 
                    placeholder="Tuliskan testimoni anda disini..."></textarea>
          <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </div>
      </form>
      
      <div id="testimony-container">
        <button type="button" id="testimony-btn" data-testimony-id="0" 
                data-url="<?= base_url('testimony/index'); ?>" 
                class="btn btn-default btn-block">Load more</button>
      </div>
      
      <script src="<?= base_url('public/js/home/testimony.js'); ?>"></script>
    </section>
    <!-- Testimony ---------------------------------------------------------------------------------------------------->

  </aside>
</section>