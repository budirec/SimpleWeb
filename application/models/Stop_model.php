<?php

class Stop_model extends CI_Model {

  // ==================================================================================================================
  
  public function __construct() {
    parent::__construct();
  }

  // ==================================================================================================================
  
  public function get(array $where = NULL) {
    if ($where) {
      return $this->db->get_where('stops', $where)->result_object();
    }
    return $this->db->get('stops')->result_object();
  }

  // ==================================================================================================================
  
  public function insert(array $data) {
    if ($this->db->insert('stops', $data)) {
      return $this->db->insert_id();
    }

    return FALSE;
  }

  // ==================================================================================================================
  
  public function delete(array $where) {
    if ($this->db->delete('stops', $where)) {
      return $this->db->affected_rows();
    }

    return FALSE;
  }

  // ==================================================================================================================
}
