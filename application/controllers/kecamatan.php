<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kecamatan extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('snippets_helper'));
        $this->load->model('crud_model', 'crud');
    }

    function log_date() {
        return date($this->config->item('log_date_format'));
    }

    function index($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $this->kecamatan($query_id, $sort_by, $sort_order, $offset);
    }

    function kecamatan($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['auth'] = $this->auth;
        // pagination
        $limit = 20;
        $this->load->library(array('form_validation', 'table', 'pagination'));
        $this->input->load_query($query_id);
        $query_array = array(
            'kecamatan' => $this->input->get('kecamatan'),
            'active' => 1
        );

        $this->load->model('kecamatan_model', 'kecamatan');
        $results = $this->kecamatan->search($query_array, $limit, $offset, $sort_by, $sort_order);
        //echo get_instance()->db->last_query();
        $data['results'] = $results['results'];
        $data['num_results'] = $results['num_rows'];

        // pagination
        $config = array();
        $config['base_url'] = site_url("master/kecamatan/$query_id/$sort_by/$sort_order");
        $config['total_rows'] = $data['num_results'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 6;
        $config['num_links'] = 6;

        $config = array_merge($config, default_pagination_btn());
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;

        $data['page_title'] = 'Kecamatan';

        $data['tools'] = array(
            'master/kecamatan/create' => 'New'
        );

        $this->load->view('master/kecamatan', $data);
    }

    function search() {
        $query_array = array(
            'kecamatan' => $this->input->post('kecamatan'),
            'active' => 1
        );
        $query_id = $this->input->save_query($query_array);
        redirect("master/kecamatan/view/$query_id");
    }

    function info() {
        $id = $this->uri->segment(3);
        $data['auth'] = $this->auth;
        $criteria = array(
            'm_kecamatan.id' => $id
        );
        $this->load->model('kecamatan_model', 'kecamatan');
        $result = $this->kecamatan->get_many('', $criteria)->row_array();
        //[debug]echo get_instance()->db->last_query();
        $result_keys = array_keys($result);
        foreach ($result_keys as $result_key) {
            $data[$result_key] = $result[$result_key];
        }

        $data['tools'] = array(
            'master/kecamatan' => 'Back',
            'master/kecamatan/' . $id . '/edit' => 'Edit',
            'master/kecamatan/' . $id . '/delete' => 'Delete'
        );
        $data['page_title'] = 'Detail Kecamatan';
        $this->load->view('master/kecamatan_info', $data);
    }

    function delete() {
        $id = $this->uri->segment(3);
        $this->crud->use_table('m_kecamatan');
        $criteria = array('id' => $id);
        $data_in = array(
            'active' => 0,
            'modified_on' => logged_info()->on,
            'modified_by' => logged_info()->by
        );
        $this->crud->update($criteria, $data_in);
        redirect('master/kecamatan');
    }

    function create() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $master_url = 'master/kecamatan/';

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('kecamatan_create') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('m_kecamatan');
            $data_in = array(
                'kelurahan_id' => $this->input->post('kelurahan_id'),
                'kecamatan' => strtoupper($this->input->post('kecamatan')),
                'keterangan' => $this->input->post('keterangan'),
                'created_on' => date($this->config->item('log_date_format')),
                'created_by' => logged_info()->on
            );
            $created_id = $this->crud->create($data_in);
            redirect('master/kecamatan/' . $created_id . '/info');
        }
        $data['action_url'] = $master_url . __FUNCTION__;
        $data['page_title'] = 'Create Kecamatan';
        $data['tools'] = array(
            'master/kecamatan' => 'Back'
        );

        $this->crud->use_table('m_desa_kelurahan');
        $data['desa_kelurahan_options'] = $this->crud->retrieve()->result();

        $this->load->model('kecamatan_model', 'kecamatan');
        $data = array_merge($data, $this->kecamatan->set_default()); //merge dengan arr data dengan default
        $this->load->view('master/kecamatan_form', $data);
    }

    function edit() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $master_url = 'master/kecamatan/';
        $id = $this->uri->segment(3);

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('kecamatan_update') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('m_kecamatan');
            $criteria = array(
                'id' => $id
            );
            $data_in = array(
                'kelurahan_id' => $this->input->post('kelurahan_id'),
                'nama_kecamatan' => strtoupper($this->input->post('nama_kecamatan')),
                'keterangan' => $this->input->post('keterangan'),
                'modified_on' => date($this->config->item('log_date_format')),
                'modified_by' => logged_info()->on
            );
            $this->crud->update($criteria, $data_in);
            redirect('master/kecamatan/' . $id . '/info');
        }
        $data['action_url'] = $master_url . $id . '/' . __FUNCTION__;
        $data['page_title'] = 'Update Kecamatan';
        $data['tools'] = array(
            'master/kecamatan' => 'Back'
        );

        $this->crud->use_table('m_kecamatan');
        $kecamatan_data = $this->crud->retrieve(array('id' => $id))->row();

        $this->crud->use_table('m_desa_kelurahan');
        $data['desa_kelurahan_options'] = $this->crud->retrieve()->result();

        $this->load->model('kecamatan_model', 'kecamatan');
        $data = array_merge($data, $this->kecamatan->set_default()); //merge dengan arr data dengan default
        $data = array_merge($data, (array) $kecamatan_data);
        $this->load->view('master/kecamatan_form', $data);
    }

    function unique_kecamatan($nama_kecamatan) {
        $this->crud->use_table('m_kecamatan');
        $kecamatan = $this->crud->retrieve(array('kecamatan' => $nama_kecamatan))->row();
        if (sizeof($kecamatan) > 0) {
            $this->form_validation->set_message(__FUNCTION__, 'Kecamatan sudah terdaftar'); //pakai function karena ini harus sama dengan nama function nya
            return FALSE;
        } else {
            return true;
        }
    }

}

?>
