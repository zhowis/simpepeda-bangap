<?php

/**
 * Grab semua log
 *
 * @author Yudi
 */
class log extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('crud');
        $this->crud->use_table('logs');
    }

    function index() {//LOM KELAR INI
        $data = $this->crud->retrieve()->result();
        $this->load->view();
    }

}

?>
