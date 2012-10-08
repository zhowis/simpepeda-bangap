<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Status_pelaksanaan extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('snippets_helper'));
        $this->load->model('crud_model', 'crud');
    }

    function log_date() {
        return date($this->config->item('log_date_format'));
    }

    function index($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $this->status_pelaksanaan($query_id, $sort_by, $sort_order, $offset);
    }

    function status_pelaksanaan($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['auth'] = $this->auth;
        // pagination
        $limit = 20;
        $this->load->library(array('form_validation', 'table', 'pagination'));
        $this->input->load_query($query_id);
        $query_array = array(
            'status_pelaksanaan' => $this->input->get('status_pelaksanaan'),
            'active' => 1
        );

        $this->load->model('status_pelaksanaan_model', 'status_pelaksanaan');
        $results = $this->status_pelaksanaan->search($query_array, $limit, $offset, $sort_by, $sort_order);
        //echo get_instance()->db->last_query();
        $data['results'] = $results['results'];
        $data['num_results'] = $results['num_rows'];

        // pagination
        $config = array();
        $config['base_url'] = site_url("master/status_pelaksanaan/$query_id/$sort_by/$sort_order");
        $config['total_rows'] = $data['num_results'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 6;
        $config['num_links'] = 6;

        $config = array_merge($config, default_pagination_btn());
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;

        $data['page_title'] = 'Status Pelaksanaan';

        $data['tools'] = array(
            'master/status_pelaksanaan/create' => 'New'
        );

        $this->load->view('master/status_pelaksanaan', $data);
    }

    function search() {
        $query_array = array(
            'status_pelaksanaan' => $this->input->post('status_pelaksanaan'),
            'active' => 1
        );
        $query_id = $this->input->save_query($query_array);
        redirect("master/status_pelaksanaan/view/$query_id");
    }

    function info() {
        $id = $this->uri->segment(3);
        $data['auth'] = $this->auth;
        $criteria = array(
            'm_status_pelaksanaan.id' => $id
        );
        $this->load->model('status_pelaksanaan_model', 'status_pelaksanaan');
        $result = $this->status_pelaksanaan->get_many('', $criteria)->row_array();
        //[debug]echo get_instance()->db->last_query();
        $result_keys = array_keys($result);
        foreach ($result_keys as $result_key) {
            $data[$result_key] = $result[$result_key];
        }

        $data['tools'] = array(
            'master/status_pelaksanaan' => 'Back',
            'master/status_pelaksanaan/' . $id . '/edit' => 'Edit',
            'master/status_pelaksanaan/' . $id . '/delete' => 'Delete'
        );
        $data['page_title'] = 'Detail Status Pelaksanaan';
        $this->load->view('master/status_pelaksanaan_info', $data);
    }

    function delete() {
        $id = $this->uri->segment(3);
        $this->crud->use_table('m_status_pelaksanaan');
        $criteria = array('id' => $id);
        $data_in = array(
            'active' => 0,
            'modified_on' => logged_info()->on,
            'modified_by' => logged_info()->by
        );
        $this->crud->update($criteria, $data_in);
        redirect('master/status_pelaksanaan');
    }

    function create() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $master_url = 'master/status_pelaksanaan/';

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('status_pelaksanaan_create') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('m_status_pelaksanaan');
            $data_in = array(
                'status_pelaksanaan' => strtoupper($this->input->post('status_pelaksanaan')),
                'keterangan' => $this->input->post('keterangan'),
                'created_on' => date($this->config->item('log_date_format')),
                'created_by' => logged_info()->on
            );
            $created_id = $this->crud->create($data_in);
            redirect('master/status_pelaksanaan/' . $created_id . '/info');
        }
        $data['action_url'] = $master_url . __FUNCTION__;
        $data['page_title'] = 'Create Status Pelaksanaan';
        $data['tools'] = array(
            'master/status_pelaksanaan' => 'Back'
        );

        $this->load->model('status_pelaksanaan_model', 'status_pelaksanaan');
        $data = array_merge($data, $this->status_pelaksanaan->set_default()); //merge dengan arr data dengan default
        $this->load->view('master/status_pelaksanaan_form', $data);
    }

    function edit() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $master_url = 'master/status_pelaksanaan/';
        $id = $this->uri->segment(3);

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('status_pelaksanaan_update') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('m_status_pelaksanaan');
            $criteria = array(
                'id' => $id
            );
            $data_in = array(
                'status_pelaksanaan' => strtoupper($this->input->post('status_pelaksanaan')),
                'keterangan' => $this->input->post('keterangan'),
                'modified_on' => date($this->config->item('log_date_format')),
                'modified_by' => logged_info()->on
            );
            $this->crud->update($criteria, $data_in);
            redirect('master/status_pelaksanaan/' . $id . '/info');
        }
        $data['action_url'] = $master_url . $id . '/' . __FUNCTION__;
        $data['page_title'] = 'Update Status Pelaksanaan';
        $data['tools'] = array(
            'master/status_pelaksanaan' => 'Back'
        );

        $this->crud->use_table('m_status_pelaksanaan');
        $status_pelaksanaan_data = $this->crud->retrieve(array('id' => $id))->row();

        $this->load->model('status_pelaksanaan_model', 'status_pelaksanaan');
        $data = array_merge($data, $this->status_pelaksanaan->set_default()); //merge dengan arr data dengan default
        $data = array_merge($data, (array) $status_pelaksanaan_data);
        $this->load->view('master/status_pelaksanaan_form', $data);
    }

    function unique_status_pelaksanaan($status_pelaksanaan) {
        $this->crud->use_table('m_status_pelaksanaan');
        $status_pelaksanaan = $this->crud->retrieve(array('status_pelaksanaan' => $status_pelaksanaan))->row();
        if (sizeof($status_pelaksanaan) > 0) {
            $this->form_validation->set_message(__FUNCTION__, 'Status Pelaksanaan sudah terdaftar'); //pakai function karena ini harus sama dengan nama function nya
            return FALSE;
        } else {
            return true;
        }
    }

}

?>
