<?php

/**
 * Base Model to support CRUD
 * Note: Need to have field id, created and modified
 */
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Basic_crud extends CI_Model {

  protected $table;

  public function __construct() {
    parent::__construct();
  }

  /**
   * Get data from database
   * 
   * @param int $limit
   * @param int $offset
   * @param array $options [ fields[], where[field => value], order(string), like[field => [value, NULL|before|after|both]] ]
   * @return array of object
   */
  public function get($limit = 10, $offset = 0, $options = NULL) {
    if (is_string($options['fields'])) {
      $this->db->select($options['fields']);
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
      $this->db->order_by($options['order']);
    } else {
      $this->db->order_by('id DESC');
    }

    if ($limit !== NULL) {
      $this->db->limit($limit, $offset);
    }

    $q = $this->db->get($this->table);

    return $q->result();
  }

  /**
   * Count row
   * 
   * @param array $where [ [field => value], like[field => [value, NULL|before|after|both]] ]
   * @return int
   */
  public function get_count($where = NULL) {
    if (is_array($where['where'])) {
      $this->db->where($where['where']);
    }

    if (is_array($where['like'])) {
      $this->db->group_start();
      foreach ($where['like'] as $key => $value) {
        $this->db->or_like($key, $value[0], $value[1]);
      }
      $this->db->group_end();
    }

    $this->db->select('COUNT(*) `ct`');

    $q = $this->db->get($this->table);

    return $q->result()[0]->ct;
  }

  /**
   * Insert data to database
   * 
   * @param array $data [field => value]
   * @return int Inserted id or FALSE on fail
   */
  public function insert($data) {
    $data['created'] = date('Y-m-d H:i:s', time());
    $data['modified'] = $data['created'];

    if ($this->db->insert($this->table, $data)) {
      return $this->db->insert_id();
    }

    return FALSE;
  }

  /**
   * Update database with where clause
   * 
   * @param array $where [field => value]
   * @param array $data [field => value]
   * @return int number of affected rows or FALSE on fail
   */
  public function update($where, $data) {
    $data['modified'] = date('Y-m-d H:i:s');

    if (is_array($where)) {
      $this->db->where($where);
      if ($this->db->update($this->table, $data)) {
        return $this->db->affected_rows();
      }
    }

    return FALSE;
  }

  /**
   * Update database without where clause
   * Becareful on usage
   * 
   * @param array $data [field => value]
   * @return int number of affected rows or FALSE on fail
   */
  public function updateAll($data) {
    $data['modified'] = date('Y-m-d H:i:s');

    if ($this->db->update($this->table, $data)) {
      return $this->db->affected_rows();
    }

    return FALSE;
  }

  /**
   * Delete with where clause
   * 
   * @param array $where
   * @return int number of affected rows or FALSE on fail
   */
  public function delete($where) {
    if (is_array($where)) {
      $this->db->where($where);
      if ($this->db->delete($this->table)) {
        return $this->db->affected_rows();
      }
    }

    return FALSE;
  }

  /**
   * Delete all, better used truncate?
   * 
   * @return int number of affected rows or FALSE on fail
   */
  public function deleteAll() {
    if ($this->db->delete($this->table)) {
      return $this->db->affected_rows();
    }

    return FALSE;
  }

}
