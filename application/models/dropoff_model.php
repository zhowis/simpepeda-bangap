<?php

class Dropoff_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function q_basic() {
        $this->db->select('t_pengembalian.*,m_kendaraan.no_polisi,m_kendaraan.kilometer_terakhir,m_jenis.jenis,m_merk.merk')
                ->from('t_pengembalian')
                ->join('m_kendaraan', 't_pengembalian.kendaraan_id = m_kendaraan.id', 'left')
                ->join('m_jenis', 'mr_m_kendaraan.jenis_id = mr_m_jenis.id', 'left')
                ->join('m_merk', 'm_merk.id = mr_m_jenis.merk_id', 'left');
        return $this;
    }

    function q_string($term) {
        $this->q_basic();
        if (!empty($term)) {
            foreach ($term as $key => $val) {
                $where[$key] = $val;
            }
            $this->db->where($where);
        }
        return $this;
    }

    function q_search($query_array) {
        $this->q_basic();
        if (isset($query_array['no_pengembalian']) && $query_array['no_pengembalian'] != '') {
            $this->db->where('t_pengembalian.id', $query_array['no_pengembalian']);
        }
        if (isset($query_array['no_polisi']) && $query_array['no_polisi'] != '') {
            $this->db->like('m_kendaraan.no_polisi', strtoupper($query_array['no_polisi']));
        }
        
        if (isset($query_array['active']) && $query_array['active'] != '') {
            $this->db->where('t_pengembalian.active', strtoupper($query_array['active']));
        }
        return $this;
    }

    function get_by($data_type = NULL, $term = array(), $limit = NULL, $offset = NULL) {
        $this->q_string($term);
        return $this->db->get()->row();
    }
    
    function get_many($data_type = NULL, $term = array(), $limit = NULL, $offset = NULL) {
        $this->q_string($term);
        return $this->db->get()->result();
    }

    function count_dropoff($query_array) {
        $this->q_string($query_array);
        return $this->db->count_all_results();
    }

    function count_search_dropoff($query_array) {
        $this->q_search($query_array);
        return $this->db->count_all_results();
    }

    function search($query_array, $limit, $offset, $sort_by, $sort_order = 'desc') {
        $this->q_search($query_array);
        $this->db->limit($limit, $offset);
        $this->db->order_by($sort_by, $sort_order);
        $data['rows'] = $this->db->get()->result();//echo $this->db->last_query();
        $data['num_rows'] = $this->count_search_dropoff($query_array);
        //[debug] echo $this->db->last_query();
        return $data;
    }

    function create_pengembalian($data = array()) {

        $this->db->insert('t_pengembalian', $data);

        //change status kendaraan
        $this->db->where('id', $data['kendaraan_id']);
        $this->db->update('m_kendaraan', array(
            'status_kendaraan_id' => 1
        ));

        $tipe_transaksi = $data['tipe_transaksi'];

        if ($tipe_transaksi == 'peminjaman') {
            //change returned
            $this->db->where('id', $data['transaksi_id']);
            $this->db->update('t_peminjaman', array(
                'returned' => 1
            ));
        }

        if ($tipe_transaksi == 'pengambilan') {
            //change returned
            $this->db->where('id', $data['transaksi_id']);
            $this->db->update('t_pengambilan', array(
                'returned' => 1
            ));
        }
    }

    function update_pengembalian($data = array()) {
        $this->db->get('t_pengembalian');
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('t_pengembalian', $data);
    }

    function set_default() {
        $fields = $this->db->list_fields('t_pengembalian');
        foreach ($fields as $field) {
            $data[$field] = '';
        }
        return $data;
    }

    function suggestion($terms = NULL) {
        //return json
        $this->db->select('t_peminjaman.*,m_kendaraan.no_polisi,m_kendaraan.tahun,m_kendaraan.kapasitas_mesin,m_kendaraan.bahan_bakar,m_kendaraan.no_rangka,m_kendaraan.no_mesin,m_kendaraan.status_kendaraan_id,m_peminjam.nip,m_peminjam.peminjam,m_jabatan.jabatan,m_departments.department,m_company.company');
        $this->db->from('t_peminjaman');
        $this->db->join('m_kendaraan', 't_peminjaman.kendaraan_id = m_kendaraan.id', 'left');
        $this->db->join('m_peminjam', 't_peminjaman.peminjam_id = m_peminjam.id', 'left');
        $this->db->join('m_jabatan', 'm_jabatan.id = m_peminjam.jabatan_id', 'left');
        $this->db->join('m_departments', 'm_departments.id = m_peminjam.department_id', 'left');
        $this->db->join('m_company', 'm_company.id = m_departments.company_id', 'left');
        $this->db->where('m_kendaraan.status_kendaraan_id', 2); //status kendaraan not available

        if (isset($terms['no_peminjaman']) && $terms['no_peminjaman'] != '') {
            $this->db->like('t_peminjaman.id', $terms['no_peminjaman']);
        }

        if (isset($terms['no_polisi']) && $terms['no_polisi'] != '') {
            $this->db->like('m_kendaraan.no_polisi', $terms['no_polisi']);
        }

        $this->db->limit(10);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $options_id[$row->id] = $row->id;
            $options_no_polisi[$row->id] = $row->no_polisi;
            $options_warna[$row->id] = $row->warna;
            $options_jenis[$row->id] = $row->jenis;
            $options_kapasitas_mesin[$row->id] = $row->kapasitas_mesin;
            $options_tahun[$row->id] = $row->tahun;
            $options_bahan_bakar[$row->id] = $row->bahan_bakar;
            $options_merk[$row->id] = $row->merk;
            $options_no_rangka[$row->id] = $row->no_rangka;
            $options_no_mesin[$row->id] = $row->no_mesin;
            $options_nip[$row->id] = $row->nip;
            $options_peminjam[$row->id] = $row->peminjam;
            $options_lokasi[$row->id] = $row->lokasi;
            $options_tgl_peminjaman[$row->id] = $row->tgl_peminjaman;
            $options_tgl_pemakaian[$row->id] = $row->tgl_pemakaian;
            $options_tujuan[$row->id] = $row->tujuan;
        }

        $options['no_pengembalian_options'] = $options_id;

        $options['no_polisi_options'] = $options_no_polisi;
        $options['warna_options'] = $options_warna;
        $options['jenis_options'] = $options_jenis;
        $options['warna_options'] = $options_warna;
        $options['kapasitas_mesin_options'] = $options_kapasitas_mesin;
        $options['tahun_options'] = $options_tahun;
        $options['bahan_bakar_options'] = $options_bahan_bakar;
        $options['merk_options'] = $options_merk;
        $options['no_rangka_options'] = $options_no_rangka;
        $options['no_mesin_options'] = $options_no_mesin;

        $options['nip_options'] = $options_nip;
        $options['peminjam_options'] = $options_peminjam;
        $options['lokasi_options'] = $options_lokasi;
        $options['tgl_peminjaman_options'] = $options_tgl_peminjaman;
        $options['tgl_pemakaian_options'] = $options_tgl_pemakaian;
        $options['tujuan_options'] = $options_tujuan;

        echo json_encode($options);
    }

    function get_last_generate_id() {
        $this->db->select_max('id');
        return $this->db->get('t_peminjaman');
    }

}

