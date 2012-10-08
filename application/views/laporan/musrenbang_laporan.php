<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
?>

<div class="container-fluid form-inline well" id="musrenbang_laporan-search">
    <?php
    $skpd_attr = array(
        'id' => 'skpd',
        'name' => 'skpd',
        'class' => 'input-medium',
        'style' => 'text-transform : uppercase;',
        'placeholder' => 'SKPD'
    );
    $nama_kegiatan_attr = array(
        'id' => 'nama_kegiatan',
        'name' => 'nama_kegiatan',
        'class' => 'input-medium',
        'style' => 'text-transform : uppercase;',
        'placeholder' => 'Nama Kegiatan'
    );
    $kelurahan_attr = array(
        'id' => 'kelurahan',
        'name' => 'kelurahan',
        'class' => 'input-medium',
        'style' => 'text-transform : uppercase;',
        'placeholder' => 'Kelurahan'
    );
    $kecamatan_attr = array(
        'id' => 'kecamatan',
        'name' => 'kecamatan',
        'class' => 'input-medium',
        'style' => 'text-transform : uppercase;',
        'placeholder' => 'Kecamatan'
    );
    echo form_open('master/musrenbang_usulan/search/') .
    form_input($skpd_attr) . ' ' .
    form_input($nama_kegiatan_attr) . ' ' .
    form_input($kelurahan_attr) . ' ' .
    form_input($kecamatan_attr) . ' ' .
    form_submit('cari', 'CARI', 'class="btn btn-mini"') .
    form_close();
    ?>
</div>

<?php $this->load->view('_shared/footer'); ?>