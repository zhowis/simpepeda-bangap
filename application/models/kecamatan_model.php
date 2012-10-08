<?php

class Kecamatan_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function s_kecamatan() {
        return $this->db->select('m_kecamatan.*')
                        ->from('m_kecamatan');
    }

    function get_many($data_type = NULL, $term = array(), $limit = NULL, $offset = NULL) {
        $this->s_kecamatan();
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
                $options[$row->id] = $row->kecamatan;
            }
            echo json_encode($options);
        } else {
            return $query;
        }
    }

    function search($query_array, $limit, $offset, $sort_by, $sort_order) {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        $sort_by = 'id';

        $this->s_kecamatan()
                ->limit($limit, $offset)
                ->order_by($sort_by, $sort_order);

        if ($query_array['kecamatan'] != '') {
            $this->db->like('m_kecamatan.kecamatan', $query_array['kecamatan']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('m_kecamatan.active', $query_array['active']);
        }

        $q = $this->db->get();
        //debug echo $this->db->last_query();
        $ret['results'] = $q;
        $ret['num_rows'] = $this->count_kecamatan($query_array);
        return $ret;
    }

    function count_kecamatan($query_array) {

        $this->s_kecamatan();

        if ($query_array['kecamatan'] != '') {
            $this->db->like('m_kecamatan.kecamatan', $query_array['kecamatan']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('m_kecamatan.active', $query_array['active']);
        }

        return $this->db->count_all_results();
    }

    function set_default() {
        $fields = $this->db->list_fields('m_kecamatan');
        foreach ($fields as $field) {
            $data[$field] = '';
        }
        return $data;
    }

}

