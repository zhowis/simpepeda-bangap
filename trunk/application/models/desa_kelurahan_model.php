<?php

class Desa_kelurahan_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function s_desa_kelurahan() {
        return $this->db->select('m_desa_kelurahan.*,m_kecamatan.kecamatan')
                        ->from('m_desa_kelurahan')
                        ->join('m_kecamatan', 'm_kecamatan.id = m_desa_kelurahan.kecamatan_id', 'left');
    }

    function get_many($data_type = NULL, $term = array(), $limit = NULL, $offset = NULL) {
        $this->s_desa_kelurahan();
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
                $options[$row->id] = $row->desa_kelurahan;
            }
            echo json_encode($options);
        } else {
            return $query;
        }
    }

    function search($query_array, $limit, $offset, $sort_by, $sort_order) {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        $sort_by = 'id';

        $this->s_desa_kelurahan()
                ->limit($limit, $offset)
                ->order_by($sort_by, $sort_order);

        if ($query_array['desa_kelurahan'] != '') {
            $this->db->like('m_desa_kelurahan.desa_kelurahan', $query_array['desa_kelurahan']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('m_desa_kelurahan.active', $query_array['active']);
        }

        $q = $this->db->get();
        //debug echo $this->db->last_query();
        $ret['results'] = $q;
        $ret['num_rows'] = $this->count_desa_kelurahan($query_array);
        return $ret;
    }

    function count_desa_kelurahan($query_array) {

        $this->s_desa_kelurahan();

        if ($query_array['desa_kelurahan'] != '') {
            $this->db->like('m_desa_kelurahan.desa_kelurahan', $query_array['desa_kelurahan']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('m_desa_kelurahan.active', $query_array['active']);
        }

        return $this->db->count_all_results();
    }

    function set_default() {
        $fields = $this->db->list_fields('m_desa_kelurahan');
        foreach ($fields as $field) {
            $data[$field] = '';
        }
        return $data;
    }

}

