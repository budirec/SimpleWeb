<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Adm extends CI_Controller {

  public function __construct() {
    parent::__construct();
    if (!$this->session->userdata('adm')) {
      redirect(base_url($this->session->userdata('region')));
    }
  }

  public function index_en() {
    $this->session->set_userdata(['region' => 'en']);
    $data['lang'] = 'en';

    $this->load->view('adm/inc/header', $data);
    $this->load->view('adm/adm_view');
    $this->load->view('home/inc/footer');
  }
  public function index_id() {
    $this->session->set_userdata(['region' => 'id']);
    $data['lang'] = 'id';

    $this->load->view('adm/inc/header', $data);
    $this->load->view('adm/adm_view');
    $this->load->view('home/inc/footer');
  }
  
  public function basic_content() {
    $this->load->helper('bootstrap3_helper');
    $this->load->view('adm/basic_content');
  }
  public function user() {
    $this->load->helper('bootstrap3_helper');
    $this->load->view('adm/user');
  }
  public function news() {
    $this->load->helper('bootstrap3_helper');
    $this->load->view('adm/news');
  }
  public function project() {
    $this->load->helper('bootstrap3_helper');
    $this->load->view('adm/project');
  }

}
