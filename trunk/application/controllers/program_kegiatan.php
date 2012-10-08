<?php

/*
 * Meon
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class _usulan extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('snippets_helper'));
        $this->load->model('crud_model', 'crud');
    }

    function log_date() {
        return date($this->config->item('log_date_format'));
    }

    function index($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $this->musrenbang_usulan($query_id, $sort_by, $sort_order, $offset);
    }

    function musrenbang_usulan($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
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

        $data['tools'] = array(
            'transaction/musrenbang_usulan/create' => 'New'
        );

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

    function info() {
        $id = $this->uri->segment(3);
        $data['auth'] = $this->auth;
        $criteria = array(
            't_musrenbang_usulan.id' => $id
        );
        $this->load->model('musrenbang_usulan_model', 'musrenbang_usulan');
        $result = $this->musrenbang_usulan->get_many('', $criteria)->row_array();
        //[debug]echo get_instance()->db->last_query();
        $result_keys = array_keys($result);
        foreach ($result_keys as $result_key) {
            $data[$result_key] = $result[$result_key];
        }

        $data['tools'] = array(
            'transaction/musrenbang_usulan' => 'Back',
            'transaction/musrenbang_usulan/' . $id . '/edit' => 'Edit',
            'transaction/musrenbang_usulan/' . $id . '/delete' => 'Delete'
        );
        $data['page_title'] = 'Detail Musrenbang Usulan';
        $this->load->view('transaction/musrenbang_usulan_info', $data);
    }

    function delete() {
        $id = $this->uri->segment(3);
        $this->crud->use_table('t_musrenbang_usulan');
        $criteria = array('id' => $id);
        $data_in = array(
            'active' => 0,
            'modified_on' => logged_info()->on,
            'modified_by' => logged_info()->by
        );
        $this->crud->update($criteria, $data_in);
        redirect('transaction/musrenbang_usulan');
    }

    function create() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $trancaction_url = 'transaction/musrenbang_usulan/';

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('musrenbang_usulan_create') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('t_musrenbang_usulan');
            $data_in = array(
                'nomor_kegiatan' => $this->input->post('nomor_kegiatan'),
                'nama_kegiatan' => $this->input->post('nama_kegiatan'),
                'nama_pengusul' => $this->input->post('nama_pengusul'),
                'skpd_id' => $this->input->post('skpd_id'),
                'desa_kelurahan_id' => $this->input->post('desa_kelurahan_id'),
                'kecamatan_id' => $this->input->post('kecamatan_id'),
                'status_kegiatan_id' => $this->input->post('status_kegiatan_id'),
                'status_prioritas_id' => $this->input->post('status_prioritas_id'),
                'detail_lokasi' => $this->input->post('detail_lokasi'),
                'volume_dimensi' => $this->input->post('volume_dimensi'),
                'usulan_dana' => $this->input->post('usulan_dana'),
                'sasaran' => $this->input->post('sasaran'),
                'target' => $this->input->post('target'),
                'keterangan' => $this->input->post('keterangan'),
                'status_musrenbang_id' => $this->input->post('status_musrenbang_id'),
                'created_on' => date($this->config->item('log_date_format')),
                'created_by' => logged_info()->on
            );
            /*
              echo '<pre>';
              var_dump($data_in);
              echo '</pre>';
             */
            $created_id = $this->crud->create($data_in);
            redirect('transaction/musrenbang_usulan/' . $created_id . '/info');
        }
        $data['action_url'] = $trancaction_url . __FUNCTION__;
        $data['page_title'] = 'Create MUSRENBANG Usulan';
        $data['tools'] = array(
            'transaction/musrenbang_usulan' => 'Back'
        );

        $this->crud->use_table('m_desa_kelurahan');
        $data['desa_kelurahan_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_kecamatan');
        $data['kecamatan_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_status_kegiatan');
        $data['status_kegiatan_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_status_prioritas');
        $data['status_prioritas_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_skpd');
        $data['skpd_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_status_musrenbang');
        $data['status_musrenbang_options'] = $this->crud->retrieve()->result();

        $this->load->model('musrenbang_usulan_model', 'musrenbang_usulan');
        $data = array_merge($data, $this->musrenbang_usulan->set_default()); //merge dengan arr data dengan default
        $this->load->view('transaction/musrenbang_usulan_form', $data);
    }

    function edit() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $trancaction_url = 'transaction/musrenbang_usulan/';
        $id = $this->uri->segment(3);

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('musrenbang_usulan_update') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('t_musrenbang_usulan');
            $criteria = array(
                'id' => $id
            );
            $data_in = array(
                'nomor_kegiatan' => $this->input->post('nomor_kegiatan'),
                'nama_kegiatan' => $this->input->post('nama_kegiatan'),
                'nama_pengusul' => $this->input->post('nama_pengusul'),
                'skpd_id' => $this->input->post('skpd_id'),
                'desa_kelurahan_id' => $this->input->post('desa_kelurahan_id'),
                'kecamatan_id' => $this->input->post('kecamatan_id'),
                'status_kegiatan_id' => $this->input->post('status_kegiatan_id'),
                'status_prioritas_id' => $this->input->post('status_prioritas_id'),
                'detail_lokasi' => $this->input->post('detail_lokasi'),
                'volume_dimensi' => $this->input->post('volume_dimensi'),
                'usulan_dana' => $this->input->post('usulan_dana'),
                'sasaran' => $this->input->post('sasaran'),
                'target' => $this->input->post('target'),
                'keterangan' => $this->input->post('keterangan'),
                'status_musrenbang_id' => $this->input->post('status_musrenbang_id'),
                'modified_on' => date($this->config->item('log_date_format')),
                'modified_by' => logged_info()->on
            );
            $this->crud->update($criteria, $data_in);
            redirect('transaction/musrenbang_usulan/' . $id . '/info');
        }
        $data['action_url'] = $trancaction_url . $id . '/' . __FUNCTION__;
        $data['page_title'] = 'Update MUSRENBANG Usulan';
        $data['tools'] = array(
            'transaction/musrenbang_usulan' => 'Back'
        );

        $this->crud->use_table('t_musrenbang_usulan');
        $musrenbang_usulan_data = $this->crud->retrieve(array('id' => $id))->row();

        $this->crud->use_table('m_desa_kelurahan');
        $data['desa_kelurahan_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_kecamatan');
        $data['kecamatan_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_status_kegiatan');
        $data['status_kegiatan_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_status_prioritas');
        $data['status_prioritas_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_skpd');
        $data['skpd_options'] = $this->crud->retrieve()->result();

        $this->crud->use_table('m_status_musrenbang');
        $data['status_musrenbang_options'] = $this->crud->retrieve()->result();

        $this->load->model('musrenbang_usulan_model', 'musrenbang_usulan');
        $data = array_merge($data, $this->musrenbang_usulan->set_default()); //merge dengan arr data dengan default
        $data = array_merge($data, (array) $musrenbang_usulan_data);
        $this->load->view('transaction/musrenbang_usulan_form', $data);
    }

}

?>
