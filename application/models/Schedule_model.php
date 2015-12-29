<?php

class Schedule_model extends CI_Model {

  // ==================================================================================================================

  public function __construct() {
    parent::__construct();
  }

  // ==================================================================================================================

  public function get(array $where = NULL) {
    if ($where) {
      return $this->db->get_where('schedules', $where)->result_object();
    }
    return $this->db->get('schedules')->result_object();
  }

  // ==================================================================================================================

  public function insert(array $data) {
    if ($this->db->insert('schedules', $data)) {
      return $this->db->insert_id();
    }

    return FALSE;
  }

  // ==================================================================================================================

  public function delete(array $where) {
    if ($this->db->delete('schedules', $where)) {
      return $this->db->affected_rows();
    }

    return FALSE;
  }

  // ==================================================================================================================

  public function update_move() {
    $this->load->model('elevator_model');
    foreach ($this->get() as $schedule) {
      $elevator = $this->db
              ->where_in('direction', ['stand', $schedule->direction])
              ->where(['current_floor' => $schedule->floor])
              ->order_by('direction', 'ASC')
              ->get('elevators', 1)
              ->row();
      if (isset($elevator)) {
        $this->elevator_model->open_door($elevator->id);
        $this->delete(['id' => $schedule->id]);
      } else {
        $elevator = $this->db
                ->select('id, ABS(current_floor - ' . $schedule->floor . ') AS dist')
                ->where(['direction' => 'stand', 'alert' => 'door_close'])
                ->order_by('dist', 'ASC')
                ->get('elevators', 1)
                ->row();
        if (isset($elevator)) {
          if ($this->db->insert('stops', ['elevator_id' => $elevator->id, 'floor' => $schedule->floor])) {
            $this->delete(['id' => $schedule->id]);
          } else {
            return FALSE;
          }
        }
      }
    }
    
    return TRUE;
  }

  // ==================================================================================================================
}
