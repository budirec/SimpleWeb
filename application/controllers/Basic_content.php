<?php

/**
 * TODO: Better CMS so no need for special layout
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Basic_content extends CI_Controller {

  private $_region;

  public function __construct() {
    parent::__construct();
    $this->load->model('basic_content_m');
    $this->_region = $this->session->userdata('region');
  }

  /**
   * Specialize layout for about page
   */
  public function about() {
    $data = $this->basic_content_m->get(NULL, 0, ['where' => ['name' => 'about_' . $this->_region]])[0];

    $this->output->set_content_type('text/plain');
    $this->load->view('home/about_' . $this->_region, ['about' => $data->content]);
  }

  /**
   * Specialize layout for about page
   */
  public function contact() {
    $this->output->set_content_type('text/plain');
    $this->load->view('home/contact_' . $this->_region);
  }

  //Administration use
  
  private function validate_data() {
    $ret = [];

    $this->load->library('form_validation');
    $this->form_validation->set_rules('content', 'Content', 'trim|required');

    if ($this->form_validation->run() === FALSE) {
      $ret[] = validation_errors();
    }

    return $ret;
  }

  public function update() {
    $this->output->set_content_type('json');

    $error = $this->validate_data();
    if ($error) {
      $this->output->set_output(json_encode(['error' => $error]));
      return FALSE;
    }

    $data = [
        'content' => trim($this->input->post('content'))
    ];

    if ($this->basic_content_m->update(['id' => $this->input->post('id')], $data) === FALSE) {
      $this->output->set_output(json_encode(['error' => 'Can\'t update data.']));
      return FALSE;
    }

    $this->output->set_output(json_encode(['success' => 1]));
    return TRUE;
  }

  /**
   * Based on DataTables API calls
   */
  public function adm() {
    $this->output->set_content_type('json');

    $result['recordsTotal'] = $this->basic_content_m->get_count();
    $result['recordsFiltered'] = $result['recordsTotal'];
    //Used so DataTables know which data arrived
    $result['draw'] = $this->input->get('draw');

    if (!empty($this->input->get('search')['value'])) {
      $search_value = $this->input->get('search')['value'];
      $param['like'] = [
          'name' => [$search_value, 'both'],
          'content' => [$search_value, 'both']
      ];
      $result['recordsFiltered'] = $this->basic_content_m->get_count($param);
    }
    $news = $this->basic_content_m->get($this->input->get('length'), $this->input->get('start'), $param);

    if ($result['recordsFiltered'] > 0) {
      foreach ($news as $value) {
        $result['data'][] = [
            'id' => $value->id,
            'name' => $value->name,
            'content' => (strlen($value->content) > 100 ? substr($value->content, 0, 100) . ' ...' : $value->content),
            'created' => date('M d, Y H:i:s', strtotime($value->created)),
            'modified' => date('M d, Y H:i:s', strtotime($value->modified)),
            //TODO: Offload it to js file instead
            'actions' => '<button type="button" class="btn btn-warning edit-btn" data-id="' . $value->id . '">'
            . 'Edit'
            . '</button>'
        ];
      }
    } else {
      $result['data'] = [];
    }

    $this->output->set_output(json_encode($result));
    return $result;
  }

  public function view($id) {
    $this->output->set_content_type('json');

    if ($id === NULL) {
      $this->output->set_output(json_encode(['error' => 'None selected.']));
      return FALSE;
    }

    $news = $this->basic_content_m->get(NULL, 0, ['where' => ['id' => $id]]);
    if ($news) {
      $this->output->set_output(json_encode($news[0]));
      return $news[0];
    }

    $this->output->set_output(json_encode(['error' => 'Content cannot be found.']));
    return FALSE;
  }

}
