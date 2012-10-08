<?php

class Musrenbang_usulan_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function s_musrenbang_usulan() {
        return $this->db->select('t_musrenbang_usulan.*,m_skpd.skpd,m_desa_kelurahan.desa_kelurahan,m_kecamatan.kecamatan,
            m_status_kegiatan.status_kegiatan,m_status_prioritas.status_prioritas,m_status_musrenbang.status_musrenbang')
                        ->from('t_musrenbang_usulan')
                        ->join('m_skpd', 'm_skpd.id = t_musrenbang_usulan.skpd_id', 'left')
                        ->join('m_kecamatan', 'm_kecamatan.id = t_musrenbang_usulan.kecamatan_id', 'left')
                        ->join('m_desa_kelurahan', 'm_desa_kelurahan.id = t_musrenbang_usulan.desa_kelurahan_id', 'left')
                        ->join('m_status_kegiatan', 'm_status_kegiatan.id = t_musrenbang_usulan.status_kegiatan_id', 'left')
                        ->join('m_status_prioritas', 'm_status_prioritas.id = t_musrenbang_usulan.status_prioritas_id', 'left')
                        ->join('m_status_musrenbang', 'm_status_musrenbang.id = t_musrenbang_usulan.status_musrenbang_id', 'left');
    }

    function get_many($data_type = NULL, $term = array(), $limit = NULL, $offset = NULL) {
        $this->s_musrenbang_usulan();
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
                $options[$row->id] = $row->nama_kegiatan;
            }
            echo json_encode($options);
        } else {
            return $query;
        }
    }

    function search($query_array, $limit, $offset, $sort_by, $sort_order) {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        $sort_by = 'id';

        $this->s_musrenbang_usulan()
                ->limit($limit, $offset)
                ->order_by($sort_by, $sort_order);

        if ($query_array['skpd'] != '') {
            $this->db->where('m_skpd.skpd', $query_array['skpd']);
        }

        if ($query_array['nama_kegiatan'] != '') {
            $this->db->like('t_musrenbang_usulan.nama_kegiatan', $query_array['nama_kegiatan']);
        }

        if ($query_array['kecamatan'] != '') {
            $this->db->where('m_kecamatan.kecamatan', $query_array['kecamatan']);
        }

        if ($query_array['kelurahan'] != '') {
            $this->db->where('m_kelurahan.kelurahan', $query_array['kelurahan']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('t_musrenbang_usulan.active', $query_array['active']);
        }

        $q = $this->db->get();
        //debug echo $this->db->last_query();
        $ret['results'] = $q;
        $ret['num_rows'] = $this->count_musrenbang_usulan($query_array);
        return $ret;
    }

    function count_musrenbang_usulan($query_array) {

        $this->s_musrenbang_usulan();

        if ($query_array['skpd'] != '') {
            $this->db->where('m_skpd.skpd', $query_array['skpd']);
        }

        if ($query_array['nama_kegiatan'] != '') {
            $this->db->like('t_musrenbang_usulan.nama_kegiatan', $query_array['nama_kegiatan']);
        }

        if ($query_array['kecamatan'] != '') {
            $this->db->where('m_kecamatan.kecamatan', $query_array['kecamatan']);
        }

        if ($query_array['kelurahan'] != '') {
            $this->db->where('m_kelurahan.kelurahan', $query_array['kelurahan']);
        }

        if ($query_array['active'] != '') {
            $this->db->where('t_musrenbang_usulan.active', $query_array['active']);
        }

        return $this->db->count_all_results();
    }

    function set_default() {
        $fields = $this->db->list_fields('t_musrenbang_usulan');
        foreach ($fields as $field) {
            $data[$field] = '';
        }
        return $data;
    }

}

