<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('news_m');
  }

  public function index($region = 'en', $id = NULL) {
    $this->output->set_content_type('json');

    $param = [];
    $param['where']['region'] = $region;
    if ($id) {
      $param['where']['id <'] = $id;
    }

    $result['region'] = $region;
    $news = $this->news_m->get(10, 0, $param);
    if (count($news) > 0) {
      foreach ($news as $value) {
        $content = $value->content_en;
        if ($region != 'en' && empty($content)) {
          $content = $value->content_id;
        }
        $result['news'][] = [
            'id' => $value->id,
            'header' => $value->header,
            'img' => $value->img,
            'content' => (strlen($content) > 100 ? substr($content, 0, 100) . ' ...' : $content),
            'created' => $value->created
        ];
      }
    } else {
      $result['error'] = 'No more news.';
    }

    $this->output->set_output(json_encode([$result]));
    return $result;
  }

  public function index_id($region = 'id', $id = NULL) {
    $this->output->set_content_type('json');

    $param = [];
    $param['where']['region'] = $region;
    if ($id) {
      $param['where']['id <'] = $id;
    }

    $result['region'] = $region;
    $news = $this->news_m->get(10, 0, $param);
    if (count($news) > 0) {
      foreach ($news as $value) {
        $content = $value->content_id;
        if ($region != 'id' && empty($content)) {
          $content = $value->content_en;
        }
        $result['news'][] = [
            'id' => $value->id,
            'header' => $value->header,
            'img' => $value->img,
            'content' => (strlen($content) > 100 ? substr($content, 0, 100) . ' ...' : $content),
            'created' => $value->created
        ];
      }
    } else {
      $result['error'] = 'Tidak ada berita lagi.';
    }

    $this->output->set_output(json_encode([$result]));
    return $result;
  }

  public function view($id = NULL) {
    $this->output->set_content_type('json');

    if ($id === NULL) {
      $this->output->set_output(json_encode(['error' => 'No news selected.']));
      return FALSE;
    }

    $news = $this->news_m->get(NULL, 0, ['where' => ['id' => $id]]);
    if ($news) {
      $this->output->set_output(json_encode($news[0]));
      return $news[0];
    }

    $this->output->set_output(json_encode(['error' => 'News cannot be found.']));
    return FALSE;
  }

  //Administration use
  
  /**
   * @param string $img file name to store in database
   * @return NULL or array on error
   */
  private function validate_data(&$img) {
    $ret = [];
    //Uploading file(image) only gif/jpg/png extension is accepted
    if (!empty($_FILES['img']['name'])) {
      $config['upload_path'] = './public/img/';
      $config['allowed_types'] = 'gif|jpg|png';
      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('img')) {
        $ret[] = $this->upload->display_errors();
      } else {
        $img = $this->upload->data()['file_name'];
      }
    }

    $this->load->library('form_validation');
    $this->form_validation->set_rules('header', 'Title', 'trim|required');
    $this->form_validation->set_rules('content', 'Content', 'trim|required');

    if ($this->form_validation->run() === FALSE) {
      $ret[] = validation_errors();
    }

    return $ret;
  }

  public function add() {
    $this->output->set_content_type('json');

    $img = '';
    $error = $this->validate_data($img);
    if ($error) {
      $this->output->set_output(json_encode(['error' => $error]));
      return FALSE;
    }

    $content_en = trim($this->input->post('content'));
    $content_id = trim($this->input->post('translated')) or '';
    if ($this->session->userdata('region') == 'id') {
      $temp = $content_id;
      $content_id = $content_en;
      $content_en = $temp;
    }

    $data = [
        'region' => $this->session->userdata('region'),
        'header' => trim($this->input->post('header')),
        'content_en' => $content_en,
        'content_id' => $content_id,
    ];
    if (!empty($img)) {
      $data['img'] = $img;
    }

    if ($this->input->post('id')) {
      if ($this->news_m->update(['id' => $this->input->post('id')], $data) === FALSE) {
        $this->output->set_output(json_encode(['error' => 'Can\'t update data.']));
        return FALSE;
      }
    } else {
      if ($this->news_m->insert($data) == FALSE) {
        $this->output->set_output(json_encode(['error' => 'Can\'t insert data.']));
        return FALSE;
      }
    }

    $this->output->set_output(json_encode(['success' => 1]));
    return TRUE;
  }

  public function delete() {
    $this->output->set_content_type('json');

    if ($this->news_m->delete(['id' => $this->input->post('id')])) {
      $this->output->set_output(json_encode(['success' => 1]));
      return TRUE;
    }

    $this->output->set_output(json_encode(['error' => 'Can\'t update database.']));
    return FALSE;
  }

  /**
   * Based on DataTables API calls
   */
  public function adm($region = 'en') {
    $this->output->set_content_type('json');

    $param['where'] = ['region' => $region];
    
    $result['recordsTotal'] = $this->news_m->get_count($param);
    $result['recordsFiltered'] = $result['recordsTotal'];
    //Used so DataTables know which data arrived
    $result['draw'] = $this->input->get('draw');

    if (!empty($this->input->get('search')['value'])) {
      $search_value = $this->input->get('search')['value'];
      $param['like'] = [
          'header' => [$search_value, 'both'],
          'content_'.$this->session->userdata('region') => [$search_value, 'both']
      ];
      $result['recordsFiltered'] = $this->news_m->get_count($param);
    }
    $news = $this->news_m->get($this->input->get('length'), $this->input->get('start'), $param);
    
    if ($result['recordsFiltered'] > 0) {
      foreach ($news as $value) {
        $content = $value->{content_ . $region};

        $result['data'][] = [
            'id' => $value->id,
            'header' => $value->header,
            'img' => ($value->img ? '<img src="' . base_url('public/img/' . $value->img) . '" class="img-responsive">' : ''),
            'content' => (strlen($content) > 100 ? substr($content, 0, 100) . ' ...' : $content),
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
      $result['data'] = [];
    }

    $this->output->set_output(json_encode($result));
    return $result;
  }

}
