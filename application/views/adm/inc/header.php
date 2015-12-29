<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title>TEST<?= (isset($title) ? ' - ' . $title : ''); ?></title>

  <link href="<?= base_url(); ?>public/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url(); ?>public/css/readable.css" rel="stylesheet">
  <link href="<?= base_url(); ?>public/css/base.css" rel="stylesheet">

  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="<?= base_url('public/js/jquery.min.js'); ?>"></script>
  
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="<?= base_url('public/js/bootstrap.min.js'); ?>"></script>
  
  <!-- DataTables -->
  <link href="<?= base_url('public/css/dataTables.bootstrap.min.css'); ?>" rel="stylesheet">
  <script src="<?= base_url('public/js/dataTables.min.js'); ?>"></script>
  <script src="<?= base_url('public/js/dataTables.bootstrap.min.js'); ?>"></script>
  
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
            <li><a href="<?= base_url('adm/basic_content'); ?>" id="content-nav">Content</a></li>
            <li><a href="<?= base_url('adm/user'); ?>" id="user-nav">User</a></li>
            <li><a href="<?= base_url('adm/news'); ?>" id="news-nav">News</a></li>
            <li><a href="<?= base_url('adm/project'); ?>" id="project-nav">Project</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?= base_url('api/logout'); ?>" class="btn btn-default">Logout</a></li>
            <li><a href="<?= base_url('adm/en'); ?>"><img src="<?= base_url('public/img/usa.png'); ?>" alt="USA Region"></a></li>
            <li><a href="<?= base_url('adm/id'); ?>"><img src="<?= base_url('public/img/indo.jpg'); ?>" alt="Indonesia Region"></a></li>
          </ul>
        </div>
      </nav>
    </header>
