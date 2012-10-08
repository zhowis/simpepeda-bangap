<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('snippets_helper'));
        $this->load->model('crud_model', 'crud');
    }

    function index() {
        redirect('transaction/musrenbang_usulan');
    }

    function log_date() {
        return date($this->config->item('log_date_format'));
    }

    function _default_pagination_btn() {
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="first">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="last">';
        $config['last_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['next_link'] = 'Next &rarr;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&larr; Previous';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        return $config;
    }

    /*
     * transaction service
     * superzkoss
     * 20 februari 2012
     */

    function number_cast($number) {
        if ($number < 9) {
            return '000' . $number;
        } else if ($number < 99) {
            return '00' . $number;
        } else if ($number < 999) {
            return '0' . $number;
        } else {
            return $number;
        }
    }

    function _update_service_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_service_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_service_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_service_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_service_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_service_year', 'option_value' => $sys_year));
    }

    function _get_service_no() {

        $this->_update_service_no(); //check service no update

        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_service_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_service_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_service_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_service_last_no', 'option_value' => $next_number)); //update last service no

        return $year . $month . $last_no;
    }

    function _create_service() {
        $data = array();
        $this->load->model(array('service_model', 'kendaraan_model', 'peminjam_model', 'bengkel_model', 'option_model'));

        $date = new DateTime();
        $session = $this->session->userdata('logged_in');

        // Assign the query data : header
        $data['id'] = $this->_get_service_no(); //generate nomor service internal & external
        $option_iwl = $this->option_model->get_options('', array('option' => 'internal_workshop_list'))->row_array();
        $option_iwl['internal_workshop_list'] = $option_iwl['option_value'];
        $option_iwl_arr = $option_iwl['internal_workshop_list']; //string(7) "1,2,3,4"
        $resultBengkel = $this->bengkel_model->get_bengkel('', array('bengkel' => $this->input->post('bengkel')))->row_array();
        if (in_array(intval($resultBengkel['id']), array($option_iwl_arr))) {
            $data['service_type'] = 'INTERNAL';
        } else {
            $data['service_type'] = 'EXTERNAL';
        }

        $data['keluhan'] = is_null($this->input->post('keluhan')) ? '' : serialize($this->input->post('keluhan'));

        $result = $this->kendaraan_model->get_kendaraan('', array('m_kendaraan.id' => $this->input->post('kendaraan_id')))->row_array();
        $data['kendaraan_id'] = $this->input->post('kendaraan_id');
        $data['bengkel_id'] = $resultBengkel['id'];
        $data['kilometer'] = $this->input->post('kilometer');
        $data['nip'] = $this->input->post('nip');
        $data['customer'] = strtoupper($this->input->post('customer'));
        $data['pic'] = strtoupper($this->input->post('pic_service'));
        $data['status'] = 'TERIMA';
        $tgl_pelaksanaan = new DateTime($this->input->post('tgl_terima'));
        $data['tgl_pelaksanaan'] = $tgl_pelaksanaan->format('Y-m-d');
        $data['laporan_bengkel'] = $this->input->post('laporan_bengkel');
        $data['note'] = $this->input->post('note');
        $data['lain_lain'] = $this->input->post('lain_lain');
        $data['biaya'] = $this->input->post('biaya');

        $data['created_on'] = $this->log_date();
        $data['created_by'] = $this->auth->logged_in();

        $data['service_category_id'] = $this->input->post('cat_per_id');
        $data['perbaikan_id'] = $this->input->post('per_id');
        $data['teknisi_id'] = $this->input->post('tek_id');

        $data['foc'] = $this->input->post('foc') == 'true' ? 'foc' : 'non_foc';

        $this->service_model->create_service($data);
        redirect('transaction/service');
    }

    function kilometer_check($kilometer) {
        if ($kilometer != 0):
            $this->load->model('kendaraan_model');
            $result = $this->kendaraan_model->get_kendaraan('', array('m_kendaraan.id' => $this->input->post('kendaraan_id')))->row_array();
            $kilometer_terakhir = $result['kilometer_terakhir'];
            if ($kilometer_terakhir > $kilometer || $kilometer_terakhir == $kilometer) {
                $this->form_validation->set_message(__FUNCTION__, 'Kilometer Service Harus Lebih Besar dari Kilometer Akhir!'); //pakai function karena ini harus sama dengan nama function nya
                return FALSE;
            } else {
                return true;
            }
        else:
            return true;

        endif;
    }

    function _update_service($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('service_model', 'kendaraan_model', 'peminjam_model', 'option_model'));

        // Assign the query data : header
        $data['id'] = $id;

        $data['keluhan'] = is_null($this->input->post('keluhan')) ? '' : serialize($this->input->post('keluhan'));

        $data['kilometer'] = $this->input->post('kilometer');
        $data['nip'] = $this->input->post('nip');
        $data['customer'] = strtoupper($this->input->post('customer'));
        $data['pic'] = strtoupper($this->input->post('pic_service'));
        $tgl_pelaksanaan = new DateTime($this->input->post('tgl_terima'));
        $data['tgl_pelaksanaan'] = $tgl_pelaksanaan->format('Y-m-d');
        $data['laporan_bengkel'] = $this->input->post('laporan_bengkel');
        $data['note'] = $this->input->post('note');
        $data['lain_lain'] = $this->input->post('lain_lain');
        $data['biaya'] = $this->input->post('biaya');

        $data['created_on'] = $this->log_date();
        $data['created_by'] = $this->auth->logged_in();

        $data['modified_on'] = $this->log_date();
        $data['modified_by'] = $this->auth->logged_in();

        $data['pk'] = $this->input->post('pk');
        $data['service_id'] = $id;

        $data['part'] = $this->input->post('part');
        $data['part_id'] = $this->input->post('no_part');

        $data['qty'] = $this->input->post('qty');
        $data['price'] = $this->input->post('price');

        $data['service_category_id'] = $this->input->post('cat_per_id');
        $data['perbaikan_id'] = $this->input->post('per_id');
        $data['teknisi_id'] = $this->input->post('tek_id');
        $data['bengkel_luar_id'] = $this->input->post('bek_id');
        $data['pk_perbaikan_detail'] = $this->input->post('pk_perbaikan_detail');

        $status = $this->input->post('status');

        if ($status == '-STATUS-') {
            $data['status'] = 'TERIMA';
        } else {
            $data['status'] = $status;
        }

        if ($this->input->post('status') == 'SELESAI' || $this->input->post('status') == 'BATAL') {
            $data['closed_on'] = $date->format($this->config->item('log_date_format'));
            $data['closed_by'] = $this->auth->logged_in();
        } else {
            $data['closed_on'] = $this->input->post('closed_on');
            $data['closed_by'] = $this->input->post('closed_by');
        }

        $data['reason_cancel'] = $this->input->post('reason_cancel');

        $this->service_model->update_service($data);
        redirect('transaction/service/info/' . $id);
    }

    function _create_service_detail($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('service_model'));
        $session = $this->session->userdata('logged_in');
        // Assign the query data : detail
        $data['service_id'] = $id;
        $data['part_id'] = $this->input->post('no_part');
        $data['qty'] = $this->input->post('qty');
        $data['price'] = $this->input->post('price');

        $data['created_on'] = $this->log_date();
        $data['created_by'] = $this->auth->logged_in();

        $this->service_model->create_service_detail($data);
        redirect('transaction/service');
    }

    function _update_service_detail($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model('service_model');
        $session = $this->session->userdata('logged_in');

        // Assign the query data : detail
        $data['service_id'] = $id;
        $data['part_id'] = $this->input->post('no_part');
        $data['qty'] = $this->input->post('qty');
        $data['price'] = $this->input->post('price');
        $data['pk'] = $this->input->post('pk');

        $data['created_on'] = $this->log_date();
        $data['created_by'] = $this->auth->logged_in();

        $data['modified_on'] = $this->log_date();
        $data['modified_by'] = $this->auth->logged_in();

        $this->service_model->update_service_detail($data);
        redirect('transaction/service/info/' . $id);
    }

    function service($type = NULL, $query_id = 0, $sort_by = 'tgl_terima', $sort_order = 'desc', $offset = 0) {

        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/service/';
        $data['auth'] = $this->auth;

        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table', 'pagination')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');

                //service
                if ($this->form_validation->run('service_create') === FALSE) {
                    //don't do anything
                } else {
                    $this->_create_service();
                }

                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Service sheet';
                $data['tools'] = array(
                    'transaction/service' => '&laquo; Back'
                );

                $this->load->model(array('service_model', 'service_detail_model', 'teknisi_model', 'service_category_model', 'perbaikan_model', 'part_model', 'option_model', 'bengkel_model'));

                //dinamis
                $data['category_options'] = $this->service_category_model->get_service_category();
                $data['perbaikan_options'] = $this->perbaikan_model->get_perbaikan('', array('service_category_id' => $this->input->post('category')));
                $data['teknisi_options'] = $this->teknisi_model->get_teknisi();
                $data['bengkel_options'] = $this->bengkel_model->get_bengkel();
                $data['perbaikan_info_options'] = $this->perbaikan_model->get_perbaikan();

                $data['service_category_options'] = $this->service_category_model->get_service_category();
                $data['part_options'] = $this->part_model->get_part();
                $data['results_service'] = $this->service_model->get_service();
                $data['generate_options'] = $this->service_model->get_last_generate_id();

                $query_array = null;
                $limit = 20;

                $results_service_detail = $this->service_detail_model->search($query_array);
                $data['results_service_detail'] = $results_service_detail;
                $data['num_results_service_detail'] = sizeof($results_service_detail->result());

                $config = array();
                $config['base_url'] = site_url("transaction/service/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results_service_detail'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;
                $data = array_merge($data, $this->service_model->set_default());

                $data['all_keluhan'] = '';

                $data['no_polisi'] = '';
                $data['warna'] = '';
                $data['vehicle_type'] = '';
                $data['jenis'] = '';
                $data['kilometer_terakhir'] = '';
                $data['results'] = '';
                $data['service_category_id'] = '';

                $data['tahun'] = '';
                $data['merk'] = '';
                $data['jenis'] = '';
                $data['no_rangka'] = '';
                $data['no_mesin'] = '';

                $data['perbaikan_id'] = '';
                $data['bengkel'] = '';
                $data['pic_bengkel'] = '';
                $data['pic_service'] = '';
                $data['kota'] = '';
                $data['kota_bengkel'] = '';
                $data['email'] = '';
                $data['telephone1'] = '';

                $option_dfj = $this->option_model->get_options('', array('option' => 'default_workshop_jkt'))->row_array();
                $term = array(
                    'm_bengkel.id' => $option_dfj['option_value']
                );
                $this->load->model('bengkel_model');
                $result = $this->bengkel_model->get_bengkel('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }
                $this->load->view('transaction/service_form', $data);
                break;
            case 'create_detail':
                $id = $this->uri->segment(4);
                $this->_create_service_detail($id);
                break;
            case 'update_detail':
                $id = $this->uri->segment(4);
                $this->_update_service_detail($id);
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table', 'pagination')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_service.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('service_update') === FALSE) {
                    //don't do anything
                } else {
                    $this->_update_service($id);
                }

                $data['page_title'] = 'Update Service';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/transaction' => '&laquo; Back'
                );

                $data['action_url'] = $master_url . $type . '/' . $id;

                $this->load->model(array('service_model', 'service_detail_model', 'teknisi_model', 'service_category_model', 'perbaikan_model', 'part_model', 'option_model', 'bengkel_model'));
                $data['generate_options'] = $this->service_model->get_last_generate_id();

                $result = $this->service_model->get_service('', $term)->row_array();

                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                    $temp_keluhan = unserialize($result['keluhan']);
                }

                if (unserialize($result['keluhan']) === false && unserialize($result['keluhan']) !== 'b:0;') {
                    $data['all_keluhan'] = '';
                } else {
                    $data['all_keluhan'] = $temp_keluhan;
                }

                if (is_array($data['all_keluhan'])) {
                    foreach ($data['all_keluhan'] as $key => $value) {
                        $bengkel = $this->bengkel_model->get_bengkel('', array('m_bengkel.id' => $key))->row_array();
                        $data['all_bengkel'][$key] = isset($bengkel['bengkel']) ? $bengkel['bengkel'] : '';
                    }
                } else {
                    $data['all_bengkel'] = '';
                }

                //dinamis
                $data['category_options'] = $this->service_category_model->get_service_category();
                $data['perbaikan_options'] = $this->perbaikan_model->get_perbaikan('', array('service_category_id' => $this->input->post('category')));
                $data['perbaikan_info_options'] = $this->perbaikan_model->get_perbaikan();
                $data['bengkel_options'] = $this->bengkel_model->q_bengkel_luar();
                $data['teknisi_options'] = $this->teknisi_model->get_teknisi();
                $data['service_category_options'] = $this->service_category_model->get_service_category();
                $data['part_options'] = $this->part_model->get_part();

                $limit = 20;
                $query_array = array(
                    'service_id' => $id
                );

                $results_service_perbaikan_detail = $this->service_model->search_by_perbaikan($query_array);
                $data['results_service_perbaikan_detail'] = $results_service_perbaikan_detail;

                $results_service_perbaikan_detail_bengkel = $this->service_model->search_by_perbaikan($query_array);
                $data['results_service_perbaikan_detail_bengkel'] = $results_service_perbaikan_detail_bengkel;

                $results_service_perbaikan_detail_lain = $this->service_model->search_by_perbaikan_lain($query_array);
                $data['results_service_perbaikan_detail_lain'] = $results_service_perbaikan_detail_lain;

                $results_service_perbaikan_bengkel_luar = $this->service_model->search_by_perbaikan_bengkel_luar($query_array);
                $data['results_service_perbaikan_bengkel_luar'] = $results_service_perbaikan_bengkel_luar;
                $data['num_results_service_perbaikan_bengkel_luar'] = sizeof($results_service_perbaikan_bengkel_luar->result());

                $results_service_detail = $this->service_detail_model->search($query_array);
                $data['results_service_detail'] = $results_service_detail;
                $data['num_results_service_detail'] = sizeof($results_service_detail->result());

                $results_service_detail_luar = $this->service_detail_model->search_luar($query_array);
                $data['results_service_detail_luar'] = $results_service_detail_luar;
                $data['num_results_service_detail_luar'] = sizeof($results_service_detail_luar->result());

                //teknisi
                $service_id = $this->uri->segment(4);
                $service_detail = $this->service_model->get_service('', array('t_service.id' => $service_id))->row_array();
                $data['teknisi_id'] = ( isset($service_detail) && sizeof($service_detail) > 0) ? $service_detail['teknisi_id'] : '';
                $data['results_service'] = $this->service_model->get_service();

                //echo var_dump($data['teknisi']);
                $config = array();
                $config['base_url'] = site_url("transaction/service/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results_service_detail'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;
                $data['service_details'] = $this->service_model->service_details($id);
                $data['num_service_detail'] = $this->service_model->count_service_detail($id);

                $data['service_category_id'] = '';

                $pemilik = $result['pemilik'];

                //check foc or non foc
                $this->crud->use_table('m_options');
                $foc_company = $this->crud->retrieve(array('option' => 'foc_company'), 'option_value')->row()->option_value;

                $data['foc'] = 'non_foc';
                if ($foc_company == $pemilik)
                    $data['foc'] = 'foc';

                $option_iwl = $this->option_model->get_options('', array('option' => 'internal_workshop_list'))->row_array();
                $data['internal_workshop_list'] = $option_iwl['option_value'];


                $this->load->view('transaction/service_form', $data);
                break;
            case 'delete_service_detail':
                $this->crud->use_table('t_service_detail');
                $criteria = array('id' => $this->input->post('pk'));
                $data_in = array(
                    'active' => 0
                );
                $this->crud->update($criteria, $data_in);
                //[debug]
                echo $this->db->last_query();
                break;
            case 'delete_perbaikan':
                $this->crud->use_table('t_service_perbaikan_detail');
                $criteria = array('id' => $this->input->post('pk_perbaikan_detail'));
                $data_in = array(
                    'active' => 0
                );
                $this->crud->update($criteria, $data_in);
                //[debug] echo $this->db->last_query();
                break;
            case 'print_int':
                $id = $this->uri->segment(4);
                $term = array(
                    't_service.id' => $id
                );
                $this->load->model(array('service_model', 'service_detail_model', 'kendaraan_model', 'perbaikan_model'));
                $result = $this->service_model->get_service('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                if ($data['perbaikan_id'] != '') {
                    $perbaikan_arr = explode(",", $data['perbaikan_id']);
                    if (is_array($perbaikan_arr) && sizeof($perbaikan_arr) > 0) {
                        foreach ($perbaikan_arr as $key => $perbaikan_id) {
                            $perbaikan = $this->perbaikan_model->get_perbaikan('', array('m_perbaikan.id' => $perbaikan_id))->row_array();
                            if ($perbaikan['perbaikan'] != '')
                                $data['all_perbaikan'][] = $perbaikan['perbaikan'];
                        }
                    }
                }

                $array_hari = array(1 => "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
                $hari = $array_hari[date("N")];
                $tanggal = date("j");
                $array_bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                $bulan = $array_bulan[date("n")];
                $tahun = date("Y");
                $jam = date("H");
                $menit = date("i");
                $detik = date("s");
                $data['date_now'] = "Jakarta" . ",&nbsp;" . $tanggal . "&nbsp;" . $bulan . "&nbsp;" . $tahun . "&nbsp;" . $jam . ":" . $menit . ":" . $detik;

                $query_array = array(
                    'service_id' => $id
                );

                $results_service_perbaikan_detail = $this->service_model->search_by_perbaikan($query_array);
                $data['results_service_perbaikan_detail'] = $results_service_perbaikan_detail;

                $results_service_perbaikan_detail_lain = $this->service_model->search_by_perbaikan_lain($query_array);
                $data['results_service_perbaikan_detail_lain'] = $results_service_perbaikan_detail_lain;

                $this->load->view('transaction/service_int_print', $data);
                break;
            case 'print_int_blank':
                $id = $this->uri->segment(4);
                $term = array(
                    't_service.id' => $id
                );
                $this->load->model(array('service_model', 'service_detail_model', 'kendaraan_model', 'perbaikan_model'));
                $result = $this->service_model->get_service('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                if ($data['perbaikan_id'] != '') {
                    $perbaikan_arr = explode(",", $data['perbaikan_id']);
                    if (is_array($perbaikan_arr) && sizeof($perbaikan_arr) > 0) {
                        foreach ($perbaikan_arr as $key => $perbaikan_id) {
                            $perbaikan = $this->perbaikan_model->get_perbaikan('', array('m_perbaikan.id' => $perbaikan_id))->row_array();
                            if ($perbaikan['perbaikan'] != '')
                                $data['all_perbaikan'][] = $perbaikan['perbaikan'];
                        }
                    }
                }

                $array_hari = array(1 => "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
                $hari = $array_hari[date("N")];
                $tanggal = date("j");
                $array_bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                $bulan = $array_bulan[date("n")];
                $tahun = date("Y");
                $jam = date("H");
                $menit = date("i");
                $detik = date("s");
                $data['date_now'] = "Jakarta" . ",&nbsp;" . $tanggal . "&nbsp;" . $bulan . "&nbsp;" . $tahun . "&nbsp;" . $jam . ":" . $menit . ":" . $detik;

                $query_array = array(
                    'service_id' => $id
                );

                $results_service_perbaikan_detail = $this->service_model->search_by_perbaikan($query_array);
                $data['results_service_perbaikan_detail'] = $results_service_perbaikan_detail;

                $this->load->view('transaction/service_int_print_other', $data);
                break;
            case 'print_ext':
                $id = $this->uri->segment(4);
                $term = array(
                    't_service.id' => $id
                );

                $this->load->model(array('service_model', 'service_detail_model', 'kendaraan_model', 'perbaikan_model', 'option_model'));
                $result = $this->service_model->get_service('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $query_array = array(
                    'service_id' => $id
                );

                $results_service_perbaikan_detail = $this->service_model->search_by_perbaikan($query_array);
                $data['results_service_perbaikan_detail'] = $results_service_perbaikan_detail;

                $results_service_perbaikan_detail_lain = $this->service_model->search_by_perbaikan_lain($query_array);
                $data['results_service_perbaikan_detail_lain'] = $results_service_perbaikan_detail_lain;

                $results_service_detail = $this->service_detail_model->search($query_array);
                $data['results_service_detail'] = $results_service_detail;
                $data['num_results_service_detail'] = sizeof($results_service_detail->result());

                $array_hari = array(1 => "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
                $hari = $array_hari[date("N")];
                $tanggal = date("j");
                $array_bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                $bulan = $array_bulan[date("n")];
                $tahun = date("Y");
                $jam = date("H");
                $menit = date("i");
                $detik = date("s");
                $data['date_now'] = "Jakarta" . ",&nbsp;" . $tanggal . "&nbsp;" . $bulan . "&nbsp;" . $tahun . "&nbsp;" . $jam . ":" . $menit . ":" . $detik;

                $option_operational = $this->option_model->get_options('', array('option' => 'operational_workhshop_garage'))->row_array();
                $data['operational_workhshop_garage'] = $option_operational['option_value'];
                $operational_workshop_manager = $this->option_model->get_options('', array('option' => 'operational_workshop_manager'))->row_array();
                $data['operational_workshop_manager'] = $operational_workshop_manager['option_value'];
                $operational_supervisor_workshop = $this->option_model->get_options('', array('option' => 'operational_supervisor_workshop'))->row_array();
                $data['operational_supervisor_workshop'] = $operational_supervisor_workshop['option_value'];

                $this->load->view('transaction/service_ext_print', $data);
                break;
            case 'print_ext_other':
                $id = $this->uri->segment(4);
                $id_ = $this->uri->segment(5);

                $term = array(
                    't_service.id' => $id
                );

                $this->load->model(array('service_model', 'service_detail_model', 'kendaraan_model', 'perbaikan_model', 'option_model', 'bengkel_model'));
                $result = $this->service_model->get_service('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $temp_keluhan = unserialize($result['keluhan']);
                $key = $id_; //var_dump($temp_keluhan[$key]);
                $data['key_bengkel'] = $key;

                if (unserialize($result['keluhan']) === false && unserialize($result['keluhan']) !== 'b:0;') {
                    $data['all_keluhan'] = '';
                } else {
                    $data['all_keluhan'] = $temp_keluhan[$key];
                }

                $query_array_by_bengkel = array(
                    'service_id' => $id,
                    't_service_perbaikan_detail.bengkel_luar_id' => $id_,
                    't_service_perbaikan_detail.active' => 1
                );

                $result = $this->service_model->get_service_by_bengkel('', $query_array_by_bengkel)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $query_array = array(
                    'service_id' => $id,
                    'service_perbaikan_id' => $id_
                );

                $results_service_perbaikan_detail = $this->service_model->search_by_perbaikan($query_array);
                $data['results_service_perbaikan_detail'] = $results_service_perbaikan_detail;

                $results_service_perbaikan_detail_lain = $this->service_model->search_by_perbaikan_lain($query_array);
                $data['results_service_perbaikan_detail_lain'] = $results_service_perbaikan_detail_lain;

                $results_service_detail = $this->service_detail_model->search($query_array);
                $data['results_service_detail'] = $results_service_detail;
                $data['num_results_service_detail'] = sizeof($results_service_detail->result());

                $array_hari = array(1 => "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
                $hari = $array_hari[date("N")];
                $tanggal = date("j");
                $array_bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                $bulan = $array_bulan[date("n")];
                $tahun = date("Y");
                $jam = date("H");
                $menit = date("i");
                $detik = date("s");
                $data['date_now'] = "Jakarta" . ",&nbsp;" . $tanggal . "&nbsp;" . $bulan . "&nbsp;" . $tahun . "&nbsp;" . $jam . ":" . $menit . ":" . $detik;

                $option_operational = $this->option_model->get_options('', array('option' => 'operational_workhshop_garage'))->row_array();
                $data['operational_workhshop_garage'] = $option_operational['option_value'];
                $operational_workshop_manager = $this->option_model->get_options('', array('option' => 'operational_workshop_manager'))->row_array();
                $data['operational_workshop_manager'] = $operational_workshop_manager['option_value'];
                $operational_supervisor_workshop = $this->option_model->get_options('', array('option' => 'operational_supervisor_workshop'))->row_array();
                $data['operational_supervisor_workshop'] = $operational_supervisor_workshop['option_value'];

                $this->load->view('transaction/service_ext_print_other', $data);
                break;
            case 'search' :
                $query_array = array(
                    'status' => $this->input->post('status'),
                    'no_polisi' => $this->input->post('no_polisi'),
                    'no_service' => $this->input->post('no_service'),
                    'tgl_start' => $this->input->post('tgl_start'),
                    'tgl_end' => $this->input->post('tgl_end')
                );

                $query_id = $this->input->save_query($query_array);

                redirect("transaction/service/view/$query_id");
                break;
            default:
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model(array('service_model', 'option_model', 'bengkel_model'));
                $this->input->load_query($query_id);

                $query_array = array(
                    'status' => $this->input->get('status'),
                    'no_polisi' => $this->input->get('no_polisi'),
                    'no_service' => $this->input->get('no_service'),
                    'tgl_pelaksanaan' => $this->input->get('tgl_pelaksanaan'),
                    'tgl_start' => $this->input->get('tgl_start'),
                    'tgl_end' => $this->input->get('tgl_end')
                );
                $results = $this->service_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

                // pagination
                $config = array();
                $config['base_url'] = site_url("transaction/service/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Service';

                if ($this->auth->has_capability('t_modified_service_action')) {
                    $data['tools'] = array(
                        'transaction/service/create' => 'New Service'
                    );
                }

                $option_iwl = $this->option_model->get_options('', array('option' => 'internal_workshop_list'))->row_array();
                $data['internal_workshop_list'] = $option_iwl['option_value'];

                $data['tgl_start'] = '';
                $data['tgl_end'] = '';
                $this->load->view('transaction/service', $data);
                break;
        }
    }

    /*
     * transaction payment
     * superzkoss
     * 23 februari 2012
     */

    function _update_payment_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_payment_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_payment_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_payment_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_payment_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_payment_year', 'option_value' => $sys_year));
    }

    function _get_payment_no() {
        $this->_update_payment_no();

        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_payment_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_payment_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_payment_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_payment_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function formatdate($format, $stringtime) {//tar dipindahin ke lib aja
        if (isset($stringtime) && $stringtime != '') {
            $temp = explode('-', $stringtime);
            return date($format, mktime(0, 0, 0, (int) ($temp[1]), (int) ($temp[2]), (int) ($temp[0])));
        }
    }

    function _create_payment() {
        $data = array();
        $date = new DateTime();
        $format = 'j-m-Y';
        $this->load->model(array('payment_model', 'service_bureau_model'));
        $resultBureau = $this->service_bureau_model->get_service_bureau('', array('bureau' => $this->input->post('bureau')))->row_array();

        // Assign the query data : header
        $data['id'] = $this->_get_payment_no(); //generate nomor service
        $tgl_payment = new DateTime($this->input->post('tgl_payment'));
        $data['tgl_payment'] = $tgl_payment->format('Y-m-d');
        $data['bureau_id'] = $resultBureau['id'];
        $data['description'] = $this->input->post('description');

        // Assign the query data : detail
        $data['payment_id'] = $this->_get_payment_no();
        $data['no_polisi'] = $this->input->post('no_polisi');
        $data['jenis_transaksi'] = $this->input->post('jenis_transaksi');
        $data['tgl_lama'] = $this->input->post('tgl_lama');
        $data['tgl_baru'] = $this->input->post('tgl_baru');
        $data['total'] = $this->input->post('total');
        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();


        $this->payment_model->create_payment($data);
        redirect('transaction/payment');
    }

    function _update_payment($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('payment_model', 'service_bureau_model'));
        $resultBureau = $this->service_bureau_model->get_service_bureau('', array('bureau' => $this->input->post('bureau')))->row_array();

// Assign the query data : header
        $data['payment_id'] = $id;
        $tgl_payment = new DateTime($this->input->post('tgl_payment'));
        $data['tgl_payment'] = $tgl_payment->format('Y-m-d');
        $data['bureau_id'] = $resultBureau['id'];
        $data['description'] = $this->input->post('description');

// Assign the query data : detail
        $data['pk'] = $this->input->post('pk');
        $data['no_polisi'] = $this->input->post('no_polisi');
        $data['jenis_transaksi'] = $this->input->post('jenis_transaksi');
        $data['tgl_lama'] = $this->input->post('tgl_lama');
        $data['tgl_baru'] = $this->input->post('tgl_baru');
        $data['total'] = $this->input->post('total');
        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();


        $this->payment_model->update_payment($data);
        redirect('transaction/payment');
    }

    function payment($type = NULL, $query_id = 0, $sort_by = 'tgl_transaksi', $sort_order = 'desc', $offset = 0) {

        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/payment/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table', 'pagination')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('payment_create') === FALSE) {
//don't do anything
                } else {
                    $this->_create_payment();
                }

                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Payment';
                $data['tools'] = array(
                    'transaction/payment' => '&laquo; Back'
                );

                $this->load->model(array('payment_model', 'payment_detail_model'));
                $limit = 20;
                $query_array = null;
                $results_payment_detail = $this->payment_detail_model->search($query_array, $limit, $offset, $sort_by, $sort_order);
                $data['results_payment_detail'] = $results_payment_detail['results'];
                $data['num_results_payment_detail'] = $results_payment_detail['num_rows'];
                $data['generate_options'] = $this->payment_model->get_last_generate_id();

                $config = array();
                $config['base_url'] = site_url("transaction/payment/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results_payment_detail'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;
                $data = array_merge($data, $this->payment_model->set_default());
                $data = array_merge($data, $this->payment_detail_model->set_default());

                $data['no_payment'] = $this->_get_payment_no();
                $data['bureau'] = '';

                $this->load->view('transaction/payment_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table', 'pagination')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_payment.id' => $id
                );

                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('payment_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_payment($id);
                }

                $data['page_title'] = 'Update Payment';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/payment' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;
                $this->load->model(array('payment_model', 'payment_detail_model'));
                $result = $this->payment_model->get_payment('', $term)->row_array();

                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $limit = 20;
                $query_array = array(
                    'payment_id' => $id
                );

                $results_payment_detail = $this->payment_detail_model->search($query_array, $limit, $offset, $sort_by, $sort_order);
                $data['results_payment_detail'] = $results_payment_detail['results'];
                $data['num_results_payment_detail'] = $results_payment_detail['num_rows'];

                $config = array();
                $config['base_url'] = site_url("transaction/payment/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results_payment_detail'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['no_payment'] = '';

                $this->load->view('transaction/payment_form', $data);
                break;
            case 'delete_payment_detail':
                $this->crud->use_table('t_payment_detail');
                $criteria = array('id' => $this->input->post('pk'));
                $data_in = array(
                    'active' => 0
                );
                $this->crud->update($criteria, $data_in);
                //[debug]
                echo $this->db->last_query();
                break;
            case 'search' :
                $query_array = array(
                    'no_payment' => $this->input->post('no_payment'),
                    'tgl_payment' => $this->input->post('tgl_payment'),
                    'no_ref' => $this->input->post('no_ref'),
                    'no_polisi' => $this->input->post('no_polisi')
                );
                $query_id = $this->input->save_query($query_array);
                redirect("transaction/payment/view/$query_id");
            case 'detail' :
                $this->load->helper(array('form'));
                break;
            default:
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model('payment_model');
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_payment' => $this->input->get('no_payment'),
                    'tgl_payment' => $this->input->get('tgl_payment'),
                    'no_ref' => $this->input->get('no_ref'),
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $results = $this->payment_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/payment/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Payment';
                $data['tools'] = array(
                    'transaction/payment/create' => 'New Payment Sheet'
                );

                $data = array_merge($data, $this->payment_model->set_default()); //merge dengan arr data dengan default

                $this->load->view('transaction/payment', $data);
                break;
        }
    }

    /*
     * transaction peminjaman
     * superzkoss
     * 25 februari 2012
     */

    function _update_peminjaman_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_peminjaman_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_peminjaman_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_peminjaman_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_peminjaman_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_peminjaman_year', 'option_value' => $sys_year));
    }

    function _get_peminjaman_no() {

        $this->_update_peminjaman_no();
        $this->load->model('peminjaman_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_peminjaman_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_peminjaman_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_peminjaman_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_peminjaman_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _update_available_status_kendaraan() {
        $data = array();
        $this->load->model('kendaraan_model');
// Assign the query data
        $result = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $data['id'] = $result['id'];
        $data['status_kendaraan_id'] = 1;
        $this->kendaraan_model->update_kendaraan($data);
    }

    function _update_rent_status_kendaraan() {
        $data = array();
        $this->load->model('kendaraan_model');
// Assign the query data
        $result = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $data['id'] = $result['id'];
        $data['status_kendaraan_id'] = 2;
        $this->kendaraan_model->update_kendaraan($data);
    }

    function _update_sold_status_kendaraan() {
        $data = array();
        $this->load->model('kendaraan_model');
// Assign the query data
        $result = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $data['id'] = $result['id'];
        $data['status_kendaraan_id'] = 3;
        $this->kendaraan_model->update_kendaraan($data);
    }

    function _create_peminjaman() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('peminjaman_model', 'kendaraan_model', 'peminjam_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultPeminjam = $this->peminjam_model->get_peminjam('', array('nip' => $this->input->post('nip')))->row_array();

// Assign the query data
        $data['id'] = $this->_get_peminjaman_no();
        $tgl_peminjaman = new DateTime($this->input->post('tgl_peminjaman'));
        $data['tgl_peminjaman'] = $tgl_peminjaman->format('Y-m-d');
        $tgl_pemakaian = new DateTime($this->input->post('tgl_pemakaian'));
        $data['tgl_pemakaian'] = $tgl_pemakaian->format('Y-m-d');
        $data['tujuan'] = $this->input->post('tujuan');
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['peminjam_id'] = $resultPeminjam['id'];

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $this->peminjaman_model->create_peminjaman($data);
        redirect('transaction/peminjaman');
    }

    function _update_peminjaman($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('peminjaman_model', 'kendaraan_model', 'peminjam_model'));

        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultPeminjam = $this->peminjam_model->get_peminjam('', array('nip' => $this->input->post('nip')))->row_array();

// Assign the query data
        $data['id'] = $id;
        $tgl_peminjaman = new DateTime($this->input->post('tgl_peminjaman'));
        $data['tgl_peminjaman'] = $tgl_peminjaman->format('Y-m-d');
        $tgl_pemakaian = new DateTime($this->input->post('tgl_pemakaian'));
        $data['tgl_pemakaian'] = $tgl_pemakaian->format('Y-m-d');
        $data['tujuan'] = $this->input->post('tujuan');
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['peminjam_id'] = $resultPeminjam['id'];

        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();
        $this->peminjaman_model->update_peminjaman($data);
        redirect('transaction/peminjaman');
    }

    function peminjaman($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {

        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/peminjaman/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('peminjaman_create') === FALSE) {
//don't do anything
                } else {
                    $this->_update_rent_status_kendaraan();
                    $this->_create_peminjaman();
                }

                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Peminjaman';
                $data['tools'] = array(
                    'transaction/peminjaman' => '&laquo; Back'
                );

                $this->load->model('peminjaman_model');
                $data['generate_options'] = $this->peminjaman_model->get_last_generate_id();
                $data = array_merge($data, $this->peminjaman_model->set_default()); //merge dengan arr data dengan default
                //reset karena bukan field asli
                $vehicle_ID = $this->uri->segment(4);
                if (!empty($vehicle_ID)):
                    require_once('vehicle.php');
                    $vehicle = new vehicle();
                    $data = (array) $vehicle->complete_info_by_id() + (array) $data;
                    $data['nip'] = '';
                    $data['peminjam'] = '';
                    $data['company'] = '';
                    $data['jabatan'] = '';
                    $data['department'] = '';
                else:
                    $data['nip'] = '';
                    $data['peminjam'] = '';
                    $data['lokasi'] = '';
                    $data['company'] = '';
                    $data['jabatan'] = '';
                    $data['department'] = '';
                    $data['no_polisi'] = '';
                    $data['jenis'] = '';
                    $data['warna'] = '';
                    $data['bahan_bakar'] = '';
                    $data['kapasitas_mesin'] = '';
                    $data['tahun'] = '';
                    $data['merk'] = '';
                    $data['no_rangka'] = '';
                    $data['no_mesin'] = '';
                endif;

                $this->load->view('transaction/peminjaman_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_peminjaman.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('peminjaman_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_peminjaman($id);
                }

                $data['page_title'] = 'Update Peminjaman';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/peminjaman' => '&laquo; Back'
                );


                $data['action_url'] = $master_url . $type . '/' . $id;
                $this->load->model(array('peminjaman_model'));
                $result = $this->peminjaman_model->get_peminjaman('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $this->load->view('transaction/peminjaman_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'suggestion':
                $this->load->model('peminjaman_model');
                $terms = array(
                    'no_peminjaman' => $this->input->get('no_peminjaman'),
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $this->peminjaman_model->suggestion($terms);

                break;
            case 'suggestion_no_polisi':
                $this->load->model('peminjaman_model');
                $terms = array(
                    'no_peminjaman' => $this->input->get('no_peminjaman'),
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $this->peminjaman_model->suggestion_ext($terms);

                break;
            case 'search' :
                $query_array = array(
                    'tgl_peminjaman' => $this->input->post('tgl_peminjaman'),
                    'tgl_pemakaian' => $this->input->post('tgl_pemakaian'),
                    'no_polisi' => $this->input->post('no_polisi')
                );

                $query_id = $this->input->save_query($query_array);

                redirect("transaction/peminjaman/view/$query_id");
            case 'suggestion':
                $this->load->model('peminjaman_model');
                $no_pengembalian = $this->input->get('no_pengembalian');
                $no_polisi = $this->input->get('no_polisi');
                $terms = array(
                    'no_pengembalian' => $no_pengembalian,
                    'no_polisi' => $no_polisi
                );
                $this->pengembalian_model->suggestion($terms);
                break;
            case 'detail' :
                $this->load->helper(array('form'));
                break;
            default:
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model(array('peminjaman_model'));
                $this->input->load_query($query_id);

                $query_array = array(
                    'tgl_peminjaman' => $this->input->get('tgl_peminjaman'),
                    'tgl_pemakaian' => $this->input->get('tgl_pemakaian'),
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $results = $this->peminjaman_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/peminjaman/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Peminjaman';
                $data['tools'] = array(
                    'transaction/peminjaman/create' => 'New Peminjaman Sheet'
                );

                $data = array_merge($data, $this->peminjaman_model->set_default()); //merge dengan arr data dengan default

                $data['no_polisi'] = '';

                $this->load->view('transaction/peminjaman', $data);
                break;
        }
    }

    /*
     * transaction pengembalian
     * superzkoss
     * 27 februari 2012
     */

    function _update_pengembalian_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_pengembalian_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_pengembalian_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_pengembalian_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_pengembalian_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_pengembalian_year', 'option_value' => $sys_year));
    }

    function _get_pengembalian_no() {

        $this->_update_pengembalian_no();
        $this->load->model('pengembalian_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_pengembalian_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_pengembalian_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_pengembalian_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_pengembalian_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_pengembalian() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('peminjaman_model', 'peminjam_model', 'peminjaman_model', 'kendaraan_model'));

//berdasarkan no pol
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();

// Assign the query data
        $data['id'] = $this->_get_pengembalian_no();
        $data['transaksi_id'] = $this->input->post('no_transaksi');
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['tipe_transaksi'] = $this->input->post('tipe_transaksi');
        $tgl_pengembalian = new DateTime($this->input->post('tgl_pengembalian'));
        $data['tgl_pengembalian'] = $tgl_pengembalian->format('Y-m-d');
        $data['diserahkan_oleh'] = $this->input->post('diserahkan_oleh');
        $data['note'] = $this->input->post('note');

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $this->pengembalian_model->create_pengembalian($data);
        redirect('transaction/pengembalian');
    }

    function _update_pengembalian($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('peminjaman_model', 'peminjam_model', 'peminjaman_model', 'kendaraan_model'));

        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $row_peminjaman = $this->peminjaman_model->get_peminjaman('', array('t_peminjaman.id' => $data['peminjaman_id']))->row_array();

// Assign the query data
        $data['id'] = $id;
        $data['transaksi_id'] = $this->input->post('no_transaksi');
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['tipe_transaksi'] = $this->input->post('tipe_transaksi');
        $tgl_pengembalian = new DateTime($this->input->post('tgl_pengembalian'));
        $data['tgl_pengembalian'] = $tgl_pengembalian->format('Y-m-d');
        $data['diserahkan_oleh'] = $this->input->post('diserahkan_oleh');
        $data['note'] = $this->input->post('note');

        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();
        $this->pengembalian_model->update_pengembalian($data);
        redirect('transaction/pengembalian');
    }

    function pengembalian($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {

        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/pengembalian/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));

                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('pengembalian_create') === FALSE) {
//don't do anything
                } else {
//$this->_update_available_status_kendaraan();
                    $this->_create_pengembalian();
                }

                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Pengembalian';
                $data['tools'] = array(
                    'transaction/pengembalian' => '&laquo; Back'
                );

                $this->load->model(array('pengembalian_model', 'peminjam_model', 'kendaraan_model', 'peminjaman_model'));
                $data['pemilik'] = $this->peminjam_model->get_peminjam();
                $data['kendaraan'] = $this->kendaraan_model->get_kendaraan();
                $data['generate_options'] = $this->pengembalian_model->get_last_generate_id();

                $data['no_transaksi'] = '';

                $data['no_polisi'] = '';
                $data['jenis'] = '';
                $data['warna'] = '';
                $data['bahan_bakar'] = '';
                $data['kapasitas_mesin'] = '';
                $data['tahun'] = '';
                $data['kilometer_terakhir'] = '';

                $data['nip'] = '';
                $data['peminjam'] = '';
                $data['tgl_peminjaman'] = '';
                $data['tgl_pemakaian'] = '';
                $data['tujuan'] = '';

                $data['no_polisi_hide'] = '';
                $data['no_transaksi_hide'] = '';

                $data = array_merge($data, $this->pengembalian_model->set_default()); //merge dengan arr data dengan default

                $this->load->view('transaction/pengembalian_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_pengembalian.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('pengembalian_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_pengembalian($id);
                }

                $data['page_title'] = 'Update Pengembalian';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/pengembalian' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;
                $this->load->model(array('pengembalian_model'));

                $result = $this->pengembalian_model->get_pengembalian('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $data['no_transaksi'] = '';
                $data['nip'] = '';
                $data['peminjam'] = '';

                $this->load->view('transaction/pengembalian_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'search' :
                $query_array = array(
                    'tgl_pengembalian' => $this->input->post('tgl_pengembalian'),
                    'no_pengembalian' => $this->input->post('no_pengembalian'),
                    'no_polisi' => $this->input->post('no_polisi')
                );
                $query_id = $this->input->save_query($query_array);
                redirect("transaction/pengembalian/view/$query_id");
            case 'detail' :
                $this->load->helper(array('form'));
                break;
            case 'suggestion':
                $this->load->model('pengembalian_model');
                $no_pengembalian = $this->input->get('no_pengembalian');
                $no_polisi = $this->input->get('no_polisi');
                $terms = array(
                    'no_pengembalian' => $no_pengembalian,
                    'no_polisi' => $no_polisi
                );
                $this->pengembalian_model->suggestion($terms);
                break;
            default:
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model(array('pengembalian_model'));
                $this->input->load_query($query_id);

                $query_array = array(
                    'tgl_pengembalian' => $this->input->get('tgl_pengembalian'),
                    'no_pengembalian' => $this->input->get('no_pengembalian'),
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $results = $this->pengembalian_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/pengembalian/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Pengembalian';
                $data['tools'] = array(
                    'transaction/pengembalian/create' => 'New Pengembalian Sheet'
                );

                $data = array_merge($data, $this->pengembalian_model->set_default()); //merge dengan arr data dengan default

                $data['no_peminjaman'] = '';
                $data['tgl_peminjaman'] = '';
                $data['no_polisi'] = '';

                $this->load->view('transaction/pengembalian', $data);
                break;
        }
    }

    function _update_surat_kuasa_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_surat_kuasa_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_surat_kuasa_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_surat_kuasa_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_surat_kuasa_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_surat_kuasa_year', 'option_value' => $sys_year));
    }

    function _get_surat_kuasa_no() {

        $this->_update_surat_kuasa_no();
        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_surat_kuasa_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_surat_kuasa_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_surat_kuasa_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_surat_kuasa_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_surat_kuasa() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('surat_kuasa_model', 'kendaraan_model', 'company_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultCompany = $this->company_model->get_company('', array('company' => $this->input->post('company')))->row_array();

// Assign the query data
        $data['id'] = $this->_get_surat_kuasa_no();
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['company_id'] = $resultCompany['id'];
        $data['jenis_surat_kuasa'] = $this->input->post('jenis_surat_kuasa');

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $this->surat_kuasa_model->create_surat_kuasa($data);
        redirect('transaction/surat_kuasa');
    }

    function _update_surat_kuasa($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('surat_kuasa_model', 'kendaraan_model', 'company_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultCompany = $this->company_model->get_company('', array('company' => $this->input->post('company')))->row_array();


// Assign the query data
        $data['id'] = $id;
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['company_id'] = $resultCompany['id'];
        $data['jenis_surat_kuasa'] = $this->input->post('jenis_surat_kuasa');

        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();

        $this->surat_kuasa_model->update_surat_kuasa($data);
        redirect('transaction/surat_kuasa');
    }

    function surat_kuasa($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/surat_kuasa/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('surat_kuasa_create') === FALSE) {
//don't do anything
                } else {
                    $this->_create_surat_kuasa();
                }
                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Surat Kuasa';
                $data['tools'] = array(
                    'transaction/surat_kuasa' => '&laquo; Back'
                );

                $this->load->model(array('surat_kuasa_model', 'kota_model', 'branch_model'));
                $data['generate_options'] = $this->surat_kuasa_model->get_last_generate_id();

                $data = array_merge($data, $this->surat_kuasa_model->set_default()); //merge dengan arr data dengan default

                $data['no_polisi'] = '';
                $data['jenis'] = '';
                $data['merk'] = '';
                $data['tahun'] = '';
                $data['warna'] = '';
                $data['no_rangka'] = '';
                $data['no_mesin'] = '';
                $data['branch_id'] = '';
                $data['company'] = '';
                $data['alamat'] = '';
                $data['branch'] = '';

                $data['branch_options'] = $this->branch_model->get_branch();

                $this->load->view('transaction/kuasa_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_surat_kuasa.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('surat_kuasa_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_surat_kuasa($id);
                }
                $data['page_title'] = 'Update Surat Kuasa';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/surat_kuasa' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;

                $this->load->model(array('surat_kuasa_model', 'kendaraan_model', 'branch_model'));

                $data['branch_options'] = $this->branch_model->get_branch();

                $result = $this->surat_kuasa_model->get_surat_kuasa('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->get('no_polisi')))->row_array();

                $data['no_polisi'] = $resultKendaraan['no_polisi'];
                $data['kota_id'] = '';

                $this->load->view('transaction/kuasa_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'cetak':
                $id = $this->uri->segment(4);
                $term = array(
                    't_surat_kuasa.id' => $id
                );
                $this->load->model('surat_kuasa_model');
                $result = $this->surat_kuasa_model->get_surat_kuasa('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $array_hari = array(1 => "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
                $hari = $array_hari[date("N")];
                $tanggal = date("j");
                $array_bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                $bulan = $array_bulan[date("n")];
                $tahun = date("Y");
                $data['date_now'] = "Jakarta" . ",&nbsp;" . $tanggal . "&nbsp;" . $bulan . "&nbsp;" . $tahun;

                $this->load->view('transaction/kuasa_cetak', $data);

                break;
            case 'search' :
                $query_array = array(
                    'no_polisi' => $this->input->post('no_polisi')
                );
                $query_id = $this->input->save_query($query_array);
                redirect("transaction/surat_kuasa/view/$query_id");
                break;
            default:
// pagination
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model('surat_kuasa_model');
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_polisi' => $this->input->get('no_polisi'),
                    'jenis_surat_kuasa' => $this->input->get('jenis_surat_kuasa')
                );
                $results = $this->surat_kuasa_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/surat_kuasa/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Surat Kuasa';

                $data['tools'] = array(
                    'transaction/surat_kuasa/create' => 'New Surat Kuasa Sheet'
                );

                $data['no_polisi'] = '';
                $data = array_merge($data, $this->surat_kuasa_model->set_default()); //merge dengan arr data dengan default;

                $this->load->view('transaction/kuasa', $data);
                break;
        }
    }

    function _update_pengambilan_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_pengambilan_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_pengambilan_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_pengambilan_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_pengambilan_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_pengambilan_year', 'option_value' => $sys_year));
    }

    function _get_pengambilan_no() {

        $this->_update_pengambilan_no();
        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_pengambilan_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_pengambilan_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_pengambilan_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_pengambilan_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_pengambilan() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('kendaraan_model', 'peminjam_model', 'pengambilan_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultPeminjam = $this->peminjam_model->get_peminjam('', array('nip' => $this->input->post('nip')))->row_array();

// Assign the query data
        $data['id'] = $this->_get_pengambilan_no();
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['peminjam_id'] = $resultPeminjam['id'];
        $tgl_pengambilan = new DateTime($this->input->post('tgl_pengambilan'));
        $data['tgl_pengambilan'] = $tgl_pengambilan->format('Y-m-d');

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $this->pengambilan_model->create_pengambilan($data);
        redirect('transaction/pengambilan');
    }

    function _update_pengambilan($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('kendaraan_model', 'peminjam_model', 'pengambilan_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultPeminjam = $this->peminjam_model->get_peminjam('', array('nip' => $this->input->post('nip')))->row_array();

// Assign the query data
        $data['id'] = $id;
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['peminjam_id'] = $resultPeminjam['id'];
        $tgl_pengambilan = new DateTime($this->input->post('tgl_pengambilan'));
        $data['tgl_pengambilan'] = $tgl_pengambilan->format('Y-m-d');

        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();

        $this->pengambilan_model->update_pengambilan($data);
        redirect('transaction/pengambilan');
    }

    function pengambilan($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {

        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/pengambilan/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('pengambilan_create') === FALSE) {
//don't do anything
                } else {
                    $this->_create_pengambilan();
                }

                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Pengambilan';
                $data['tools'] = array(
                    'transaction/pengambilan' => '&laquo; Back'
                );

                $this->load->model('pengambilan_model');
                $data['generate_options'] = $this->pengambilan_model->get_last_generate_id();
                $data = array_merge($data, $this->pengambilan_model->set_default()); //merge dengan arr data dengan default
                //reset karena bukan field asli

                $vehicle_ID = $this->uri->segment(4);
                if (!empty($vehicle_ID)):
                    require_once('vehicle.php');
                    $vehicle = new vehicle();
                    $data = (array) $vehicle->complete_info_by_id() + (array) $data;
                    $data['nip'] = '';
                    $data['peminjam'] = '';
                    $data['company'] = '';
                    $data['jabatan'] = '';
                    $data['department'] = '';
                    $data['merk'] = '';
                    $data['no_rangka'] = '';
                    $data['no_mesin'] = '';
                else:
                    $data['nip'] = '';
                    $data['peminjam'] = '';
                    $data['company'] = '';
                    $data['jabatan'] = '';
                    $data['department'] = '';
                    $data['no_polisi'] = '';
                    $data['jenis'] = '';
                    $data['warna'] = '';
                    $data['bahan_bakar'] = '';
                    $data['kapasitas_mesin'] = '';
                    $data['tahun'] = '';
                    $data['kilometer_terakhir'] = '';
                    $data['no_pengambilan'] = '';
                    $data['merk'] = '';
                    $data['no_rangka'] = '';
                    $data['no_mesin'] = '';
                endif;

                $this->load->view('transaction/pengambilan_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_pengambilan.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('pengambilan_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_pengambilan($id);
                }

                $data['page_title'] = 'Update Pengambilan';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/pengambilan' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;
                $this->load->model(array('pengambilan_model'));
                $result = $this->pengambilan_model->get_pengambilan('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $this->load->view('transaction/pengambilan_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'search' :
                $query_array = array(
                    'no_pengambilan' => $this->input->post('no_pengambilan'),
                    'tgl_pengambilan' => $this->input->post('tgl_pengambilan'),
                    'no_polisi' => $this->input->post('no_polisi'),
                    'peminjam' => $this->input->post('peminjam')
                );
                $query_id = $this->input->save_query($query_array);
                redirect("transaction/pengambilan/view/$query_id");
            case 'detail' :
                $this->load->helper(array('form'));
                break;
            case 'suggestion':
                $this->load->model('pengambilan_model');
                $no_pengambilan = $this->input->get('no_pengambilan');
                $no_polisi = $this->input->get('no_polisi');
                $terms = array(
                    'no_pengambilan' => $no_pengambilan,
                    'no_polisi' => $no_polisi
                );
                $this->pengambilan_model->suggestion($terms);
                break;
            case 'suggestion_no_polisi':
                $this->load->model('pengambilan_model');
                $no_pengambilan = $this->input->get('no_pengambilan');
                $no_polisi = $this->input->get('no_polisi');
                $terms = array(
                    'no_pengambilan' => $no_pengambilan,
                    'no_polisi' => $no_polisi
                );
                $this->pengambilan_model->suggestion_ext($terms);
                break;
            default:
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model(array('pengambilan_model'));
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_pengambilan' => $this->input->get('no_pengambilan'),
                    'tgl_pengambilan' => $this->input->get('tgl_pengambilan'),
                    'no_polisi' => $this->input->get('no_polisi'),
                    'peminjam' => $this->input->get('peminjam')
                );
                $results = $this->pengambilan_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/pengambilan/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Pengambilan';
                $data['tools'] = array(
                    'transaction/pengambilan/create' => 'New Pengambilan Sheet'
                );

                $data = array_merge($data, $this->pengambilan_model->set_default()); //merge dengan arr data dengan default

                $data['no_pengambilan'] = '';
                $data['no_polisi'] = '';
                $data['peminjam'] = '';
                $data['tgl_pengambilan'] = '';

                $this->load->view('transaction/pengambilan', $data);
                break;
        }
    }

    function _update_service_luar_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_service_luar_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_service_luar_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_service_luar_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_service_luar_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_service_luar_year', 'option_value' => $sys_year));
    }

    function _get_service_luar_no() {
//check service no update
        $this->_update_service_no();

        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_service_luar_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_service_luar_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_service_luar_year'))->row()->option_value;

//update last service no
        $this->option_model->update_option(array('option' => 't_service_luar_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_service_luar() {
        $data = array();
        $this->load->model(array('service_luar_model', 'kendaraan_model', 'bengkel_model'));
        $resultBengkel = $this->bengkel_model->get_bengkel_rekanan('', array('bengkel' => $this->input->post('bengkel')))->row_array();
        $result = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();

        $date = new DateTime();
        $session = $this->session->userdata('logged_in');

        $data['id'] = $this->_get_service_no(); //generate nomor service
        $data['kendaraan_id'] = $result['id'];
        $data['bengkel_id'] = $resultBengkel['id'];
        $data['kilometer'] = $this->input->post('kilometer');
        $data['nip'] = $this->input->post('nip');
        $data['customer'] = strtoupper($this->input->post('customer'));
        $data['status'] = 'SELESAI';
        $tgl_pelaksanaan = new DateTime($this->input->post('tgl_pelaksanaan'));
        $data['tgl_pelaksanaan'] = $tgl_pelaksanaan->format('Y-m-d');
        $data['keterangan'] = $this->input->post('keterangan');
        $data['foc'] = $this->input->post('foc');
        $data['biaya'] = $this->input->post('biaya');

        // Assign the query data : detail
        $data['part'] = $this->input->post('part');
        $data['qty'] = $this->input->post('qty');
        $data['price'] = $this->input->post('price');
        $data['total'] = $this->input->post('total');

        $data['service_category_id'] = $this->input->post('cat_per_id');
        $data['perbaikan_id'] = $this->input->post('per_id');
        // $data['teknisi_id'] = $this->input->post('tek_id');

        $data['created_on'] = $this->log_date();
        $data['created_by'] = $this->auth->logged_in();

        $kilometer_service = $this->input->post('kilometer');
        $kilometer_terakhir = $this->input->post('kilometer_terakhir');
        $this->kilometer_check($kilometer_service, $kilometer_terakhir);

        echo '<pre>';
        echo var_dump($data);
        echo '</pre>';

        $this->service_luar_model->create_service_luar($data);
        redirect('transaction/service_luar');
    }

    function _update_service_luar($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('service_luar_model', 'kendaraan_model', 'bengkel_model'));
        $resultBengkel = $this->bengkel_model->get_bengkel_rekanan('', array('bengkel' => $this->input->post('bengkel')))->row_array();
        $result = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();

// Assign the query data : header
        $data['id'] = $id;
        $data['kendaraan_id'] = $result['id'];
        $data['bengkel_id'] = $resultBengkel['id'];
        $data['kilometer'] = $this->input->post('kilometer');
        $data['nip'] = $this->input->post('nip');
        $data['customer'] = strtoupper($this->input->post('customer'));
        $data['status'] = $this->input->post('status');
        $tgl_pelaksanaan = new DateTime($this->input->post('tgl_pelaksanaan'));
        $data['tgl_pelaksanaan'] = $tgl_pelaksanaan->format('Y-m-d');
        $data['keterangan'] = $this->input->post('keterangan');
        $data['foc'] = $this->input->post('foc');
        $data['biaya'] = $this->input->post('biaya');

// Assign the query data : detail
        $data['service_luar_id'] = $id;
        $data['part'] = $this->input->post('part');
        $data['qty'] = $this->input->post('qty');
        $data['price'] = $this->input->post('price');
        $data['total'] = $this->input->post('total');

        $data['service_category_id'] = $this->input->post('cat_per_id');
        $data['perbaikan_id'] = $this->input->post('per_id');
        $data['pk_perbaikan_detail'] = $this->input->post('pk_perbaikan_detail');
        $data['pk_part'] = $this->input->post('pk_part');

        $data['modified_on'] = $this->log_date();
        $data['modified_by'] = $this->auth->logged_in();

        $kilometer_service = $this->input->post('kilometer');
        $kilometer_terakhir = $this->input->post('kilometer_terakhir');
        $this->kilometer_check($kilometer_service, $kilometer_terakhir);

        echo '<pre>';
        var_dump($data);
        echo '</pre>';


        $pk_perbaikan = $this->input->post('pk_perbaikan_detail');
        if ($pk_perbaikan != '') {
            echo 'pk kosong';
        }

        $this->service_luar_model->update_service_luar($data);
        redirect('transaction/service_luar');
    }

    function service_luar($type = NULL, $query_id = 0, $sort_by = 'tgl_terima', $sort_order = 'desc', $offset = 0) {

        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/service_luar/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table', 'pagination')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('service_luar_create') === FALSE) {
//don't do anything
                } else {
                    $this->_create_service_luar();
                }

                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Service sheet';
                $data['tools'] = array(
                    'transaction/service' => '&laquo; Back'
                );

                $this->load->model(array('service_model', 'service_detail_model', 'teknisi_model', 'service_category_model', 'perbaikan_model', 'part_model', 'service_luar_model'));
                $limit = 20;
                $query_array = null;
                $results_service_detail = $this->service_detail_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                //dinamis
                $data['category_options'] = $this->service_category_model->get_service_category();
                $data['perbaikan_options'] = $this->perbaikan_model->get_perbaikan('', array('service_category_id' => $this->input->post('category')));
                $data['teknisi_options'] = $this->teknisi_model->get_teknisi();
                $data['perbaikan_info_options'] = $this->perbaikan_model->get_perbaikan();

                $data['service_category_options'] = $this->service_category_model->get_service_category();
                $data['part_options'] = $this->part_model->get_part();
                $data['generate_options'] = $this->service_luar_model->get_last_generate_id();

                $results_service_detail = $this->service_detail_model->search($query_array);
                $data['results_service_detail'] = $results_service_detail;
                $data['num_results_service_detail'] = sizeof($results_service_detail->result());

                $config = array();
                $config['base_url'] = site_url("transaction/service/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results_service_detail'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;
                $data = array_merge($data, $this->service_luar_model->set_default());

                $data['no_polisi'] = '';
                $data['service_category_id'] = '';
                $data['perbaikan_id'] = '';
                $data['bengkel'] = '';
                $data['email'] = '';
                $data['pic'] = '';
                $data['kota'] = '';
                $data['telephone1'] = '';
                $data['kilometer_terakhir'] = '';
                $data['results'] = '';

                $data['tahun'] = '';
                $data['merk'] = '';
                $data['jenis'] = '';
                $data['no_rangka'] = '';
                $data['no_mesin'] = '';

                $this->load->view('transaction/service_luar_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table', 'pagination')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_service_luar.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('service_luar_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_service_luar($id);
                }

                $data['page_title'] = 'Update Service Luar Transaction';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/transaction' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;
                $this->load->model(array('service_luar_model', 'service_luar_detail_model', 'teknisi_model', 'service_category_model', 'perbaikan_model', 'part_model'));

                $result = $this->service_luar_model->get_service_luar('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                //dinamis
                $data['category_options'] = $this->service_category_model->get_service_category();
                $data['perbaikan_options'] = $this->perbaikan_model->get_perbaikan('', array('service_category_id' => $this->input->post('category')));
                $data['teknisi_options'] = $this->teknisi_model->get_teknisi();
                $data['perbaikan_info_options'] = $this->perbaikan_model->get_perbaikan();
                $data['service_category_options'] = $this->service_category_model->get_service_category();
                $data['part_options'] = $this->part_model->get_part();

                $limit = 20;
                $query_array = array(
                    'service_luar_id' => $id
                );

                $results_service_luar_detail = $this->service_luar_detail_model->search($query_array, $limit, $offset, $sort_by, $sort_order);
                $data['service_luar_details'] = $results_service_luar_detail['results'];
                $data['num_service_luar_detail'] = $results_service_luar_detail['num_rows'];

                $results_service_perbaikan_detail = $this->service_luar_model->search_by_perbaikan($query_array);
                $data['results_service_perbaikan_detail'] = $results_service_perbaikan_detail;

                $config = array();
                $config['base_url'] = site_url("transaction/service_luar/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_service_luar_detail'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $this->load->view('transaction/service_luar_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'suggestion':
                $this->load->model('service_luar_model');
                $bengkel = $this->input->get('bengkel');
                $terms = array(
                    'bengkel' => $bengkel
                );
                $this->service_luar_model->suggestion($terms);
                break;
            case 'search' :
                $query_array = array(
                    'no_polisi' => $this->input->post('no_polisi'),
                    'no_service' => $this->input->post('no_service'),
                    'tgl_start' => $this->input->post('tgl_start'),
                    'tgl_end' => $this->input->post('tgl_end')
                );

                $query_id = $this->input->save_query($query_array);

                redirect("transaction/service_luar/view/$query_id");
                break;
            default:
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model(array('service_luar_model'));
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_polisi' => $this->input->get('no_polisi'),
                    'no_service' => $this->input->get('no_service'),
                    'tgl_start' => $this->input->get('tgl_start'),
                    'tgl_end' => $this->input->get('tgl_end')
                );
                $results = $this->service_luar_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/service_luar/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Service Luar';
                $data['tools'] = array(
                    'transaction/service_luar/create' => 'New Service Luar Sheet'
                );

                $data['tgl_start'] = '';
                $data['tgl_end'] = '';

                $this->load->view('transaction/service_luar', $data);
                break;
        }
    }

    /*
     * Surat pengalihan hak
     */

    function _update_surat_pengalihan_hak_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_surat_pengalihan_hak_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_surat_pengalihan_hak_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_surat_pengalihan_hak_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_surat_pengalihan_hak_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_surat_pengalihan_hak_year', 'option_value' => $sys_year));
    }

    function _get_surat_pengalihan_hak_no() {

        $this->_update_surat_pengalihan_hak_no();
        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_surat_pengalihan_hak_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_surat_pengalihan_hak_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_surat_pengalihan_hak_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_surat_pengalihan_hak_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_surat_pengalihan_hak() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('surat_pengalihan_hak_model', 'kendaraan_model', 'company_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultCompany = $this->company_model->get_company('', array('company' => $this->input->post('company')))->row_array();

// Assign the query data
        $data['id'] = $this->_get_pengambilan_no();
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['company_id'] = $resultCompany['id'];
        $data['pembeli'] = $this->input->post('pembeli');
        $data['alamat_pembeli'] = $this->input->post('alamat_pembeli');
        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $this->surat_pengalihan_hak_model->create_surat_pengalihan_hak($data);
        redirect('transaction/surat_pengalihan_hak');
    }

    function _update_surat_pengalihan_hak($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('surat_pengalihan_hak_model', 'kendaraan_model', 'company_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultCompany = $this->company_model->get_company('', array('company' => $this->input->post('company')))->row_array();

// Assign the query data
        $data['id'] = $id;
        $data['company_id'] = $resultCompany['id'];
        $data['pembeli'] = $this->input->post('pembeli');
        $data['alamat_pembeli'] = $this->input->post('alamat_pembeli');
        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();

        $this->surat_pengalihan_hak_model->update_surat_pengalihan_hak($data);
        redirect('transaction/surat_pengalihan_hak');
    }

    function surat_pengalihan_hak($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'asc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/surat_pengalihan_hak/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('surat_pengalihan_hak_create') === FALSE) {
//don't do anything
                } else {
                    $this->_create_surat_pengalihan_hak();
                }
                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Surat Pengalihan Hak';
                $data['tools'] = array(
                    'transaction/surat_pengalihan_hak' => '&laquo; Back'
                );

                $this->load->model('surat_pengalihan_hak_model');
                $data['generate_options'] = $this->surat_pengalihan_hak_model->get_last_generate_id();

                $data = array_merge($data, $this->surat_pengalihan_hak_model->set_default()); //merge dengan arr data dengan default

                $data['no_polisi'] = '';
                $data['jenis'] = '';
                $data['merk'] = '';
                $data['tahun'] = '';
                $data['warna'] = '';
                $data['no_rangka'] = '';
                $data['no_mesin'] = '';
                $data['company'] = '';
                $data['alamat'] = '';

                $this->load->view('transaction/pengalihan_hak_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_surat_pengalihan_hak.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('surat_pengalihan_hak_update') === FALSE) {
                    
                } else {
                    $this->_update_surat_pengalihan_hak($id);
                }
                $data['page_title'] = 'Update Surat Pengalihan Hak';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/surat_pengalihan_hak' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;

                $this->load->model(array('surat_pengalihan_hak_model', 'kendaraan_model'));

                $result = $this->surat_pengalihan_hak_model->get_surat_pengalihan_hak('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->get('no_polisi')))->row_array();

                $data['no_polisi'] = $resultKendaraan['no_polisi'];
                $data['jenis'] = '';
                $data['merk'] = '';
                $data['tahun'] = '';
                $data['warna'] = '';
                $data['no_rangka'] = '';
                $data['no_mesin'] = '';
                $data['company'] = '';

                $this->load->view('transaction/pengalihan_hak_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'cetak':
                $id = $this->uri->segment(4);
                $term = array(
                    't_surat_pengalihan_hak.id' => $id
                );
                $this->load->model('surat_pengalihan_hak_model');
                $result = $this->surat_pengalihan_hak_model->get_surat_pengalihan_hak('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $array_hari = array(1 => "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
                $hari = $array_hari[date("N")];
                $tanggal = date("j");
                $array_bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                $bulan = $array_bulan[date("n")];
                $tahun = date("Y");
                $data['date_now'] = "Jakarta" . ",&nbsp;" . $tanggal . "&nbsp;" . $bulan . "&nbsp;" . $tahun;

                $this->load->view('transaction/pengalihan_hak_cetak', $data);

                break;
            case 'search' :
                $query_array = array(
                    'no_polisi' => $this->input->post('no_polisi')
                );
                $query_id = $this->input->save_query($query_array);
                redirect("transaction/surat_pengalihan_hak/view/$query_id");
                break;
            default:
// pagination
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model('surat_pengalihan_hak_model');
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $results = $this->surat_pengalihan_hak_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/surat_pengalihan_hak/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Surat Pengalihan Hak';

                $data['tools'] = array(
                    'transaction/surat_pengalihan_hak/create' => 'New Surat Pengalihan Hak Sheet'
                );

                $data['no_polisi'] = '';
                $data = array_merge($data, $this->surat_pengalihan_hak_model->set_default()); //merge dengan arr data dengan default;

                $this->load->view('transaction/pengalihan_hak', $data);
                break;
        }
    }

    /*
     * Surat Perintah Kerja
     * 
     */

    function _update_surat_perintah_kerja_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_surat_perintah_kerja_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_surat_perintah_kerja_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_surat_perintah_kerja_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_surat_perintah_kerja_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_surat_perintah_kerja_year', 'option_value' => $sys_year));
    }

    function _get_surat_perintah_kerja_no() {

        $this->_update_surat_perintah_kerja_no();
        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_surat_perintah_kerja_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_surat_perintah_kerja_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_surat_perintah_kerja_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_surat_perintah_kerja_last_no', 'option_value' => $next_number));

        return $last_no . '-SPK-MR-' . $month . '-' . $year;
    }

    function _create_surat_perintah_kerja() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('surat_perintah_kerja_model', 'kendaraan_model', 'company_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultCompany = $this->company_model->get_company('', array('company' => $this->input->post('company')))->row_array();

// Assign the query data
        $data['id'] = $this->_get_surat_perintah_kerja_no();
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['perbaikan_id'] = implode(',', $this->input->post('perbaikan'));
        $data['company_id'] = $resultCompany['id'];

// Assign the query data
        $data['surat_perintah_kerja_id'] = $data['id'];
        $data['part_id'] = $this->input->post('no_part');
        $data['qty'] = $this->input->post('qty');
        $data['total'] = $this->input->post('price');

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $this->surat_perintah_kerja_model->create_surat_perintah_kerja($data);
        redirect('transaction/surat_perintah_kerja');
    }

    function _update_surat_perintah_kerja($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('surat_perintah_kerja_model', 'kendaraan_model', 'company_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultCompany = $this->company_model->get_company('', array('company' => $this->input->post('company')))->row_array();

// Assign the query data
        $data['id'] = $id;
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['perbaikan_id'] = implode(',', $this->input->post('perbaikan'));
        $data['part_id'] = implode(',', $this->input->post('part'));
        $data['company_id'] = $resultCompany['id'];

// Assign the query data
        $data['surat_perintah_kerja_id'] = $id;
        $data['part_id'] = $this->input->post('no_part');
        $data['qty'] = $this->input->post('qty');
        $data['total'] = $this->input->post('price');

        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();

        $this->surat_perintah_kerja_model->update_surat_perintah_kerja($data);
        redirect('transaction/surat_perintah_kerja');
    }

    function surat_perintah_kerja($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'asc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/surat_perintah_kerja/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('surat_perintah_kerja_create') === FALSE) {
//don't do anything
                } else {
                    $this->_create_surat_perintah_kerja();
                }
                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Surat Perintah Kerja';
                $data['tools'] = array(
                    'transaction/surat_perintah_kerja' => '&laquo; Back'
                );

                $this->load->model(array('surat_perintah_kerja_model', 'service_category_model'));
                $data['generate_options'] = $this->surat_perintah_kerja_model->get_last_generate_id();
                $data = array_merge($data, $this->surat_perintah_kerja_model->set_default()); //merge dengan arr data dengan default

                $data['no_polisi'] = '';
                $data['jenis'] = '';
                $data['merk'] = '';
                $data['tahun'] = '';
                $data['warna'] = '';
                $data['no_rangka'] = '';
                $data['no_mesin'] = '';
                $data['company'] = '';
                $data['alamat'] = '';
                $data['kilometer_terakhir'] = '';

                $data['service_category_options'] = $this->service_category_model->get_service_category();

                $this->load->view('transaction/perintah_kerja_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_surat_perintah_kerja.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('surat_perintah_kerja_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_surat_perintah_kerja($id);
                }
                $data['page_title'] = 'Update Surat Perintah Kerja';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/perintah_kerja' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;

                $this->load->model(array('surat_perintah_kerja_model', 'surat_perintah_kerja_detail_model', 'kendaraan_model', 'perbaikan_model', 'service_category_model'));

                $result = $this->surat_perintah_kerja_model->get_surat_perintah_kerja('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->get('no_polisi')))->row_array();
                $data['no_polisi'] = $resultKendaraan['no_polisi'];

                $data['service_category_options'] = $this->service_category_model->get_service_category();

                $limit = 1;
                $query_array = array(
                    'surat_perintah_kerja_id' => $id
                );

                $results_surat_perintah_kerja_detail = $this->surat_perintah_kerja_detail_model->search($query_array, $limit, $offset, $sort_by, $sort_order);
                $data['results_surat_perintah_kerja_detail'] = $results_surat_perintah_kerja_detail['results'];
                $data['num_results_surat_perintah_kerja_detail'] = $results_surat_perintah_kerja_detail['num_rows'];

                $data['alamat'] = '';

                $this->load->view('transaction/perintah_kerja_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'cetak':
                $id = $this->uri->segment(4);
                $term = array(
                    't_surat_perintah_kerja.id' => $id
                );
                $this->load->model(array('surat_perintah_kerja_model', 'surat_perintah_kerja_detail_model', 'kendaraan_model', 'perbaikan_model'));
                $result = $this->surat_perintah_kerja_model->get_surat_perintah_kerja('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $perbaikan_arr = explode(",", $data['perbaikan_id']);
                foreach ($perbaikan_arr as $key => $perbaikan_id) {
                    $perbaikan = $this->perbaikan_model->get_perbaikan('', array('m_perbaikan.id' => $perbaikan_id))->row_array();
                    $data['all_perbaikan'][] = $perbaikan['perbaikan'];
                }

                $limit = 1;
                $query_array = array(
                    'surat_perintah_kerja_id' => $id
                );

                $results_surat_perintah_kerja_detail = $this->surat_perintah_kerja_detail_model->search($query_array, $limit, $offset, $sort_by, $sort_order);
                $data['results_surat_perintah_kerja_detail'] = $results_surat_perintah_kerja_detail['results'];
                $data['num_results_surat_perintah_kerja_detail'] = $results_surat_perintah_kerja_detail['num_rows'];

                $array_hari = array(1 => "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
                $hari = $array_hari[date("N")];
                $tanggal = date("j");
                $array_bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                $bulan = $array_bulan[date("n")];
                $tahun = date("Y");
                $data['date_now'] = "Jakarta" . ",&nbsp;" . $tanggal . "&nbsp;" . $bulan . "&nbsp;" . $tahun;

                //$this->load->view('transaction/perintah_kerja_cetak', $data);
                $this->load->view('transaction/service_ext_print', $data);

                break;
            case 'search' :
                $query_array = array(
                    'no_polisi' => $this->input->post('no_polisi')
                );
                $query_id = $this->input->save_query($query_array);
                redirect("transaction/perintah_kerja/view/$query_id");
                break;
            default:
// pagination
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model(array('surat_perintah_kerja_model', 'perbaikan_model'));
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $results = $this->surat_perintah_kerja_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/surat_perintah_kerja/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Surat Perintah Kerja';

                $data['tools'] = array(
                    'transaction/surat_perintah_kerja/create' => 'New Surat Perintah Kerja Sheet'
                );

                $data['no_polisi'] = '';
                $data = array_merge($data, $this->surat_perintah_kerja_model->set_default()); //merge dengan arr data dengan default;

                $this->load->view('transaction/perintah_kerja', $data);
                break;
        }
    }

    function _update_claim_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_claim_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_claim_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_claim_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_claim_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_claim_year', 'option_value' => $sys_year));
    }

    function _get_claim_no() {

        $this->_update_claim_no();
        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_claim_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_claim_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_claim_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_claim_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_claim() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('claim_model', 'kendaraan_model', 'peminjam_model', 'bengkel_rekanan_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultBengkel = $this->bengkel_rekanan_model->get_bengkel_rekanan('', array('bengkel' => $this->input->post('bengkel')))->row_array();

// Assign the query data
        $data['id'] = $this->_get_claim_no();
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['bengkel_id'] = $resultBengkel['id'];
        $data['kilometer_perbaikan'] = $this->input->post('kilometer_perbaikan');
        $data['nip'] = $this->input->post('nip');
        $data['customer'] = strtoupper($this->input->post('customer'));
        $data['status'] = 'TERIMA';
        $tgl_claim = new DateTime($this->input->post('tgl_claim'));
        $data['tgl_claim'] = $tgl_claim->format('Y-m-d');
        $tgl_perbaikan = new DateTime($this->input->post('tgl_perbaikan'));
        $data['tgl_perbaikan'] = $tgl_perbaikan->format('Y-m-d');
        $tgl_selesai = new DateTime($this->input->post('tgl_selesai'));
        $data['tgl_selesai'] = $tgl_selesai->format('Y-m-d');
        $data['sub_total'] = $this->input->post('sub_total');
        $data['sparepart'] = strtoupper($this->input->post('sparepart'));
        $data['kronologis'] = strtoupper($this->input->post('kronologis'));

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

// Assign the query data
        $this->claim_model->create_claim($data);
        redirect('transaction/claim');
    }

    function _update_claim($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('claim_model', 'kendaraan_model', 'peminjam_model', 'bengkel_rekanan_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();
        $resultBengkel = $this->bengkel_rekanan_model->get_bengkel_rekanan('', array('bengkel' => $this->input->post('bengkel')))->row_array();

// Assign the query data
        $data['id'] = $id;
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['bengkel_id'] = $resultBengkel['id'];
        $data['kilometer_perbaikan'] = $this->input->post('kilometer_perbaikan');
        $data['nip'] = $this->input->post('nip');
        $data['customer'] = strtoupper($this->input->post('customer'));
        $data['status'] = 'TERIMA';
        $tgl_claim = new DateTime($this->input->post('tgl_claim'));
        $data['tgl_claim'] = $tgl_claim->format('Y-m-d');
        $tgl_perbaikan = new DateTime($this->input->post('tgl_perbaikan'));
        $data['tgl_perbaikan'] = $tgl_perbaikan->format('Y-m-d');
        $tgl_selesai = new DateTime($this->input->post('tgl_selesai'));
        $data['tgl_selesai'] = $tgl_selesai->format('Y-m-d');
        $data['sub_total'] = $this->input->post('sub_total');
        $data['sparepart'] = strtoupper($this->input->post('sparepart'));
        $data['kronologis'] = strtoupper($this->input->post('kronologis'));

        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();

// Assign the query data
        $this->claim_model->update_claim($data);
        redirect('transaction/claim');
    }

    function claim($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'asc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/claim/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('claim_create') === FALSE) {
//don't do anything
                } else {
                    $this->_create_claim();
                }
                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Claim';
                $data['tools'] = array(
                    'transaction/claim' => '&laquo; Back'
                );

                $this->load->model('claim_model');
                $data['generate_options'] = $this->claim_model->get_last_generate_id();
                $data = array_merge($data, $this->claim_model->set_default()); //merge dengan arr data dengan default

                $data['no_polisi'] = '';
                $data['bengkel'] = '';
                $data['nip'] = '';

                $data['tahun'] = '';
                $data['merk'] = '';
                $data['jenis'] = '';
                $data['no_rangka'] = '';
                $data['no_mesin'] = '';

                $this->load->view('transaction/claim_form', $data);
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_claim.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('claim_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_claim($id);
                }

                $data['page_title'] = 'Update Claim';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/claim' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;

                $this->load->model(array('claim_model', 'kendaraan_model'));

                $result = $this->claim_model->get_claim('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }


                $this->load->view('transaction/claim_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'search' :
                $query_array = array(
                    'no_claim' => $this->input->post('no_claim'),
                    'no_polisi' => $this->input->post('no_polisi')
                );
                $query_id = $this->input->save_query($query_array);
                redirect("transaction/claim/view/$query_id");
                break;
            default:
// pagination
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model('claim_model');
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_claim' => $this->input->get('no_claim'),
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $results = $this->claim_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/surat_perintah_kerja/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Claim';

                $data['tools'] = array(
                    'transaction/claim/create' => 'New Claim Sheet'
                );

                $data['no_polisi'] = '';

                $data = array_merge($data, $this->claim_model->set_default()); //merge dengan arr data dengan default;

                $this->load->view('transaction/claim', $data);
                break;
        }
    }

    function _update_asuransi_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_asuransi_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_asuransi_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_asuransi_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_asuransi_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_asuransi_year', 'option_value' => $sys_year));
    }

    function _get_asuransi_no() {

        $this->_update_asuransi_no();
        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_asuransi_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_asuransi_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_asuransi_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_asuransi_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_asuransi() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('asuransi_model', 'kendaraan_model'));

        // Assign the query data
        $data['id'] = $this->_get_asuransi_no();
        $data['no_polis'] = $this->input->post('no_polis');
        $data['jenis_leasing'] = $this->input->post('jenis_leasing');
        $data['jenis_bank'] = $this->input->post('jenis_bank');
        if ($this->input->post('jenis_bank') == 'Bank') {
            $data['nama_bank'] = $this->input->post('nama_bank');
        } else {
            $data['nama_bank'] = 'non-Bank';
        }
        $data['jenis_tertanggung'] = $this->input->post('jenis_tertanggung');
        if ($this->input->post('jenis_tertanggung') == 'PERUSAHAAN') {
            $data['nama_tertanggung'] = $this->input->post('perusahaan_tertanggung');
        } else {
            $data['nama_tertanggung'] = $this->input->post('pribadi_tertanggung');
        }

        if ($this->input->post('nama_bank') == 'BCA') {
            $data['periode_awal'] = '';
            $data['periode_akhir'] = '';
        } else {
            $periode_awal = new DateTime($this->input->post('periode_awal'));
            $data['periode_awal'] = $periode_awal->format('Y-m-d');
            $periode_akhir = new DateTime($this->input->post('periode_akhir'));
            $data['periode_akhir'] = $periode_akhir->format('Y-m-d');
        }

        // Assign the query data
        $data['no_polisi'] = $this->input->post('no_polisi');
        $kendaraan_arr = $this->input->post('no_polisi');
        foreach ($kendaraan_arr as $key => $no_polisi) {
            $kendaraan = $this->kendaraan_model->get_kendaraan('', array('m_kendaraan.no_polisi' => $no_polisi))->row_array();
            $data['kendaraan_id'][] = $kendaraan['id'];
        }
        $data['asuransi_id'] = $data['id'];
        $data['harga_pertanggungan'] = $this->input->post('harga_pertanggungan');
        $data['rate'] = $this->input->post('rate');
        $data['tahun'] = $this->input->post('tahun');
        $data['cover'] = $this->input->post('cover');
        $data['persentase'] = $this->input->post('persentase');
        $data['premi'] = $this->input->post('premi');

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $this->asuransi_model->create_asuransi($data);
        redirect('transaction/asuransi');
    }

    function _update_asuransi($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('asuransi_model', 'kendaraan_model'));

        // Assign the query data
        $data['id'] = $id;
        $data['no_polis'] = $this->input->post('no_polis');
        $data['jenis_leasing'] = $this->input->post('jenis_leasing');
        $data['jenis_bank'] = $this->input->post('jenis_bank');
        if ($this->input->post('jenis_bank') == 'Bank') {
            $data['nama_bank'] = $this->input->post('nama_bank');
        } else {
            $data['nama_bank'] = 'non-Bank';
        }
        $data['jenis_tertanggung'] = $this->input->post('jenis_tertanggung');

        if ($this->input->post('jenis_tertanggung') == 'PERUSAHAAN') {
            $data['nama_tertanggung'] = $this->input->post('perusahaan_tertanggung');
        } else if ($this->input->post('jenis_tertanggung') == 'PRIBADI') {
            $data['nama_tertanggung'] = $this->input->post('pribadi_tertanggung');
        } else {
            $data['nama_tertanggung'] = '-';
        }

        $periode_awal = new DateTime($this->input->post('periode_awal'));
        $data['periode_awal'] = $periode_awal->format('Y-m-d');
        $periode_akhir = new DateTime($this->input->post('periode_akhir'));
        $data['periode_akhir'] = $periode_akhir->format('Y-m-d');

        // Assign the query data
        $data['asuransi_id'] = $id;

        $data['no_polisi'] = $this->input->post('no_polisi');
        $kendaraan_arr = $this->input->post('no_polisi');
        foreach ($kendaraan_arr as $key => $no_polisi) {
            $kendaraan = $this->kendaraan_model->get_kendaraan('', array('m_kendaraan.no_polisi' => $no_polisi))->row_array();
            $data['kendaraan_id'][] = $kendaraan['id'];
        }

        $data['asuransi_id'] = $data['id'];
        $data['harga_pertanggungan'] = $this->input->post('harga_pertanggungan');
        $data['rate'] = $this->input->post('rate');
        $data['persentase'] = $this->input->post('persentase');
        $data['tahun'] = $this->input->post('tahun');
        $data['cover'] = $this->input->post('cover');
        $data['premi'] = $this->input->post('premi');
        $data['pk'] = $this->input->post('pk');

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();

        //Assign the query data
        $this->asuransi_model->update_asuransi($data);
        redirect('transaction/asuransi/info/' . $id);
    }

    function asuransi($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'asc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/asuransi/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('asuransi_create') === FALSE) {
                    //don't do anything
                } else {
                    $this->_create_asuransi();
                }
                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Asuransi';
                $data['tools'] = array(
                    'transaction/asuransi' => '&laquo; Back'
                );

                $this->load->model(array('asuransi_model', 'bank_model'));
                $data['generate_options'] = $this->asuransi_model->get_last_generate_id();
                $data['bank_options'] = $this->bank_model->get_bank();
                $data = array_merge($data, $this->asuransi_model->set_default()); //merge dengan arr data dengan default

                $data['no_polisi'] = '';
                $data['vehicle_type'] = '';
                $data['company'] = '';
                $data['jenis_leasing'] = '';
                $data['nama_bank'] = '';
                $data['jenis_tertanggung'] = '';
                $data['perusahaan_tertanggung'] = '';
                $data['pribadi_tertanggung'] = '';

                $this->load->view('transaction/asuransi_form', $data);
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_asuransi.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('asuransi_update') === FALSE) {
                    //don't do anything
                } else {
                    $this->_update_asuransi($id);
                }

                $data['page_title'] = 'Update Asuransi';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/asuransi' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;

                $this->load->model(array('asuransi_model', 'kendaraan_model', 'asuransi_detail_model'));

                $result = $this->asuransi_model->get_asuransi('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $limit = 1;
                $query_array = array(
                    'asuransi_id' => $id
                );

                $results_asuransi_detail = $this->asuransi_detail_model->search($query_array, $limit, $offset, $sort_by, $sort_order);
                $data['results_asuransi_detail'] = $results_asuransi_detail['results'];
                $data['num_results_asuransi_detail'] = $results_asuransi_detail['num_rows'];

                $data['company'] = '';

                $this->load->view('transaction/asuransi_form', $data);
                break;
            case 'delete_asuransi':
                $this->crud->use_table('t_service_asuransi_detail');
                $criteria = array('id' => $this->input->post('pk'));
                $data_in = array(
                    'active' => 0
                );
                $this->crud->update($criteria, $data_in);
                //[debug] echo $this->db->last_query();
                break;
            case 'delete_asuransi_detail':
                $this->crud->use_table('t_asuransi_detail');
                $criteria = array('id' => $this->input->post('pk'));
                $data_in = array(
                    'active' => 0
                );
                $this->crud->update($criteria, $data_in);
                //[debug]
                echo $this->db->last_query();
                break;
            case 'search' :
                $query_array = array(
                    'no_polis' => $this->input->post('no_polis')
                );
                $query_id = $this->input->save_query($query_array);
                redirect("transaction/asuransi/view/$query_id");
                break;
            default:
// pagination
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model('asuransi_model');
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_polis' => $this->input->get('no_polis'),
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $results = $this->asuransi_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/asuransi/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Asuransi';

                $data['tools'] = array(
                    'transaction/asuransi/create' => 'New Asuransi Sheet'
                );

                $data = array_merge($data, $this->asuransi_model->set_default()); //merge dengan arr data dengan default;

                $data['no_polis'] = '';

                $this->load->view('transaction/asuransi', $data);
                break;
        }
    }

    /*
     * transaction 
     * superzkoss
     * 27 maret 2012
     */

    function _update_penjualan_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_penjualan_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_penjualan_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_penjualan_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_penjualan_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_penjualan_year', 'option_value' => $sys_year));
    }

    function _get_penjualan_no() {

        $this->_update_asuransi_no();
        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_penjualan_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_penjualan_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_penjualan_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_penjualan_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_penjualan() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('kendaraan_model', 'penjualan_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();

// Assign the query data
        $data['id'] = $this->_get_penjualan_no();
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['pembeli'] = $this->input->post('pembeli');
        $data['alamat_pembeli'] = $this->input->post('alamat_pembeli');
        $tgl_penjualan = new DateTime($this->input->post('tgl_penjualan'));
        $data['tgl_penjualan'] = $tgl_penjualan->format('Y-m-d');

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $this->penjualan_model->create_penjualan($data);
        redirect('transaction/penjualan');
    }

    function _update_penjualan($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('kendaraan_model', 'penjualan_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();

// Assign the query data
        $data['id'] = $id;
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['pembeli'] = $this->input->post('pembeli');
        $data['alamat_pembeli'] = $this->input->post('alamat_pembeli');
        $tgl_penjualan = new DateTime($this->input->post('tgl_penjualan'));
        $data['tgl_penjualan'] = $tgl_penjualan->format('Y-m-d');

        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();

        $this->penjualan_model->update_penjualan($data);
        redirect('transaction/penjualan');
    }

    function penjualan($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'desc', $offset = 0) {

        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/penjualan/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('penjualan_create') === FALSE) {
//don't do anything
                } else {
                    $this->_create_penjualan();
                }

                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Penjualan';
                $data['tools'] = array(
                    'transaction/penjualan' => '&laquo; Back'
                );

                $this->load->model('penjualan_model');
                $data['generate_options'] = $this->penjualan_model->get_last_generate_id();
                $data = array_merge($data, $this->penjualan_model->set_default()); //merge dengan arr data dengan default
//reset karena bukan field asli
                $data['no_polisi'] = '';
                $data['tahun'] = '';
                $data['merk'] = '';
                $data['jenis'] = '';
                $data['no_rangka'] = '';
                $data['no_mesin'] = '';

                $data['no_polisi'] = '';
                $this->load->view('transaction/penjualan_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_penjualan.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('penjualan_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_penjualan($id);
                }

                $data['page_title'] = 'Update Penjualan';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/penjualan' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;
                $this->load->model('penjualan_model');
                $result = $this->penjualan_model->get_penjualan('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $this->load->view('transaction/penjualan_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'search' :
                $query_array = array(
                    'no_penjualan' => $this->input->post('no_penjualan'),
                    'no_polisi' => $this->input->post('no_polisi')
                );

                $query_id = $this->input->save_query($query_array);

                redirect("transaction/penjualan/view/$query_id");
            case 'detail' :
                $this->load->helper(array('form'));
                break;
            default:
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model('penjualan_model');
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_penjualan' => $this->input->get('no_penjualan'),
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $results = $this->penjualan_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/penjualan/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Penjualan';
                $data['tools'] = array(
                    'transaction/penjualan/create' => 'New Penjualan Sheet'
                );

                $data = array_merge($data, $this->penjualan_model->set_default()); //merge dengan arr data dengan default
                $data['no_penjualan'] = '';

                $this->load->view('transaction/penjualan', $data);
                break;
        }
    }

    function _update_mutasi_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_mutasi_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_mutasi_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_mutasi_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_mutasi_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_mutasi_year', 'option_value' => $sys_year));
    }

    function _get_mutasi_no() {

        $this->_update_mutasi_no();
        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_mutasi_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_mutasi_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_mutasi_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_mutasi_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_mutasi() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('kendaraan_model', 'mutasi_model'));
        $resultSourceKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi_lama')))->row_array();
        $resultDestinationKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi_baru')))->row_array();

        $data['id'] = $this->_get_mutasi_no();
        $data['kendaraan_id_lama'] = $resultSourceKendaraan['id'];
        $data['no_polisi_lama'] = $resultSourceKendaraan['no_polisi'];
        $data['kendaraan_id_baru'] = $resultDestinationKendaraan['id'];
        $data['no_polisi_baru'] = $resultDestinationKendaraan['no_polisi'];
        $data['status_mutasi'] = 'IN PROGRESS';
        $tgl_mutasi = new DateTime($this->input->post('tgl_mutasi'));
        $data['tgl_mutasi'] = $tgl_mutasi->format('Y-m-d');
        $data['keterangan'] = $this->input->post('keterangan');

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();


        $this->mutasi_model->create_mutasi($data);
        redirect('transaction/mutasi');
    }

    function _update_mutasi($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('kendaraan_model', 'mutasi_model'));
        $resultSourceKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi_lama')))->row_array();
        $resultDestinationKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi_baru')))->row_array();

        $data['id'] = $id;
        $data['kendaraan_id_lama'] = $resultSourceKendaraan['id'];
        $data['no_polisi_lama'] = $this->input->post('no_polisi_lama');
        $data['kendaraan_id_baru'] = $resultDestinationKendaraan['id'];
        $data['no_polisi_baru'] = $this->input->post('no_polisi_baru');
        $data['status_mutasi'] = 'SELESAI';
        $tgl_mutasi = new DateTime($this->input->post('tgl_mutasi'));
        $data['tgl_mutasi'] = $tgl_mutasi->format('Y-m-d');
        $data['keterangan'] = $this->input->post('keterangan');

        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();

        $this->mutasi_model->update_mutasi($data);
        redirect('transaction/mutasi');
    }

    function mutasi($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'asc', $offset = 0) {

        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/mutasi/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('mutasi_create') === FALSE) {
                    
                } else {
                    $this->_create_mutasi();
                }

                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Mutasi';
                $data['tools'] = array(
                    'transaction/mutasi' => '&laquo; Back'
                );

                $this->load->model('mutasi_model');
                $data['generate_options'] = $this->mutasi_model->get_last_generate_id();
                $data = array_merge($data, $this->mutasi_model->set_default()); //merge dengan arr data dengan default

                $data['no_polisi_lama'] = '';
                $data['no_polisi_baru'] = '';
                $data['merk_lama'] = '';
                $data['jenis_lama'] = '';
                $data['no_rangka_lama'] = '';
                $data['no_mesin_lama'] = '';
                $data['tahun_lama'] = '';
                $data['merk_baru'] = '';
                $data['jenis_baru'] = '';
                $data['no_rangka_baru'] = '';
                $data['no_mesin_baru'] = '';
                $data['tahun_baru'] = '';

                $this->load->view('transaction/mutasi_form', $data);
                break;
            case 'update':
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_mutasi.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('mutasi_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_mutasi($id);
                }

                $data['page_title'] = 'Update Mutasi';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/mutasi' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;
                $this->load->model('mutasi_model');
                $result = $this->mutasi_model->get_mutasi('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $this->load->view('transaction/mutasi_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'search' :
                $query_array = array(
                    'no_mutasi' => $this->input->post('no_mutasi')
                );

                $query_id = $this->input->save_query($query_array);

                redirect("transaction/mutasi/view/$query_id");
            case 'detail' :
                $this->load->helper(array('form'));
                break;
            default:
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model('mutasi_model');
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_mutasi' => $this->input->get('no_mutasi')
                );
                $results = $this->mutasi_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/mutasi/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Mutasi';
                $data['tools'] = array(
                    'transaction/mutasi/create' => 'New Mutasi Sheet'
                );

                $data = array_merge($data, $this->mutasi_model->set_default()); //merge dengan arr data dengan default

                $data['no_mutasi'] = '';

                $this->load->view('transaction/mutasi', $data);
                break;
        }
    }

    function _update_audit_no() {
        $sys_date = new DateTime();
        $sys_month = $sys_date->format('m');
        $sys_year = $sys_date->format('y');

        $this->load->model('option_model');
        $month = $this->option_model->get_options('', array('option' => 't_audit_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_audit_year'))->row()->option_value;

        if ($sys_month != $month) {
            $this->option_model->update_option(array('option' => 't_audit_month', 'option_value' => $sys_month));
            $this->option_model->update_option(array('option' => 't_audit_last_no', 'option_value' => 0));
        }
        if ($sys_year != $year)
            $this->option_model->update_option(array('option' => 't_audit_year', 'option_value' => $sys_year));
    }

    function _get_audit_no() {

        $this->_update_mutasi_no();
        $this->load->model('option_model');
        $next_number = $this->option_model->get_options('', array('option' => 't_audit_last_no'))->row()->option_value + 1;
        $last_no = $this->number_cast($next_number);
        $month = $this->option_model->get_options('', array('option' => 't_audit_month'))->row()->option_value;
        $year = $this->option_model->get_options('', array('option' => 't_audit_year'))->row()->option_value;

        $this->option_model->update_option(array('option' => 't_audit_last_no', 'option_value' => $next_number));

        return $year . $month . $last_no;
    }

    function _create_audit() {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('kendaraan_model', 'audit_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();

// Assign the query data
        $data['id'] = $this->_get_audit_no();
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['audit_category_id'] = implode(',', $this->input->post('audit_category'));
        $tgl_audit = new DateTime($this->input->post('tgl_audit'));
        $data['tgl_audit'] = $tgl_audit->format('Y-m-d');
        $data['keterangan'] = $this->input->post('keterangan');

        $data['created_on'] = $date->format($this->config->item('log_date_format'));
        $data['created_by'] = $this->auth->logged_in();

        $this->audit_model->create_audit($data);
        redirect('transaction/audit');
    }

    function _update_audit($id) {
        $data = array();
        $date = new DateTime();
        $this->load->model(array('kendaraan_model', 'audit_model'));
        $resultKendaraan = $this->kendaraan_model->get_kendaraan('', array('no_polisi' => $this->input->post('no_polisi')))->row_array();

        $data['id'] = $id;
        $data['kendaraan_id'] = $resultKendaraan['id'];
        $data['audit_category_id'] = implode(',', $this->input->post('audit_category'));
        $tgl_audit = new DateTime($this->input->post('tgl_audit'));
        $data['tgl_audit'] = $tgl_audit->format('Y-m-d');
        $data['keterangan'] = $this->input->post('keterangan');

// Assign the query data
        $data['modified_on'] = $date->format($this->config->item('log_date_format'));
        $data['modified_by'] = $this->auth->logged_in();

        $this->audit_model->update_audit($data);
        redirect('transaction/audit');
    }

    function audit($type = NULL, $query_id = 0, $sort_by = 'id', $sort_order = 'asc', $offset = 0) {
        $data_type = $this->input->post('data_type');
        $data['action_type'] = $type;
        $master_url = 'transaction/audit/';
        $data['auth'] = $this->auth;
        switch ($type) {
            case 'create':
                $this->load->library(array('form_validation', 'table', 'pagination')); //, 'session'));
                $this->load->helper(array('form', 'snippets'));
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('audit_create') === FALSE) {
//don't do anything
                } else {
                    $this->_create_audit();
                }
                $data['action_url'] = $master_url . $type;
                $data['page_title'] = 'New Audit';
                $data['tools'] = array(
                    'transaction/audit' => '&laquo; Back'
                );

                $this->load->model(array('audit_model', 'audit_detail_model'));

                $limit = 20;
                $query_array = array(
                    'no_audit' => $this->input->get('no_audit')
                );

                $results_audit = $this->audit_model->search($query_array, $limit, $offset, $sort_by, $sort_order);
                $data['results'] = $results_audit['results'];
                $data['num_rows'] = $results_audit ['num_rows'];

                $data['audit_options'] = $this->audit_model->get_audit_name();
                $data['audit_category_options'] = $this->audit_model->get_audit_category();
                $data['generate_options'] = $this->audit_model->get_last_generate_id();

                $data = array_merge($data, $this->audit_model->set_default()); //merge dengan arr data dengan default

                $data['no_polisi'] = '';
                $data['tahun'] = '';
                $data['merk'] = '';
                $data['jenis'] = '';
                $data['no_rangka'] = '';
                $data['no_mesin'] = '';
                $data['company'] = '';
                $data['kilometer_terakhir'] = '';
                $data['condition'] = '';

                $this->load->view('transaction/audit_form', $data);
                break;
            case 'info':
                $this->load->library(array('form_validation', 'table'));
                $this->load->helper(array('form', 'snippets'));
                $id = $this->uri->segment(4);
                $term = array(
                    't_audit.id' => $id
                );
                $this->form_validation->set_error_delimiters('<span class="notice">', '</span>');
                if ($this->form_validation->run('audit_update') === FALSE) {
//don't do anything
                } else {
                    $this->_update_audit($id);
                }

                $data['page_title'] = 'Update Audit';
                $data['action_type'] = $type;
                $data['tools'] = array(
                    'transaction/audit' => '&laquo; Back'
                );
                $data['action_url'] = $master_url . $type . '/' . $id;

                $this->load->model(array('audit_model', 'audit_checklist_model', 'audit_checklist_category_model', 'audit_detail_model', 'perbaikan_model', 'service_category_model'));
                $data['audit_options'] = $this->audit_model->get_audit_name();
                $data['audit_category_options'] = $this->audit_model->get_audit_category();
                $data['generate_options'] = $this->audit_model->get_last_generate_id();

                $result = $this->audit_model->get_audit('', $term)->row_array();
                $result_keys = array_keys($result);
                foreach ($result_keys as $result_key) {
                    $data[$result_key] = $result[$result_key];
                }

                $this->load->view('transaction/audit_form', $data);
                break;
            case 'delete':
                $this->load->helper(array('form'));
                break;
            case 'search' :
                $query_array = array(
                    'no_audit' => $this->input->post('no_audit'),
                    'no_polisi' => $this->input->post('no_polisi')
                );
                $query_id = $this->input->save_query($query_array);
                redirect("transaction/audit/view/$query_id");
                break;
            default:
// pagination
                $limit = 20;
                $this->load->library(array('form_validation', 'table', 'pagination'));
                $this->load->model('audit_model');
                $this->input->load_query($query_id);

                $query_array = array(
                    'no_audit' => $this->input->get('no_audit'),
                    'no_polisi' => $this->input->get('no_polisi')
                );
                $results = $this->audit_model->search($query_array, $limit, $offset, $sort_by, $sort_order);

                $data['results'] = $results['results'];
                $data['num_results'] = $results['num_rows'];

// pagination
                $config = array();
                $config['base_url'] = site_url("transaction/audit/view/$query_id/$sort_by/$sort_order");
                $config['total_rows'] = $data['num_results'];
                $config['per_page'] = $limit;
                $config['uri_segment'] = 7;
                $config['num_links'] = 6;

                $config = array_merge($config, $this->_default_pagination_btn());
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();
                $data['sort_by'] = $sort_by;
                $data['sort_order'] = $sort_order;

                $data['page_title'] = 'Audit';

                $data['tools'] = array(
                    'transaction/audit/create' => 'New Audit Sheet'
                );

                $data = array_merge($data, $this->audit_model->set_default()); //merge dengan arr data dengan default;

                $data['no_audit'] = '';
                $data['no_polisi'] = '';

                $this->load->view('transaction/audit', $data);
                break;
        }
    }

}