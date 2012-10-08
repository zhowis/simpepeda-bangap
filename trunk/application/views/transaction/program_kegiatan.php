<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
?>

<div class="container-fluid form-inline well" id="musrenbang_usulan-search">
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
    echo form_open('transaction/musrenbang_usulan/search/') .
    form_input($skpd_attr) . ' ' .
    form_input($nama_kegiatan_attr) . ' ' .
    form_input($kelurahan_attr) . ' ' .
    form_input($kecamatan_attr) . ' ' .
    form_submit('cari', 'CARI', 'class="btn btn-mini"') .
    form_close();
    ?>
</div>

<?php if ($pagination): ?>
    <div class="container">
        <div class="pagination pagination-centered">
            <?php echo $pagination; ?>
        </div>
    </div>
<?php endif; ?>

<table class="table table-bordered table-striped container-full data_list" id="musrenbang_usulan" controller="transaction">
    <thead>
        <tr>
            <th>Nomor Kegiatan</th>
            <th>Nama Kegiatan</th>
            <th>Nama Pengusul</th>
            <th>SKPD</th>
            <th>Kecamatan</th>
            <th>Kelurahan</th>
            <th>Dimensi Volume</th>
            <th>Usulan Dana</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($results->result() as $row) {
            echo '<tr id="' . $row->id . '">
              <td>' . $row->nomor_kegiatan . '</td>
              <td>' . $row->nama_kegiatan . '</td>
              <td>' . $row->nama_pengusul . '</td>
              <td>' . $row->skpd . '</td>               
              <td>' . $row->kecamatan . '</td>
              <td>' . $row->desa_kelurahan . '</td>
              <td>' . $row->volume_dimensi . '</td>   
              <td>' . $row->usulan_dana . '</td>
             </tr>
          ';
        }
        ?>
    </tbody>
</table>

<?php if ($pagination): ?>
    <div class="container">
        <div class="pagination pagination-centered">
            <?php echo $pagination; ?>
        </div>
    </div>
<?php endif; ?>

<?php $this->load->view('_shared/footer'); ?>