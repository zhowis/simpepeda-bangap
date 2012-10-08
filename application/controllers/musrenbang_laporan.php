<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Musrenbang_laporan extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('snippets_helper'));
        $this->load->model('crud_model', 'crud');
    }

    function log_date() {
        return date($this->config->item('log_date_format'));
    }

    function index($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $this->musrenbang_laporan($query_id, $sort_by, $sort_order, $offset);
    }

    function musrenbang_laporan($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['auth'] = $this->auth;
        // pagination
        $limit = 20;
        $this->load->library(array('form_validation', 'table', 'pagination'));
        $this->input->load_query($query_id);
        $query_array = array(
            'skpd' => $this->input->get('skpd'),
            'nama_kegiatan' => $this->input->get('nama_kegiatan'),
            'kecamatan' => $this->input->get('kecamatan'),
            'kelurahan' => $this->input->get('kelurahan'),
            'active' => 1
        );

        $this->load->model('musrenbang_usulan_model', 'musrenbang_usulan');
        $results = $this->musrenbang_usulan->search($query_array, $limit, $offset, $sort_by, $sort_order);
        //echo get_instance()->db->last_query();
        $data['results'] = $results['results'];
        $data['num_results'] = $results['num_rows'];

        // pagination
        $config = array();
        $config['base_url'] = site_url("transaction/musrenbang_usulan/$query_id/$sort_by/$sort_order");
        $config['total_rows'] = $data['num_results'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 6;
        $config['num_links'] = 6;

        $config = array_merge($config, default_pagination_btn());
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;

        $data['page_title'] = 'MUSRENBANG Usulan';

        $data['tools'] = array();

        $this->load->view('transaction/musrenbang_usulan', $data);
    }

    function search() {
        $query_array = array(
            'skpd' => $this->input->post('skpd'),
            'nama_kegiatan' => $this->input->post('nama_kegiatan'),
            'kecamatan' => $this->input->post('kecamatan'),
            'kelurahan' => $this->input->post('kelurahan'),
            'active' => 1
        );

        /*
          echo '<pre>';
          var_dump($query_array);
          echo '</pre>';
         */
        $query_id = $this->input->save_query($query_array);
        redirect("transaction/musrenbang_usulan/view/$query_id");
    }

   
}

?>
