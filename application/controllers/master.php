<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        redirect('master/desa_kelurahan');
    }

}
