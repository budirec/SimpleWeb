<?php

class Floor_model extends CI_Model {

  // ==================================================================================================================

  public function __construct() {
    parent::__construct();
  }

  // ==================================================================================================================

  public function get(array $where = NULL) {
    $this->db->order_by('id', 'DESC');
    if ($where) {
      return $this->db->get_where('floors', $where)->result_object();
    }
    return $this->db->get('floors')->result_object();
  }

  // ==================================================================================================================
}
