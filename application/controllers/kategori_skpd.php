<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori_skpd extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('snippets_helper'));
        $this->load->model('crud_model', 'crud');
    }

    function log_date() {
        return date($this->config->item('log_date_format'));
    }

    function index($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $this->kategori_skpd($query_id, $sort_by, $sort_order, $offset);
    }

    function kategori_skpd($query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['auth'] = $this->auth;
        // pagination
        $limit = 20;
        $this->load->library(array('form_validation', 'table', 'pagination'));
        $this->input->load_query($query_id);
        $query_array = array(
            'kategori_skpd' => $this->input->get('kategori_skpd'),
            'active' => 1
        );

        $this->load->model('kategori_skpd_model', 'kategori_skpd');
        $results = $this->kategori_skpd->search($query_array, $limit, $offset, $sort_by, $sort_order);
        //echo get_instance()->db->last_query();
        $data['results'] = $results['results'];
        $data['num_results'] = $results['num_rows'];

        // pagination
        $config = array();
        $config['base_url'] = site_url("master/kategori_skpd/$query_id/$sort_by/$sort_order");
        $config['total_rows'] = $data['num_results'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 6;
        $config['num_links'] = 6;

        $config = array_merge($config, default_pagination_btn());
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;

        $data['page_title'] = 'Kategori SKPD';

        $data['tools'] = array(
            'master/kategori_skpd/create' => 'New'
        );

        $this->load->view('master/kategori_skpd', $data);
    }

    function search() {
        $query_array = array(
            'kategori_skpd' => $this->input->post('kategori_skpd'),
            'active' => 1
        );
        $query_id = $this->input->save_query($query_array);
        redirect("master/kategori_skpd/view/$query_id");
    }

    function info() {
        $id = $this->uri->segment(3);
        $data['auth'] = $this->auth;
        $criteria = array(
            'm_kategori_skpd.id' => $id
        );
        $this->load->model('kategori_skpd_model', 'kategori_skpd');
        $result = $this->kategori_skpd->get_many('', $criteria)->row_array();
        //[debug]echo get_instance()->db->last_query();
        $result_keys = array_keys($result);
        foreach ($result_keys as $result_key) {
            $data[$result_key] = $result[$result_key];
        }

        $data['tools'] = array(
            'master/kategori_skpd' => 'Back',
            'master/kategori_skpd/' . $id . '/edit' => 'Edit',
            'master/kategori_skpd/' . $id . '/delete' => 'Delete'
        );
        $data['page_title'] = 'Detail Kategori SKPD';
        $this->load->view('master/kategori_skpd_info', $data);
    }

    function delete() {
        $id = $this->uri->segment(3);
        $this->crud->use_table('m_kategori_skpd');
        $criteria = array('id' => $id);
        $data_in = array(
            'active' => 0,
            'modified_on' => logged_info()->on,
            'modified_by' => logged_info()->by
        );
        $this->crud->update($criteria, $data_in);
        redirect('master/desa');
    }

    function create() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $master_url = 'master/kategori_skpd/';

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('kategori_skpd_create') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('m_kategori_skpd');
            $data_in = array(
                'kategori_skpd' => strtoupper($this->input->post('kategori_skpd')),
                'keterangan' => $this->input->post('keterangan'),
                'created_on' => date($this->config->item('log_date_format')),
                'created_by' => logged_info()->on
            );
            $created_id = $this->crud->create($data_in);
            redirect('master/kategori_skpd/' . $created_id . '/info');
        }
        $data['action_url'] = $master_url . __FUNCTION__;
        $data['page_title'] = 'Create Kategori SKPD';
        $data['tools'] = array(
            'master/kategori_skpd' => 'Back'
        );

        $this->load->model('kategori_skpd_model', 'kategori_skpd');
        $data = array_merge($data, $this->kategori_skpd->set_default()); //merge dengan arr data dengan default
        $this->load->view('master/kategori_skpd_form', $data);
    }

    function edit() {
        $data['auth'] = $this->auth;
        $data['action_type'] = __FUNCTION__;
        $master_url = 'master/kategori_skpd/';
        $id = $this->uri->segment(3);

        $this->load->library(array('form_validation', 'table'));
        $this->load->helper(array('form', 'snippets'));
        $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
        if ($this->form_validation->run('kategori_skpd_update') === FALSE) {
            //don't do anything
        } else {
            $this->crud->use_table('m_kategori_skpd');
            $criteria = array(
                'id' => $id
            );
            $data_in = array(
                'kategori_skpd' => strtoupper($this->input->post('kategori_skpd')),
                'keterangan' => $this->input->post('keterangan'),
                'modified_on' => date($this->config->item('log_date_format')),
                'modified_by' => logged_info()->on
            );
            $this->crud->update($criteria, $data_in);
            redirect('master/kategori_skpd/' . $id . '/info');
        }
        $data['action_url'] = $master_url . $id . '/' . __FUNCTION__;
        $data['page_title'] = 'Update Kategori SKPD';
        $data['tools'] = array(
            'master/kategori_skpd' => 'Back'
        );

        $this->crud->use_table('m_kategori_skpd');
        $kategori_skpd_data = $this->crud->retrieve(array('id' => $id))->row();

        $this->load->model('kategori_skpd_model', 'kategori_skpd');
        $data = array_merge($data, $this->kategori_skpd->set_default()); //merge dengan arr data dengan default
        $data = array_merge($data, (array) $kategori_skpd_data);
        $this->load->view('master/kategori_skpd_form', $data);
    }

    function unique_kategori_skpd($nama_kategori_skpd) {
        $this->crud->use_table('m_kategori_skpd');
        $kategori_skpd = $this->crud->retrieve(array('kategori_skpd' => $nama_kategori_skpd))->row();
        if (sizeof($kategori_skpd) > 0) {
            $this->form_validation->set_message(__FUNCTION__, 'Kategori SKPD sudah terdaftar'); //pakai function karena ini harus sama dengan nama function nya
            return FALSE;
        } else {
            return true;
        }
    }

}

?>
