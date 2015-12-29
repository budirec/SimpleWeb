<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  /**
   * Redirect on those that aren't logged yet
   */
  private function _require_login() {
    if (!$this->session->userdata('uid')) {
      redirect(base_url($this->session->userdata('region')));
    }
  }

  /**
   * Send JSON encoded object 
   * {success: 0|1 [, is_adm: 0|adm link, email: logged email, logout: link to logout]}
   * 
   * @return boolean 
   */
  public function login() {
    $this->load->model('user_m');
    $this->output->set_content_type('json');

    $email = $this->input->post('email');
    $password = $this->input->post('password');

    $result = $this->user_m->is_valid_user($email, $password);

    if ($result) {
      $this->user_m->update(['id' => $result->id], ['last_login' => date('Y-m-d')]);
      $this->session->set_userdata(['uid' => $result->id]);
      if ($result->adm) {
        $this->session->set_userdata(['adm' => TRUE]);
      }
      $this->output->set_output(json_encode([
          'success' => 1,
          'is_adm' => ($result->adm ? base_url('adm/' . $this->session->userdata('region')) : 0),
          'email' => $result->email,
          'logout' => base_url('api/logout')
      ]));
      return TRUE;
    }

    $this->output->set_output(json_encode(['success' => 0]));
    return FALSE;
  }

  /**
   * Send JSON encoded object 
   * {success: 0 [, error: error message]|1 [, is_adm: 0|adm link, email: logged email, logout: link to logout]}
   * 
   * @return boolean 
   */
  public function register() {
    $this->load->library('form_validation');
    $this->output->set_content_type('json');

    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('password1', 'Confirm Password', 'required|matches[password]');

    if ($this->form_validation->run() === FALSE) {
      $this->output->set_output(json_encode(['success' => 0, 'error' => validation_errors()]));
      return FALSE;
    }

    $this->load->model('user_m');
    $result = $this->user_m->insert([
        'name' => trim($this->input->post('name')),
        'email' => trim($this->input->post('email')),
        'password' => $this->input->post('password')
    ]);

    if ($result) {
      //Success, Logged the user in
      $this->session->set_userdata(['uid' => $result]);
      $this->output->set_output(json_encode([
          'success' => 1,
          'is_adm' => 0,
          'email' => trim($this->input->post('email')),
          'logout' => base_url('api/logout')
      ]));
      
      return TRUE;
    }

    $this->output->set_output(json_encode([
        'success' => 0,
        'error' => 'Sorry, we have difficulty saving your information.'
    ]));
    
    return FALSE;
  }

  /**
   * Only logged user can change password as we need uid from session
   * Send JSON encoded object 
   * {success: 1} or {error: error message}
   * 
   * @return boolean
   */
  public function changePassword() {
    $this->_require_login();

    $this->load->library('form_validation');
    $this->output->set_content_type('json');

    $this->form_validation->set_rules('old_password', 'Old Password', 'required');
    $this->form_validation->set_rules('new_password', 'New Password', 'required');
    $this->form_validation->set_rules('new_password1', 'Confirm Password', 'required|matches[new_password]');

    if ($this->form_validation->run() !== FALSE) {
      $uid = $this->session->userdata('uid');
      $this->load->model('user_m');
      //Get the user so we can veryfy the old password
      $user = $this->user_m->get(NULL, 0, ['fields' => 'password', 'where' => ['id' => $uid]])[0];
      if (!password_verify($this->input->post('old_password'), $user->password)) {
        $this->output->set_output(json_encode(['error' => '<p>Old password is incorrect.</p>']));
      } elseif ($this->user_m->update(['id' => $uid], ['password' => $this->input->post('new_password')])) {
        $this->output->set_output(json_encode(['success' => 1]));

        return TRUE;
      } else {
        $this->output->set_output(json_encode(['error' => 'Can\'t update database.']));
      }
    } else {
      $this->output->set_output(json_encode(['error' => validation_errors()]));
    }

    return FALSE;
  }

  public function logout() {
    $region = $this->session->userdata('region');
    $this->session->sess_destroy();
    redirect(base_url($region));
  }

  public function reset_password() {
    echo 'not ready yet';

    $this->load->library('email');

    $this->email->from('info@basileia.org', 'Basileia Christian Fellowship');
    $this->email->to($this->input->post('email'));

    $this->email->subject('Reset Password');
    $this->email->message('Follow this link to reset password<br>');

    $this->email->send();
  }

}
