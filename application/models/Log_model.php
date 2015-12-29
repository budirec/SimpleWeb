<?php

class Log_model {

  // ==================================================================================================================

  private $path = [];

  // ==================================================================================================================

  public function __construct() {
    $this->path['info'] = APPPATH . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'info.log';
    $this->path['error'] = APPPATH . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'error.log';
  }

  // ==================================================================================================================

  public function write($type, $message) {echo $this->path[$type];
//    $myfile = fopen($this->path[$type], "w") or die($this->path[$type].' %% '.$type);
//    fwrite($myfile, date('Y-m-d H:i:s') . ': ' . $message . PHP_EOL);
//    fclose($myfile);
    error_log(date('Y-m-d H:i:s') . ': ' . $message . PHP_EOL, 3, $this->path[$type]);
  }

  // ==================================================================================================================
}
