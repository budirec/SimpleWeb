<?php
/**
 * TODO: FIX when content is defined
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

  private $region;

  public function __construct() {
    parent::__construct();
    $this->load->model('project_m');
    $this->region = $this->session->userdata('region');
  }

  public function index() {
    $this->output->set_content_type('json');
    $data = $this->project_m->get(NULL, 0, ['fields' => 'id, name, progress', 'where' => ['region' => $this->region]]);
    if($data){
      $this->output->set_output(json_encode(['projects' => $data]));
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function view($id) {
    $this->output->set_content_type('json');
    $data = $this->project_m->get(NULL, 0, ['where' => ['id' => $id]])[0];
    if($data){
      $this->output->set_output(json_encode($data));
      
      return TRUE;
    }
    
    return FALSE;
  }

}
