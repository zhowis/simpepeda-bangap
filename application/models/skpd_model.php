<?php

class Skpd_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function s_skpd() {
        return $this->db->select('m_skpd.*,m_kategori_skpd.kategori_skpd')
                        ->from('m_skpd')
                        ->join('m_kategori_skpd', 'm_kategori_skpd.id = m_skpd.kategori_skpd_id', 'left');
    }

    function get_many($data_type = NULL, $term = array(), $limit = NULL, $offset = NULL) {
        $this->s_skpd();
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
                $options[$row->id] = $row->skpd;
            }
            echo json_encode($options);
        } else {
            return $query;
        }
    }

    function search($query_array, $limit, $offset, $sort_by, $sort_order) {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        $sort_by = 'id';

        $this->s_skpd()
                ->limit($limit, $offset)
                ->order_by($sort_by, $sort_order);

        if ($query_array['skpd'] != '') {
            $this->db->like('m_skpd.skpd', $query_array['skpd']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('m_skpd.active', $query_array['active']);
        }

        $q = $this->db->get();
        //debug echo $this->db->last_query();
        $ret['results'] = $q;
        $ret['num_rows'] = $this->count_skpd($query_array);
        return $ret;
    }

    function count_skpd($query_array) {

        $this->s_skpd();

        if ($query_array['skpd'] != '') {
            $this->db->like('m_skpd.skpd', $query_array['skpd']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('m_skpd.active', $query_array['active']);
        }

        return $this->db->count_all_results();
    }

    function set_default() {
        $fields = $this->db->list_fields('m_skpd');
        foreach ($fields as $field) {
            $data[$field] = '';
        }
        return $data;
    }

}

