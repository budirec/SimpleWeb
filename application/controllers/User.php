<?php
/**
 * TODO: Too many copy paste with other controller. Need to refactor like model(Basic_crud)
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('user_m');
  }

  //Administration use
  
  public function view($id = NULL) {
    $this->output->set_content_type('json');

    if ($id === NULL) {
      $this->output->set_output(json_encode(['error' => 'No user selected.']));
      return FALSE;
    }

    $news = $this->user_m->get(NULL, 0, ['where' => ['id' => $id]]);
    if ($news) {
      $this->output->set_output(json_encode($news[0]));
      return $news[0];
    }

    $this->output->set_output(json_encode(['error' => 'User cannot be found.']));
    return FALSE;
  }

  private function validate_data() {
    $ret = [];

    $this->load->library('form_validation');
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

    if ($this->form_validation->run() === FALSE) {
      $ret[] = validation_errors();
    }

    return $ret;
  }

  public function add() {
    $this->output->set_content_type('json');

    $error = $this->validate_data();
    if (!empty($error)) {
      $this->output->set_output(json_encode(['error' => serialize($error)]));
      return FALSE;
    }

    $data = [
        'name' => trim($this->input->post('name')),
        'email' => trim($this->input->post('email')),
        'adm' => ($this->input->post('is_admin') !== NULL ? 1 : 0)
    ];
    
    if ($this->input->post('id')) {
      if ($this->user_m->update(['id' => $this->input->post('id')], $data) === FALSE) {
        $this->output->set_output(json_encode(['error' => 'Can\'t update data.']));
        return FALSE;
      }
    } else {
      //Make random password
      $data['password'] = substr(md5(time() . rand(4, 546)), 0, 8);
      if ($this->user_m->insert($data) == FALSE) {
        $this->output->set_output(json_encode(['error' => 'Can\'t insert data.']));
        return FALSE;
      }
      $this->load->library('email');

      //Send email to user with their new password
      $this->email->from('info@basileia.org', 'Basileia USA');
      $this->email->to($data['email']);
      $this->email->subject('Account Created');
      //Use " so \n\r escaped
      $this->email->message("Your account for www.basileia.org has been created.\n\rYour password is: ".$data['password']);

      $this->email->send();
    }

    $this->output->set_output(json_encode(['success' => 1]));
    return TRUE;
  }

  public function delete() {
    $this->output->set_content_type('json');

    if ($this->user_m->delete(['id' => $this->input->post('id')])) {
      $this->output->set_output(json_encode(['success' => 1]));
      return TRUE;
    }

    $this->output->set_output(json_encode(['error' => 'Can\'t update database.']));
    return FALSE;
  }

  /**
   * Based on DataTables API calls
   */
  public function adm() {
    $this->output->set_content_type('json');

    $ret['recordsTotal'] = $this->user_m->get_count();
    $ret['recordsFiltered'] = $ret['recordsTotal'];
    //Used so DataTables know which data arrived
    $ret['draw'] = $this->input->get('draw');

    if (!empty($this->input->get('search')['value'])) {
      $search_value = $this->input->get('search')['value'];
      $param['like'] = [
          'email' => [$search_value, 'both'],
          'name' => [$search_value, 'both']
      ];
      $ret['recordsFiltered'] = $this->user_m->get_count($param);
    }
    $result = $this->user_m->get($this->input->get('length'), $this->input->get('start'), $param);

    if ($ret['recordsFiltered'] > 0) {
      foreach ($result as $value) {
        $ret['data'][] = [
            'id' => $value->id,
            'name' => $value->name.($value->adm ? '*' : ''),
            'email' => $value->email,
            'last_login' => date('M d, Y H:i:s', strtotime($value->last_login)),
            'created' => date('M d, Y H:i:s', strtotime($value->created)),
            'modified' => date('M d, Y H:i:s', strtotime($value->modified)),
            //TODO: Offload it to js file instead
            'actions' => '<button type="button" class="btn btn-warning edit-btn" data-id="' . $value->id . '">'
            . 'Edit'
            . '</button> '
            . '<button type="button" class="btn btn-danger delete-btn" data-id="' . $value->id . '">'
            . 'Delete'
            . '</button>'
        ];
      }
    } else {
      $ret['data'] = [];
    }

    $this->output->set_output(json_encode($ret));
    return $ret;
  }

}
