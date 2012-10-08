<?php

class Status_kegiatan_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function s_status_kegiatan() {
        return $this->db->select('m_status_kegiatan.*')
                        ->from('m_status_kegiatan');
    }

    function get_many($data_type = NULL, $term = array(), $limit = NULL, $offset = NULL) {
        $this->s_status_kegiatan();
        if (!empty($term)) {
            foreach ($term as $key => $val) {
                $where[$key] = $val;
            }
            $this->db->where($where);
        }
        $query = $this->db->get();
        //[debug]echo $this->db->last_query();
        if ($data_type == 'json') {
            foreach ($query->result() as $row) {
                $options[$row->id] = $row->status_kegiatan;
            }
            echo json_encode($options);
        } else {
            return $query;
        }
    }

    function search($query_array, $limit, $offset, $sort_by, $sort_order) {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        $sort_by = 'id';

        $this->s_status_kegiatan()
                ->limit($limit, $offset)
                ->order_by($sort_by, $sort_order);

        if ($query_array['status_kegiatan'] != '') {
            $this->db->like('m_status_kegiatan.status_kegiatan', $query_array['status_kegiatan']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('m_status_kegiatan.active', $query_array['active']);
        }

        $q = $this->db->get();
        //debug echo $this->db->last_query();
        $ret['results'] = $q;
        $ret['num_rows'] = $this->count_status_kegiatan($query_array);
        return $ret;
    }

    function count_status_kegiatan($query_array) {

        $this->s_status_kegiatan();

         if ($query_array['status_kegiatan'] != '') {
            $this->db->like('m_status_kegiatan.status_kegiatan', $query_array['status_kegiatan']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('m_status_kegiatan.active', $query_array['active']);
        }
        
        return $this->db->count_all_results();
    }

    function set_default() {
        $fields = $this->db->list_fields('m_status_kegiatan');
        foreach ($fields as $field) {
            $data[$field] = '';
        }
        return $data;
    }

}

