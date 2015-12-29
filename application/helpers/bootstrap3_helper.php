<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('bootstrap_progress_bar')) {

  /**
   * Create a bootstrap 3 progress bar
   * 
   * @param string $label
   * @param int $percent
   * @return string HTML code
   */
  function bootstrap3_progress_bar($label, $percent = 0) {
    return '<label>' . $label . '</label>'
            . '<div class="progress">'
            . '<div class="progress-bar" role="progressbar" aria-valuenow="' . $percent . '" aria-valuemin="0" '
            . 'aria-valuemax="100" style="width: ' . $percent . '%;">'
            //Make the percentage outside(< 40%) or inside the progress bar
            . ($percent < 40 ? '</div>' . $percent . '%' : $percent . '%</div>')
            . '</div>';
  }

}

if (!function_exists('bootstrap3_input')) {

  /**
   * Create a bootstrap 3 input & textarea field
   * 
   * TODO: 
   * -Other input fields such as select, checkbox
   * -Validation states
   * 
   * @param string $type [text, password, number, email, textarea] other type might not have good result yet
   * @param string $name Name of this input that will be send to the server
   * @param string $label Label text for this input, will appear in placeholder too
   * @param string $id If empty will be the same as name
   * @return string HTML code
   */
  function bootstrap3_input($type, $name, $label, $id = '') {
    $id = ($id ? $id : $name);
    $str = '<div class="form-group">';
    $str .= '<label for="' . $id . '">' . $label . '</label>';
    if (strtolower($type) == 'textarea') {
      $str .= '<textarea '
              . 'rows="5" '
              . 'name="' . $name . '" '
              . 'class="form-control" '
              . 'id="' . $id . '" '
              . 'placeholder="' . $label . '">'
              . '</textarea>';
    } else {
      $str .= '<input '
              . 'type="' . $type . '" '
              . 'name="' . $name . '" '
              . 'class="form-control" '
              . 'id="' . $id . '" '
              . 'placeholder="' . $label . '">';
    }
    $str .= '</div>';

    return $str;
  }

}