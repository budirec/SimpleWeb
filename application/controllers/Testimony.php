<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Testimony extends CI_Controller {

  private $_region;
  
  public function __construct() {
    parent::__construct();
    $this->load->model('testimony_m');
    $this->_region = $this->session->userdata('region');
  }

  public function index($id = NULL) {
    $this->output->set_content_type('json');

    $param['where']['t.region'] = $this->_region;
    if ($id) {
      $param['where']['t.id <'] = $id;
    }

    $testimonies = $this->testimony_m->get(NULL, 0, $param);
    if (count($testimonies) > 0) {
      foreach ($testimonies as $value) {
        $result['testimonies'][] = [
            'id' => $value->id,
            'name' => $value->name,
            'content' => $value->content,
            'created' => $value->created
        ];
      }
    } else {
      $result['error'] = 'No more testimony.';
    }

    $this->output->set_output(json_encode([$result]));
    return $result;
  }

  public function add() {
    $this->output->set_content_type('applicatoin/json');

    $this->load->helper('form');
    $this->load->library('form_validation');

    $this->form_validation->set_rules('content', 'Your testimony', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->output->set_output(json_encode(['error' => 'Your testimony is empty.']));
    } else {
      $temp = $this->testimony_m->insert([
          //Default to user Anonymouse if not logged in
          'user_id' => ($this->session->userdata('uid') ? $this->session->userdata('uid') : 1),
          'region' => $this->_region,
          'content' => $this->input->post('content')
      ]);
      if ($temp !== FALSE) {
        $this->output->set_output(json_encode($this->testimony_m->get(NULL, 0, ['where' => ['t.id' => $temp]])));
        
        return TRUE;
      } else {
        $this->output->set_output(json_encode(['error' => 'Can\'t update database.']));
      }
    }

    return FALSE;
  }

}
