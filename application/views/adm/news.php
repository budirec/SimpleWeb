
<div class="row">
  <div class="col-lg-10 col-lg-offset-1">
    <div class="row">
      <div class="col-lg-12">
        <button class="btn btn-primary" id="new-btn" data-toggle="modal" data-target="#add-modal">New</button>
      </div>
    </div>
    <br><br>
    <div class="table-responsive">
      <table class="table table-striped table-condensed" id="browse" 
             data-url="<?= base_url('news/adm/' . $this->session->userdata('region')); ?>" style="width: 100%">
        <thead>
          <tr>
            <th>Header</th>
            <th>Image</th>
            <th>Content</th>
            <th>Created</th>
            <th>Modified</th>
            <th>Action</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Header</th>
            <th>Image</th>
            <th>Content</th>
            <th>Created</th>
            <th>Modified</th>
            <th>Action</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="Add News">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?= form_open_multipart(base_url('news/add'), ['id' => 'add-form']);?>
        <input type="hidden" name="id" id="add-id">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="add-label">Add News</h4>
        </div>
        <div class="modal-body">
          <div id="add-message"></div>
          <?= bootstrap3_input('text', 'header', 'Title', 'add-header'); ?>
          <?= bootstrap3_input('file', 'img', 'Image', 'add-img'); ?>
          <?= bootstrap3_input('textarea', 'content', 'Content', 'add-content'); ?>
          <?= bootstrap3_input('textarea', 'translated', 'Translation', 'add-translated'); ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="news-modal" tabindex="-1" role="dialog" aria-labelledby="News Preview">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('public/js/adm/news.js'); ?>"></script>