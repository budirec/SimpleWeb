<?php

class Elevator extends CI_Controller {

  // ==================================================================================================================

  public function __construct() {
    parent::__construct();
  }

  // ==================================================================================================================

  public function index() {
    $this->load->model('elevator_model');
    $this->load->model('floor_model');

    $data = [
        'elevator_num' => $this->db->count_all('elevators'),
        'floors' => $this->floor_model->get()
    ];
    $this->load->view('Elevator_view', $data);
  }

  // ==================================================================================================================

  public function get_current() {
    $this->load->model('elevator_model');
    $this->load->model('stop_model');
    $this->load->model('schedule_model');

    $data = [
        'elevators' => $this->elevator_model->get(),
        'schedules' => $this->schedule_model->get(),
        'stops' => $this->stop_model->get()
    ];
    $this->output->set_content_type('json');
    $this->output->set_output(json_encode($data));
  }

  // ==================================================================================================================

  public function update($step = 1) {
    $this->load->model('log_model');
    if ($step == 0) {
      $this->log_model->write('info', 'Update finished.');
      return;
    }
    $this->log_model->write('info', 'Updating');

    $this->load->model('elevator_model');
    if (!$this->elevator_model->update_move()) {
      $this->log_model->write('info', 'Fail updating elevator.');
    }

    $this->load->model('schedule_model');
    if (!$this->schedule_model->update_move()) {
      $this->log_model->write('info', 'Fail updating shcedule.');
    }

    return $this->update($step - 1);
  }

  // ==================================================================================================================

  private function is_floor_available($floor) {
    if ($this->db->get_where('floors', ['id' => $floor])->result_object()[0]->status == 0) {
      $this->log_model->write('info', 'No floor / floor in maintenance');
      return FALSE;
    }

    return TRUE;
  }

  // ==================================================================================================================

  public function request(int $floor, string $direction) {
    $this->load->model('log_model');
    $this->log_model->write('info', 'Request from floor ' . $floor . ' to go ' . $direction);

    if (!$this->is_floor_available($floor)) {
      return FALSE;
    }

    if (strtolower($direction) != 'up' && strtolower($direction) != 'down') {
      $this->log_model->write('info', 'Request is incomplete');
      return FALSE;
    }

    $this->load->model('schedule_model');
    if ($this->schedule_model->insert(['floor' => $floor, 'direction' => $direction])) {
      $this->log_model->write('info', 'Requset served.');
      return TRUE;
    } else {
      $this->log_model->write('error', 'Can\'t insert schedule.');
      return FALSE;
    }
  }

  // ==================================================================================================================

  public function go_to_floor($id, $floor) {
    $this->load->model('log_model');
    $this->log_model->write('info', 'Request to go to floor ' . $floor . ' from elevator ' . $id);

    if (!$this->is_floor_available($floor)) {
      return FALSE;
    }

    $this->load->model('stop_model');
    if ($this->stop_model->insert(['elevator_id' => $id, 'floor' => $floor])) {
      $this->log_model->write('info', 'Requset served.');

      if ($this->db->where(['elevator_id' => $id])->count_all_results('stops') == 1) {
        $elevator = $this->db->get_where('elevators', ['id' => $id])->row();
        $data = ['direction' => ($elevator->current_floor > $floor ? 'down' : 'up')];
        $this->db->update('elevators', $data, ['id' => $id]);
      }

      return TRUE;
    }

    $this->log_model->write('error', 'Can\'t insert stop.');
    return FALSE;
  }

  // ==================================================================================================================
}
