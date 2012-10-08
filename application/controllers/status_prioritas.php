<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Status_prioritas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('snippets_helper'));
        $this->load->model('crud_model', 'crud');
    }

    function log_date() {
        return date($this->config->item('log_date_format'));
    }

    function index($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $this->status_prioritas($query_id, $sort_by, $sort_order, $offset);
    }

    function status_prioritas($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['auth'] = $this->auth;
        // pagination
        $limit = 20;
        $this->load->library(array('form_validation', 'table', 'pagination'));
        $this->input->load_query($query_id);
        $query_array = array(
            'status_prioritas' => $this->input->get('status_prioritas'),
            'active' => 1
        );

        $this->load->model('status_prioritas_model', 'status_prioritas');
        $results = $this->status_prioritas->search($query_array, $limit, $offset, $sort_by, $sort_order);
        //echo get_instance()->db->last_query();
        $data['results'] = $results['results'];
        $data['num_results'] = $results['num_rows'];

        // pagination
        $config = array();
        $config['base_url'] = site_url("master/status_prioritas/$query_id/$sort_by/$sort_order");
        $config['total_rows'] = $data['num_results'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 6;
        $config['num_links'] = 6;

        $config = array_merge($config, default_pagination_btn());
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;

        $data['page_title'] = 'Status Prioritas';

        $data['tools'] = array(
            'master/status_prioritas/create' => 'New'
        );

        $this->load->view('master/status_prioritas', $data);
    }

    function search() {
        $query_array = array(
            'status_prioritas' => $this->input->post('status_prioritas'),
            'active' => 1
        );
        $query_id = $this->input->save_query($query_array);
        redirect("master/status_prioritas/view/$query_id");
    }

    function info() {
        $id = $this->uri->segment(3);
        $data['auth'] = $this->auth;
        $criteria = array(
            'm_status_prioritas.id' => $id
        );
        $this->load->model('status_prioritas_model', 'skpd');
        $result = $this->skpd->get_many('', $criteria)->row_array();
        //[debug]echo get_instance()->db->last_query();
        $result_keys = array_keys($result);
        foreach ($result_keys as $result_key) {
            $data[$result_key] = $result[$result_key];
        }

        $data['tools'] = array(
            'master/status_prioritas' => 'Back',
            'master/status_prioritas/' . $id . '/edit' => 'Edit',
            'master/status_prioritas/' . $id . '/delete' => 'Delete'
        );
        $data['page_title'] = 'Detail Status Prioritas';
        $this->load->view('master/status_prioritas_info', $data);
    }

    function delete() {
        $id = $this->uri->segment(3);
        $this->crud->use_table('m_status_prioritas');
        $criteria = array('id' => $id);
        $data_in = array(
            'active' => 0,
            'modified_on' => logged_info()->on,
            'modified_by' => logged_info()->by
        );
        $this->crud->update($criteria, $data_in);
        redirect('master/status_prioritas');
    }

    function create() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $master_url = 'master/status_prioritas/';

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('status_prioritas_create') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('m_status_prioritas');
            $data_in = array(
                'status_prioritas' => strtoupper($this->input->post('status_prioritas')),
                'keterangan' => $this->input->post('keterangan'),
                'created_on' => date($this->config->item('log_date_format')),
                'created_by' => logged_info()->on
            );
            $created_id = $this->crud->create($data_in);
            redirect('master/status_prioritas/' . $created_id . '/info');
        }
        $data['action_url'] = $master_url . __FUNCTION__;
        $data['page_title'] = 'Create Status Prioritas';
        $data['tools'] = array(
            'master/status_prioritas' => 'Back'
        );

        $this->load->model('status_prioritas_model', 'status_prioritas');
        $data = array_merge($data, $this->status_prioritas->set_default()); //merge dengan arr data dengan default
        $this->load->view('master/status_prioritas_form', $data);
    }

    function edit() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $master_url = 'master/status_prioritas/';
        $id = $this->uri->segment(3);

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('status_prioritas_update') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('m_status_prioritas');
            $criteria = array(
                'id' => $id
            );
            $data_in = array(
                'status_prioritas' => strtoupper($this->input->post('status_prioritas')),
                'keterangan' => $this->input->post('keterangan'),
                'modified_on' => date($this->config->item('log_date_format')),
                'modified_by' => logged_info()->on
            );
            $this->crud->update($criteria, $data_in);
            redirect('master/status_prioritas/' . $id . '/info');
        }
        $data['action_url'] = $master_url . $id . '/' . __FUNCTION__;
        $data['page_title'] = 'Update Status Prioritas';
        $data['tools'] = array(
            'master/status_prioritas' => 'Back'
        );

        $this->crud->use_table('m_status_prioritas');
        $status_prioritas_data = $this->crud->retrieve(array('id' => $id))->row();

        $this->load->model('status_prioritas_model', 'status_prioritas');
        $data = array_merge($data, $this->status_prioritas->set_default()); //merge dengan arr data dengan default
        $data = array_merge($data, (array) $status_prioritas_data);
        $this->load->view('master/status_prioritas_form', $data);
    }

    function unique_status_prioritas($status_prioritas) {
        $this->crud->use_table('m_status_prioritas');
        $status_prioritas = $this->crud->retrieve(array('status_prioritas' => $status_prioritas))->row();
        if (sizeof($status_prioritas) > 0) {
            $this->form_validation->set_message(__FUNCTION__, 'Status Prioritas sudah terdaftar'); //pakai function karena ini harus sama dengan nama function nya
            return FALSE;
        } else {
            return true;
        }
    }

}

?>
