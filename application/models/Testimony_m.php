<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'models' . DIRECTORY_SEPARATOR . 'Basic_crud.php';

class Testimony_m extends Basic_crud {

  public function __construct() {
    parent::__construct();
    $this->table = 'testimonies';
  }

  /**
   * Overwrite parent get so we can combine the result with user table(getting their name)
   * use t for testimony alias and u for user alias
   * 
   * @param int $limit
   * @param int $offset
   * @param array $options [ fields[], where[field => value], order(string), like[field => [value, NULL|before|after|both]] ]
   * @return array of object
   */
  public function get($limit = 10, $offset = 0, $options = NULL) {
    if (is_string($options['fields'])) {
      $this->db->select($options['fields']);
    } else {
      $this->db->select('t.*, u.name');
    }

    if (is_array($options['where'])) {
      $this->db->where($options['where']);
    }
    
    if (is_array($options['like'])) {
      $this->db->group_start();
      foreach ($options['like'] as $key => $value) {
        $this->db->or_like($key, $value[0], $value[1]);
      }
      $this->db->group_end();
    }

    if (is_string($options['order'])) {
      $this->db->order($options['order']);
    } else {
      $this->db->order_by('t.id DESC');
    }

    if ($limit !== NULL) {
      $this->db->limit($limit, $offset);
    }

    $this->db->from('testimonies t');
    $this->db->join('users u', 't.user_id = u.id');
    $q = $this->db->get();
    
    return $q->result();
  }

}
