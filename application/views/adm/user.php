
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
             data-url="<?= base_url('user/adm'); ?>" style="width: 100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Last Login</th>
            <th>Created</th>
            <th>Modified</th>
            <th>Action</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Last Login</th>
            <th>Created</th>
            <th>Modified</th>
            <th>Action</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="Add User">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?= form_open(base_url('user/add'), ['id' => 'add-form']); ?>
      <input type="hidden" name="id" id="add-id">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="add-label">Add User</h4>
      </div>
      <div class="modal-body">
        <div id="add-message"></div>
        <?= bootstrap3_input('text', 'name', 'Name', 'add-name'); ?>
        <?= bootstrap3_input('email', 'email', 'Email', 'add-email'); ?>
        <div class="checkbox">
          <label><input type="checkbox" name="is_admin" value="1" id="add-admin"> Is admin?</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="<?= base_url('public/js/adm/user.js'); ?>"></script>