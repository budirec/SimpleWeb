<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper('bootstrap3_helper');
  }

  public function index_en() {
    if ($this->session->userdata('uid')) {
      $this->load->model('user_m');
      $data['user'] = $this->user_m->get(NULL, 0, ['where' => ['id' => $this->session->userdata('uid')]])[0];
    }

    //Make sure that session region sets to en
    $this->session->set_userdata(['region' => 'en']);

    $data['lang'] = 'en';
    $data['title'] = 'Home';

    $this->load->model('basic_content_m');
    $data['welcome'] = $this->basic_content_m->get(NULL, 0, ['where' => ['name' => 'welcome']])[0];

    $this->load->model('slide_m');
    $data['carausel'] = $this->slide_m->get(NULL, 0, [
        'select' => 'img, header, description',
        'where' => ['region' => 'en', 'cat' => 1]
    ]);

    //TODO: Refactor after content finalized
    $this->load->model('project_m');
    $projects = $this->project_m->get(NULL, 0, ['fields' => 'id, name, progress', 'where' => ['region' => 'en']]);
    if ($projects) {
      $data['projects'] = $projects;
    }

    $this->load->view('home/inc/header', $data);
    $this->load->view('home/home_view', $data);
    $this->load->view('home/inc/footer');
  }

  public function index_id() {
    if ($this->session->userdata('uid')) {
      $this->load->model('user_m');
      $data['user'] = $this->user_m->get(NULL, 0, ['where' => ['id' => $this->session->userdata('uid')]])[0];
    }

    //Make sure that session region sets to id
    $this->session->set_userdata(['region' => 'id']);

    $data['lang'] = 'id';
    $data['title'] = 'Home';

    $this->load->model('basic_content_m');
    $data['welcome'] = $this->basic_content_m->get(NULL, 0, ['where' => ['name' => 'welcome_id']])[0];

    $this->load->model('slide_m');
    $data['carausel'] = $this->slide_m->get(NULL, 0, [
        'select' => 'img, header, description',
        'where' => ['region' => 'id', 'cat' => 1]
    ]);
    
    //TODO: Refactor after content finalized
    $this->load->model('project_m');
    $projects = $this->project_m->get(NULL, 0, ['fields' => 'id, name, progress', 'where' => ['region' => 'id']]);
    if ($projects) {
      $data['projects'] = $projects;
    }

    $this->load->view('home/inc/header', $data);
    $this->load->view('home/home_view_id', $data);
    $this->load->view('home/inc/footer');
  }

}
