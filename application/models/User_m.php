<?php

/**
 * User model extending base_crud model
 * 
 * Overwrite parent method to hash the password on input and update
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'models' . DIRECTORY_SEPARATOR . 'Basic_crud.php';

class User_m extends Basic_crud {

  public function __construct() {
    parent::__construct();
    $this->table = 'users';
  }

  public function insert($data) {
    if ($data['password']) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    return parent::insert($data);
  }

  public function update($where, $data) {
    if ($data['password']) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    return parent::update($where, $data);
  }

  public function updateAll($data) {
    if ($data['password']) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    return parent::updateAll($data);
  }

  /**
   * Helper for login. checking whether a given username and password is valid or not
   * 
   * @param type $email
   * @param type $password
   * @return boolean
   */
  public function is_valid_user($email, $password) {
    $user = $this->get(NULL, 0, ['where' => ['email' => $email]]);
    if ($user && password_verify($password, $user[0]->password)) {
      return $user[0];
    }

    return FALSE;
  }

}
