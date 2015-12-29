<?php

class Elevator_model extends CI_Model {

  // ==================================================================================================================

  public function __construct() {
    parent::__construct();
  }

  // ==================================================================================================================

  public function get(array $where = NULL) {
    if ($where) {
      return $this->db->get_where('elevators', $where)->result_object();
    }

    return $this->db->get('elevators')->result_object();
  }

  // ==================================================================================================================

  public function update_move() {
    $this->load->model('log_model');

    foreach ($this->get() as $elevator) {
      if ($elevator->alert == 'door_open') {
        $this->close_door($elevator->id);
      } else {
        $where = ['elevator_id' => $elevator->id];
        if ($elevator->direction == 'up') {
          $where['floor >='] = $elevator->current_floor;
          $this->db->order_by('floor', 'ASC');
        } elseif ($elevator->direction == 'down') {
          $where['floor <='] = $elevator->current_floor;
          $this->db->order_by('floor', 'DESC');
        } else {
          $this->db
                  ->select('*, ABS(floor - ' . $elevator->current_floor . ') AS dist')
                  ->order_by('dist', 'ASC');
        }
        $stops = $this->db->get_where('stops', $where, 1)->row();

        if (isset($stops)) {
          if ($stops->floor != $elevator->current_floor) {
            $this->db->set(
                    'current_floor', 'current_floor ' . ($stops->floor > $elevator->current_floor ? '+' : '-') . ' 1', FALSE
            );
            if ($elevator->direction == 'stand') {
              $this->db->set('direction', ($stops->floor > $elevator->current_floor ? 'up' : 'down'));
            }
            if (!$this->db->update('elevators', $data, ['id' => $elevator->id])) {
              return FALSE;
            } else {
              $this->log_model->write('info', 'Elevator ' . $elevator->id . ' is moving.');
            }
          } else {
            if ($this->open_door($elevator->id)) {
              if (!$this->db->delete('stops', ['id' => $stops->id])) {
                return FALSE;
              } else {
                $this->log_model->write('info', 'Deleting stop for elevator ' . $elevator->id);
              }
            }
          }
        }
      }
    }
    
    return TRUE;
  }

  // ==================================================================================================================

  public function open_door($id) {
    $this->load->model('log_model');

    if ($this->db->update('elevators', ['alert' => 'door_open'], ['id' => $id])) {
      $this->log_model->write('info', 'Elevator ' . $id . ' door opened.');
      return $this->db->affected_rows();
    }

    $this->log_model->write('info', 'Elevator ' . $id . ' door can\'t be opened.');
    return FALSE;
  }

  // ==================================================================================================================

  public function close_door($id) {
    $this->load->model('log_model');

    $data = ['alert' => 'door_close'];
    if ($this->db->where(['elevator_id' => $id])->count_all_results('stops') == 0) {
      $data['direction'] = 'stand';
    }

    if ($this->db->update('elevators', $data, ['id' => $id])) {
      $this->log_model->write('info', 'Elevator ' . $id . ' door closed.');
      return $this->db->affected_rows();
    }

    $this->log_model->write('info', 'Elevator ' . $id . ' door can\'t be closed.');
    return FALSE;
  }

  // ==================================================================================================================
}
