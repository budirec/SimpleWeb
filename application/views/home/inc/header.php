<!DOCTYPE html>
<html lang="<?= $lang ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="<?= base_url('public/favicon.ico'); ?>">

    <title>TEST<?= (isset($title) ? ' - ' . $title : ''); ?></title>

    <link href="<?= base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('public/css/readable.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('public/css/base.css'); ?>" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= base_url('public/js/jquery.min.js'); ?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= base_url('public/js/bootstrap.min.js'); ?>"></script>
    <script>
      var lang = '<?= $lang; ?>';
      var baseUrl = '<?= base_url(); ?>';
    </script>
  </head>
  <body>
    <div class="container">
      <header>
        <nav class="navbar navbar-default navbar-static-top">
          <div class="navbar-header">
            <a class="navbar-brand" href="<?= base_url($lang); ?>" title="Home">
              <img src="<?= base_url('public/logo.png'); ?>" alt="BCF LOGO" style="display: inline">
              <span class="text-center">TEST</span>
            </a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" 
                    aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="hidden-lg"><a href="#" id="project-nav" data-url="<?= base_url('project'); ?>">Project</a></li>
              <li><a href="#" id="about-nav" data-url="<?= base_url('basic_content/about'); ?>">About</a></li>
              <li><a href="#" id="contact-nav" data-url="<?= base_url('basic_content/contact'); ?>">Contact</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right" id="menu-right">
              <li class="dropdown" id="user-nav">
                <?php if (isset($user)) : ?>
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?= $user->email; ?> <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                    <li>
                      <a href="#" data-toggle="modal" data-target="#password-modal">Change Password</a></li>
                    <li class="<?= ($user->adm == 1 ? '' : 'hidden'); ?>" id="to-adm">
                      <a href="<?= base_url('adm/' . $this->session->userdata('region')); ?>">Admin Page</a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?= base_url('api/logout/' . $lang); ?>">Logout</a></li>
                  </ul>
                <?php else : ?>
                  <a href="#" data-toggle="modal" data-target="#login-modal">
                    Login
                  </a>
                <?php endif; ?>
              </li>
              <li><a href="<?= base_url('en'); ?>"><img src="<?= base_url(); ?>public/img/usa.png" alt="USA Region"></a></li>
              <li><a href="<?= base_url('id'); ?>"><img src="<?= base_url(); ?>public/img/indo.jpg" alt="Indonesia Region"></a></li>
            </ul>
          </div>
        </nav>
        <script src="<?= base_url('public/js/home/home.js'); ?>"></script>
      </header>

      <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" 
                       aria-expanded="true" aria-controls="collapseOne">
                      Login
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body">
                    <form id="login-form" action="<?= base_url('api/login'); ?>">
                      <div class="modal-body">
                        <div id="login-message"></div>
                        <div class="form-group">
                          <label for="login-email">Email</label>
                          <input type="email" name="email" class="form-control" id="login-email" placeholder="Email">
                        </div>
                        <div class="form-group">
                          <label for="login-password">Password</label>
                          <input type="password" name="password" class="form-control" id="login-password" placeholder="Password">
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
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                  <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" 
                       href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Register
                    </a>
                  </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                  <div class="panel-body">
                    <form id="register-form" action="<?= base_url('api/register'); ?>">
                      <div class="modal-body">
                        <div id="register-message"></div>
                        <?= bootstrap3_input('text', 'name', 'Name', 'register-name'); ?>
                        <?= bootstrap3_input('email', 'email', 'Email', 'register-name'); ?>
                        <?= bootstrap3_input('password', 'password', 'Password', 'register-password'); ?>
                        <?= bootstrap3_input('password', 'password1', 'Confirm Password', 'register-password1'); ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <script src="<?= base_url('public/js/home/login.js'); ?>"></script>
          </div>
        </div>
      </div>

      <div class="modal fade" id="password-modal" tabindex="-1" role="dialog" aria-labelledby="Change Password">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <form id="password-form" action="<?= base_url('api/changePassword'); ?>">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Change Password</h4>
              </div>
              <div class="modal-body">
                <div id="password-message"></div>
                <div class="form-group">
                  <label for="old-password">Old Password</label>
                  <input type="password" name="old_password" class="form-control" id="old-password" placeholder="Old Password">
                </div>
                <div class="form-group">
                  <label for="new-password">New Password</label>
                  <input type="password" name="new_password" class="form-control" id="new-password" placeholder="New Password">
                </div>
                <div class="form-group">
                  <label for="confirm-password">Confirm Password</label>
                  <input type="password" name="new_password1" class="form-control" id="confirm-password" placeholder="Confirm Password">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
            <script src="<?= base_url('public/js/home/password.js'); ?>"></script>
          </div>
        </div>
      </div>
