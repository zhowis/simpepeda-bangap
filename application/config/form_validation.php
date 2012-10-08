<?php

$config = array(
    'kendaraan_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'merk_id',
            'label' => 'Merk',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'jenis_id',
            'label' => 'Jenis',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'kota_id',
            'label' => 'Kota',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'tahun',
            'label' => 'Tahun',
            'rules' => 'trim|integer'
        ),
        array(
            'field' => 'kapasitas_mesin',
            'label' => 'Kapasitas mesin',
            'rules' => 'trim'
        ),
        array(
            'field' => 'bahan_bakar',
            'label' => 'Bahan bakar',
            'rules' => 'trim'
        ),
        array(
            'field' => 'warna_id',
            'label' => 'Warna',
            'rules' => 'trim'
        ),
        array(
            'field' => 'pool',
            'label' => 'Pool',
            'rules' => 'trim'
        ),
        array(
            'field' => 'no_rangka',
            'label' => 'No Rangka',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_mesin',
            'label' => 'No Mesin',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_bpkb',
            'label' => 'No BPKB',
            'rules' => 'trim'
        ),
        array(
            'field' => 'bank_id',
            'label' => 'Bank',
            'rules' => 'trim'
        ),
        array(
            'field' => 'no_faktur',
            'label' => 'No Faktur',
            'rules' => 'trim'
        ),
        array(
            'field' => 'tgl_akhir_stnk',
            'label' => 'Tanggal akhir STNK',
            'rules' => 'trim'
        ),
        array(
            'field' => 'alamat_stnk',
            'label' => 'Alamat STNK',
            'rules' => 'trim'
        ),
        array(
            'field' => 'tgl_akhir_kir',
            'label' => 'Tanggal akhir KIR',
            'rules' => 'trim'
        ),
        array(
            'field' => 'pemilik_id',
            'label' => 'Pemilik',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'tgl_akhir_asuransi',
            'label' => 'Tanggal akhir asuransi',
            'rules' => 'trim'
        ),
        array(
            'field' => 'no_asuransi',
            'label' => 'No Asuransi',
            'rules' => 'trim'
        ),
        array(
            'field' => 'tgl_jual',
            'label' => 'Tanggal jual',
            'rules' => 'trim'
        ),
        array(
            'field' => 'status_audit',
            'label' => 'Status audit',
            'rules' => 'trim'
        )
    ), 'kendaraan_update' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'merk_id',
            'label' => 'Merk',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'jenis_id',
            'label' => 'Jenis',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'kota_id',
            'label' => 'Kota',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'tahun',
            'label' => 'Tahun',
            'rules' => 'trim|integer'
        ),
        array(
            'field' => 'kapasitas_mesin',
            'label' => 'Kapasitas mesin',
            'rules' => 'trim'
        ),
        array(
            'field' => 'bahan_bakar',
            'label' => 'Bahan bakar',
            'rules' => 'trim'
        ),
        array(
            'field' => 'warna_id',
            'label' => 'Warna',
            'rules' => 'trim'
        ),
        array(
            'field' => 'pool',
            'label' => 'Pool',
            'rules' => 'trim'
        ),
        array(
            'field' => 'no_rangka',
            'label' => 'No Rangka',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_mesin',
            'label' => 'No Mesin',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_bpkb',
            'label' => 'No BPKB',
            'rules' => 'trim'
        ),
        array(
            'field' => 'bank_id',
            'label' => 'Bank',
            'rules' => 'trim'
        ),
        array(
            'field' => 'no_faktur',
            'label' => 'No Faktur',
            'rules' => 'trim'
        ),
        array(
            'field' => 'tgl_akhir_stnk',
            'label' => 'Tanggal akhir STNK',
            'rules' => 'trim'
        ),
        array(
            'field' => 'alamat_stnk',
            'label' => 'Alamat STNK',
            'rules' => 'trim'
        ),
        array(
            'field' => 'tgl_akhir_kir',
            'label' => 'Tanggal akhir KIR',
            'rules' => 'trim'
        ),
        array(
            'field' => 'pemilik_id',
            'label' => 'Pemilik',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'tgl_akhir_asuransi',
            'label' => 'Tanggal akhir asuransi',
            'rules' => 'trim'
        ),
        array(
            'field' => 'no_asuransi',
            'label' => 'No Asuransi',
            'rules' => 'trim'
        ),
        array(
            'field' => 'tgl_jual',
            'label' => 'Tanggal jual',
            'rules' => 'trim'
        ),
        array(
            'field' => 'status_audit',
            'label' => 'Status audit',
            'rules' => 'trim'
        )
    ), 'merk_create' => array(
        array(
            'field' => 'merk',
            'label' => 'Merk',
            'rules' => 'trim|required|is_unique[m_merk.merk]'
        )
    ), 'merk_update' => array(
        array(
            'field' => 'merk',
            'label' => 'Merk',
            'rules' => 'trim|required'
        )
    ), 'peminjam_create' => array(
        array(
            'field' => 'nip',
            'label' => 'nip',
            'rules' => 'trim|required|is_unique[m_peminjam.nip]'
        ),
        array(
            'field' => 'peminjam',
            'label' => 'peminjam',
            'rules' => 'trim|required'
        )
    ), 'peminjam_update' => array(
        array(
            'field' => 'nip',
            'label' => 'nip',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'peminjam',
            'label' => 'peminjam',
            'rules' => 'trim|required'
        )
    ), 'jenis_create' => array(
        array(
            'field' => 'jenis',
            'label' => 'Jenis',
            'rules' => 'trim|required|is_unique[m_jenis.jenis]'
        ),
        array(
            'field' => 'merk',
            'label' => 'Merk',
            'rules' => 'required'
        )
    ), 'jenis_update' => array(
        array(
            'field' => 'jenis',
            'label' => 'Jenis',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'merk',
            'label' => 'Merk',
            'rules' => 'required'
        )
    ), 'company_create' => array(
        array(
            'field' => 'company',
            'label' => 'Perusahaan',
            'rules' => 'trim|required'
        )
    ), 'company_update' => array(
        array(
            'field' => 'company',
            'label' => 'Perusahaan',
            'rules' => 'trim|required'
        )
    ), 'departments_create' => array(
        array(
            'field' => 'department',
            'label' => 'Department',
            'rules' => 'trim|required|is_unique[m_jenis.jenis]'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'company',
            'label' => 'Company',
            'rules' => 'required'
        )
    ), 'departments_update' => array(
        array(
            'field' => 'department',
            'label' => 'Department',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'company',
            'label' => 'Company',
            'rules' => 'required'
        )
    ), 'kota_create' => array(
        array(
            'field' => 'kota',
            'label' => 'kota',
            'rules' => 'trim|required|is_unique[m_kota.kota]'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'kota_update' => array(
        array(
            'field' => 'kota',
            'label' => 'kota',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'bank_create' => array(
        array(
            'field' => 'bank',
            'label' => 'bank',
            'rules' => 'trim|required|is_unique[m_bank.bank]'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'bank_update' => array(
        array(
            'field' => 'bank',
            'label' => 'bank',
            'rules' => 'trim|required|is_unique[m_bank.bank]'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'warna_create' => array(
        array(
            'field' => 'warna',
            'label' => 'warna',
            'rules' => 'trim|required|is_unique[m_warna.warna]'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'warna_update' => array(
        array(
            'field' => 'warna',
            'label' => 'warna',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'jabatan_create' => array(
        array(
            'field' => 'jabatan',
            'label' => 'jabatan',
            'rules' => 'trim|required|is_unique[m_jabatan.jabatan]'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'jabatan_update' => array(
        array(
            'field' => 'jabatan',
            'label' => 'jabatan',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'lokasi_create' => array(
        array(
            'field' => 'lokasi',
            'label' => 'lokasi',
            'rules' => 'trim|required|is_unique[m_lokasi.lokasi]'
        ),
        array(
            'field' => 'lokasi',
            'label' => 'lokasi',
            'rules' => 'required'
        )
    ), 'lokasi_update' => array(
        array(
            'field' => 'lokasi',
            'label' => 'lokasi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'lokasi',
            'label' => 'lokasi',
            'rules' => 'required'
        )
    ), 'teknisi_create' => array(
        array(
            'field' => 'nip',
            'label' => 'NIP',
            'rules' => 'trim|required|is_unique[m_teknisi.nip]'
        ),
        array(
            'field' => 'fullname',
            'label' => 'Fullname',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim'
        ),
        array(
            'field' => 'telephone',
            'label' => 'Telephone',
            'rules' => 'trim'
        )
    ), 'teknisi_update' => array(
        array(
            'field' => 'nip',
            'label' => 'NIP',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'fullname',
            'label' => 'Fullname',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim'
        ),
        array(
            'field' => 'telephone',
            'label' => 'Telephone',
            'rules' => 'trim'
        )
    ), 'part_create' => array(
        array(
            'field' => 'id',
            'label' => 'id',
            'rules' => 'trim|callback_unique_no_part'
        ),
        array(
            'field' => 'part',
            'label' => 'part',
            'rules' => 'trim|required|callback_unique_name_part'
        ),
    ), 'part_update' => array(
        array(
            'field' => 'id',
            'label' => 'id',
            'rules' => 'trim'
        ),
        array(
            'field' => 'part',
            'label' => 'part',
            'rules' => 'trim'
        )
    ), 'perbaikan_create' => array(
        array(
            'field' => 'service_category_id',
            'label' => 'Category',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'perbaikan',
            'label' => 'Perbaikan',
            'rules' => 'trim|required|is_unique[m_perbaikan.perbaikan]'
        )
    ), 'perbaikan_update' => array(
        array(
            'field' => 'service_category_id',
            'label' => 'Category',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'perbaikan',
            'label' => 'Perbaikan',
            'rules' => 'trim|required'
        )
    ), 'warehouse_create' => array(
        array(
            'field' => 'warehouse',
            'label' => 'warehouse',
            'rules' => 'trim|required|is_unique[m_warehouse.warehouse]'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'warehouse_update' => array(
        array(
            'field' => 'warehouse',
            'label' => 'warehouse',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|required'
        )
    ), 'consumable_create' => array(
        array(
            'field' => 'item',
            'label' => 'Item',
            'rules' => 'trim|required|is_unique[m_warehouse.warehouse]'),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim'
        ),
        array(
            'field' => 'warehouse',
            'label' => 'warehouse',
            'rules' => 'trim|greater_than[0]'
        )
    ), 'consumable_update' => array(
        array(
            'field' => 'item',
            'label' => 'Item',
            'rules' => 'trim|required'),
        array(
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim'
        ),
        array(
            'field' => 'warehouse',
            'label' => 'warehouse',
            'rules' => 'trim|greater_than[0]'
        )
    ), 'employee_create' => array(
        array(
            'field' => 'employee_id',
            'label' => 'employee_id',
            'rules' => 'trim|required|is_unique[m_employee_point.employee_id]'
        ),
        array(
            'field' => 'employee_name',
            'label' => 'employee_name',
            'rules' => 'trim|required'
        )
    ), 'employee_update' => array(
        array(
            'field' => 'employee_id',
            'label' => 'employee_id',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'employee_name',
            'label' => 'employee_name',
            'rules' => 'trim|required'
        )
    ), 'peminjaman_create' => array(
        array(
            'field' => 'nip',
            'label' => 'Nip',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'tgl_pemakaian',
            'label' => 'Tanggal Pemakaian',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'tujuan',
            'label' => 'Tujuan',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        )
    ), 'peminjaman_update' => array(
        array(
            'field' => 'nip',
            'label' => 'Nip',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'tgl_pemakaian',
            'label' => 'Tanggal Pemakaian',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'tujuan',
            'label' => 'Tujuan',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        )
    ), 'pengembalian_create' => array(
        array(
            'field' => 'tgl_pengembalian',
            'label' => 'Tanggal Pengembalian',
            'rules' => 'trim|required'
        )
    ), 'pengembalian_update' => array(
        array(
            'field' => 'tgl_pengembalian',
            'label' => 'Tanggal Pengembalian',
            'rules' => 'trim|required'
        )
    ), 'surat_kuasa_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'jenis_surat_kuasa',
            'label' => 'Jenis Surat Kuasa',
            'rules' => 'trim|required'
        )
    ), 'surat_kuasa_update' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'jenis_surat_kuasa',
            'label' => 'Jenis Surat Kuasa',
            'rules' => 'trim'
        )
    ), 'service_category_create' => array(
        array(
            'field' => 'category',
            'label' => 'category',
            'rules' => 'trim|required|is_unique[m_service_category.category]'
        )
    ), 'service_category_update' => array(
        array(
            'field' => 'category',
            'label' => 'category',
            'rules' => 'trim|required'
        )
    ), 'pengambilan_create' => array(
        array(
            'field' => 'tgl_pengambilan',
            'label' => 'Tgl Pengambilan',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'nip',
            'label' => 'NIP',
            'rules' => 'trim|required'
        )
    ), 'pengambilan_update' => array(
        array(
            'field' => 'tgl_pengambilan',
            'label' => 'Tgl Pengambilan',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'nip',
            'label' => 'NIP',
            'rules' => 'trim|required'
        )
    ), 'payment_create' => array(
        array(
            'field' => 'tgl_payment',
            'label' => 'Tgl Payment',
            'rules' => 'trim|required'
        )
    ), 'payment_update' => array(
        array(
            'field' => 'tgl_payment',
            'label' => 'Tgl Payment',
            'rules' => 'trim|required'
        )
    ), 'service_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'kilometer',
            'label' => 'kilometer',
            'rules' => 'trim|required|is_natural_no_zero|callback_kilometer_check'
        )
    ), 'service_detail_create' => array(
        array(
            'field' => 'part',
            'label' => 'Part',
            'rules' => 'trim|required'
        )
    ), 'service_update' => array(
        array(
            'field' => 'kilometer',
            'label' => 'kilometer',
            'rules' => 'trim|required'
        )
    ), 'service_detail_update' => array(
        array(
            'field' => 'part',
            'label' => 'Part',
            'rules' => 'trim|required'
        )
    ), 'service_luar_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'kilometer',
            'label' => 'kilometer',
            'rules' => 'trim|required|callback_kilometer_check'
        )
    ), 'service_luar_update' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'kilometer',
            'label' => 'kilometer',
            'rules' => 'trim|required|callback_kilometer_check'
        )
    ), 'bengkel_create' => array(
        array(
            'field' => 'bengkel',
            'label' => 'Bengkel',
            'rules' => 'trim|required|is_unique[m_bengkel.bengkel]'
        ),
        array(
            'field' => 'kota',
            'label' => 'Kota',
            'rules' => 'trim|greater_than[0]'
        )
    ), 'bengkel_update' => array(
        array(
            'field' => 'bengkel',
            'label' => 'Bengkel',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'kota',
            'label' => 'Kota',
            'rules' => 'trim|greater_than[0]'
        )
    ), 'surat_pengalihan_hak_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        )
    ), 'surat_pengalihan_hak_update' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        )
    ), 'surat_perintah_kerja_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        )
    ), 'surat_perintah_kerja_update' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        )
    ), 'claim_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'customer',
            'label' => 'customer',
            'rules' => 'trim|required'
        )
    ), 'claim_update' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'customer',
            'label' => 'customer',
            'rules' => 'trim|required'
        )
    ), 'asuransi_create' => array(
        array(
            'field' => 'no_polis',
            'label' => 'No Polis',
            'rules' => 'trim|required'
        )
    ), 'asuransi_update' => array(
        array(
            'field' => 'no_polis',
            'label' => 'No Polis',
            'rules' => 'trim|required'
        )
    ), 'penjualan_create' => array(
        array(
            'field' => 'tgl_penjualan',
            'label' => 'tgl_penjualan',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_polisi',
            'label' => 'no_polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'pembeli',
            'label' => 'pembeli',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'alamat_pembeli',
            'label' => 'alamat_pembeli',
            'rules' => 'trim|required'
        )
    ), 'penjualan_update' => array(
        array(
            'field' => 'tgl_penjualan',
            'label' => 'tgl_penjualan',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_polisi',
            'label' => 'no_polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'pembeli',
            'label' => 'pembeli',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'alamat_pembeli',
            'label' => 'alamat_pembeli',
            'rules' => 'trim|required'
        )
    ), 'branch_create' => array(
        array(
            'field' => 'branch',
            'label' => 'Branch',
            'rules' => 'trim|required'
        )
    ), 'branch_update' => array(
        array(
            'field' => 'branch',
            'label' => 'Branch',
            'rules' => 'trim|required'
        )
    ), 'driver_create' => array(
        array(
            'field' => 'fullname',
            'label' => 'Nama Lengkap',
            'rules' => 'trim|required'
        )
    ), 'driver_update' => array(
        array(
            'field' => 'fullname',
            'label' => 'Nama Lengkap',
            'rules' => 'trim|required'
        )
    ), 'mutasi_create' => array(
        array(
            'field' => 'no_polisi_lama',
            'label' => 'no_polisi_lama',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_polisi_baru',
            'label' => 'no_polisi_baru',
            'rules' => 'trim|required'
        )
    ), 'mutasi_update' => array(
        array(
            'field' => 'no_polisi_lama',
            'label' => 'no_polisi_lama',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'no_polisi_baru',
            'label' => 'no_polisi_baru',
            'rules' => 'trim|required'
        )
    ), 'service_bureau_create' => array(
        array(
            'field' => 'bureau',
            'label' => 'Biro Jasa',
            'rules' => 'trim|required'
        )
    ), 'service_bureau_update' => array(
        array(
            'field' => 'bureau',
            'label' => 'Biro Jasa',
            'rules' => 'trim|required'
        )
    ), 'insurance_company_create' => array(
        array(
            'field' => 'company',
            'label' => 'Perusahaan',
            'rules' => 'trim|required'
        )
    ), 'insurance_company_update' => array(
        array(
            'field' => 'company',
            'label' => 'Perusahaan',
            'rules' => 'trim|required'
        )
    ), 'insurance_rate_create' => array(
        array(
            'field' => 'company_id',
            'label' => 'Perusahaan',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'tahun',
            'label' => 'Tahun',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'rate',
            'label' => 'Rate',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'persentase',
            'label' => 'Persentase',
            'rules' => 'trim|required'
        )
    ), 'insurance_rate_update' => array(
        array(
            'field' => 'company',
            'label' => 'Perusahaan',
            'rules' => 'trim|greater_than[0]'
        ),
        array(
            'field' => 'tahun',
            'label' => 'Tahun',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'rate',
            'label' => 'Rate',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'persentase',
            'label' => 'Persentase',
            'rules' => 'trim|required'
        )
    ), 'audit_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'no_polisi',
            'rules' => 'trim|required'
        )
    ), 'audit_update' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'no_polisi',
            'rules' => 'trim|required'
        )
    ), 'letter_of_authority_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'company',
            'label' => 'Company',
            'rules' => 'trim|required'
        )
    ), 'letter_of_authority_update' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'company',
            'label' => 'Company',
            'rules' => 'trim|required'
        )
    ), 'type_swap' => array(
        array(
            'field' => 'new_type',
            'label' => 'Jenis baru',
            'rules' => 'trim|greater_than[0]'
        )
    ), 'dropoff_create' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'tgl_pengembalian',
            'label' => 'Tanggal Pengembalian',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'diserahkan_oleh',
            'label' => 'Diserahkan oleh',
            'rules' => 'trim|required'
        )
    ), 'dropoff_update' => array(
        array(
            'field' => 'no_polisi',
            'label' => 'No Polisi',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'tgl_pengembalian',
            'label' => 'Tanggal Pengembalian',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'diserahkan_oleh',
            'label' => 'Diserahkan oleh',
            'rules' => 'trim|required'
        )
    ), 'member_create' => array(
        array(
            'field' => 'nip',
            'label' => 'nip',
            'rules' => 'trim|required|callback_unique_member'
        ),
        array(
            'field' => 'peminjam',
            'label' => 'peminjam',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'pic',
            'label' => 'pic',
            'rules' => 'trim|callback_required_if_nip_zero'
        )
    ), 'member_update' => array(
        array(
            'field' => 'nip',
            'label' => 'nip',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'peminjam',
            'label' => 'peminjam',
            'rules' => 'trim|required'
        )
    ), 'desa_kelurahan_create' => array(
        array(
            'field' => 'desa_kelurahan',
            'label' => 'desa_kelurahan',
            'rules' => 'trim|required|callback_unique_desa_kelurahan'
        )
    ), 'desa_kelurahan_update' => array(
        array(
            'field' => 'desa_kelurahan',
            'label' => 'desa_kelurahan',
            'rules' => 'trim|required'
        )
    ), 'kecamatan_create' => array(
        array(
            'field' => 'kecamatan',
            'label' => 'kecamatan',
            'rules' => 'trim|required|callback_unique_kecamatan'
        )
    ), 'kecamatan_update' => array(
        array(
            'field' => 'kecamatan',
            'label' => 'kecamatan',
            'rules' => 'trim|required'
        )
    ), 'kategori_skpd_create' => array(
        array(
            'field' => 'kategori_skpd',
            'label' => 'kategori_skpd',
            'rules' => 'trim|required|callback_unique_kategori_skpd'
        )
    ), 'kategori_skpd_update' => array(
        array(
            'field' => 'kategori_skpd',
            'label' => 'kategori_skpd',
            'rules' => 'trim|required'
        )
    ), 'skpd_create' => array(
        array(
            'field' => 'skpd',
            'label' => 'skpd',
            'rules' => 'trim|required|callback_unique_skpd'
        )
    ), 'skpd_update' => array(
        array(
            'field' => 'skpd',
            'label' => 'skpd',
            'rules' => 'trim|required'
        )
    ), 'status_prioritas_create' => array(
        array(
            'field' => 'status_prioritas',
            'label' => 'status_prioritas',
            'rules' => 'trim|required|callback_unique_status_prioritas'
        )
    ), 'status_prioritas_update' => array(
        array(
            'field' => 'status_prioritas',
            'label' => 'status_prioritas',
            'rules' => 'trim|required'
        )
    ), 'status_kegiatan_create' => array(
        array(
            'field' => 'status_kegiatan',
            'label' => 'status_kegiatan',
            'rules' => 'trim|required|callback_unique_status_kegiatan'
        )
    ), 'status_kegiatan_update' => array(
        array(
            'field' => 'status_kegiatan',
            'label' => 'status_kegiatan',
            'rules' => 'trim|required'
        )
    ), 'status_musrenbang_create' => array(
        array(
            'field' => 'status_musrenbang',
            'label' => 'status_musrenbang',
            'rules' => 'trim|required|callback_unique_status_musrenbang'
        )
    ), 'status_musrenbang_update' => array(
        array(
            'field' => 'status_musrenbang',
            'label' => 'status_musrenbang',
            'rules' => 'trim|required'
        )
    ), 'status_pelaksanaan_create' => array(
        array(
            'field' => 'status_pelaksanaan',
            'label' => 'status_pelaksanaan',
            'rules' => 'trim|required|callback_unique_status_pelaksanaan'
        )
    ), 'status_pelaksanaan_update' => array(
        array(
            'field' => 'status_pelaksanaan',
            'label' => 'status_pelaksanaan',
            'rules' => 'trim|required'
        )
    ), 'musrenbang_usulan_create' => array(
        array(
            'field' => 'nama_kegiatan',
            'label' => 'nama_kegiatan',
            'rules' => 'trim|required'
        )
    ), 'musrenbang_usulan_update' => array(
        array(
            'field' => 'nama_kegiatan',
            'label' => 'nama_kegiatan',
            'rules' => 'trim|required'
        )
    )
);