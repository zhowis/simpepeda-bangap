<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');

//label
$control_label = array(
    'class' => 'control-label'
);

$nomor_kegiatan_attr = array(
    'name' => 'nomor_kegiatan',
    'class' => 'input-small',
    'value' => set_value('nomor_kegiatan', $nomor_kegiatan),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$nama_pengusul_attr = array(
    'name' => 'nama_pengusul',
    'class' => 'input-medium',
    'value' => set_value('nama_pengusul', $nama_pengusul),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$nama_kegiatan_attr = array(
    'name' => 'nama_kegiatan',
    'class' => 'input-large',
    'value' => set_value('nama_kegiatan', $nama_kegiatan),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$detail_lokasi_attr = array(
    'name' => 'detail_lokasi',
    'class' => 'span3',
    'value' => set_value('detail_lokasi', $detail_lokasi),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$volume_dimensi_attr = array(
    'name' => 'volume_dimensi',
    'class' => 'input-medium',
    'value' => set_value('volume_dimensi', $volume_dimensi),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$usulan_dana_attr = array(
    'name' => 'usulan_dana',
    'class' => 'input-medium',
    'value' => set_value('usulan_dana', $usulan_dana),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$sasaran_attr = array(
    'name' => 'sasaran',
    'class' => 'input-large',
    'value' => set_value('sasaran', $sasaran),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$target_attr = array(
    'name' => 'target',
    'class' => 'input-large',
    'value' => set_value('target', $target),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$keterangan_attr = array(
    'name' => 'keterangan',
    'class' => 'span3',
    'value' => set_value('keterangan', $keterangan),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$kecamatan_data[0] = '-PILIH-';
foreach ($kecamatan_options as $row) {
    $kecamatan_data[$row->id] = $row->kecamatan;
}

$desa_kelurahan_data[0] = '-PILIH-';
foreach ($desa_kelurahan_options as $row) {
    $desa_kelurahan_data[$row->id] = $row->desa_kelurahan;
}

$status_kegiatan_data[0] = '-PILIH-';
foreach ($status_kegiatan_options as $row) {
    $status_kegiatan_data[$row->id] = $row->status_kegiatan;
}

$status_prioritas_data[0] = '-PILIH-';
foreach ($status_prioritas_options as $row) {
    $status_prioritas_data[$row->id] = $row->status_prioritas;
}

$skpd_data[0] = '-PILIH-';
foreach ($skpd_options as $row) {
    $skpd_data[$row->id] = $row->skpd;
}

$status_musrenbang_data[0] = '-PILIH-';
foreach ($status_musrenbang_options as $row) {
    $status_musrenbang_data[$row->id] = $row->status_musrenbang;
}
?>
<div class="container-full" id="musrenbang_usulan">
    <?= form_open($action_url, array('class' => 'form-horizontal')); ?>

    <div class="control-group">
        <?= form_label('Nomor Kegiatan' . required(), 'nomor_kegiatan', $control_label); ?>
        <div class="controls">
            <?= form_input($nomor_kegiatan_attr) ?>
            <p class="help-block"><?php echo form_error('nomor_kegiatan') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Nama Kegiatan' . required(), 'nama_kegiatan', $control_label); ?>
        <div class="controls">
            <?= form_input($nama_kegiatan_attr) ?>
            <p class="help-block"><?php echo form_error('nama_kegiatan') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Nama Pengusul' . required(), 'nama_pengusul', $control_label); ?>
        <div class="controls">
            <?= form_input($nama_pengusul_attr) ?>
            <p class="help-block"><?php echo form_error('nama_pengusul') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('SKPD' . required(), 'skpd_id', $control_label); ?>
        <div class="controls">
            <?= form_dropdown('skpd_id', $skpd_data, set_value('skpd_id', $skpd_id), 'class="input-medium"') ?>
            <p class="help-block"><?php echo form_error('skpd_id') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Kecamatan' . required(), 'kecamatan', $control_label); ?>
        <div class="controls">
            <?= form_dropdown('kecamatan_id', $kecamatan_data, set_value('kecamatan_id', $kecamatan_id), 'class="input-medium"') ?>
            <p class="help-block"><?php echo form_error('kecamatan') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Desa/Kelurahan' . required(), 'desa_kelurahan', $control_label); ?>
        <div class="controls">
            <?= form_dropdown('desa_kelurahan_id', $desa_kelurahan_data, set_value('desa_kelurahan_id', $desa_kelurahan_id), 'class="input-medium"') ?>
            <p class="help-block"><?php echo form_error('desa_kelurahan') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Tipe Kegiatan' . required(), 'status_kegiatan', $control_label); ?>
        <div class="controls">
            <?= form_dropdown('status_kegiatan_id', $status_kegiatan_data, set_value('status_kegiatan_id', $status_kegiatan_id), 'class="input-medium"') ?>
            <p class="help-block"><?php echo form_error('status_kegiatan') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Tipe Prioritas' . required(), 'status_prioritas', $control_label); ?>
        <div class="controls">
            <?= form_dropdown('status_prioritas_id', $status_prioritas_data, set_value('status_prioritas_id', $status_prioritas_id), 'class="input-medium"') ?>
            <p class="help-block"><?php echo form_error('status_prioritas') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Detail Lokasi', 'detail_lokasi', $control_label); ?>
        <div class="controls">
            <?= form_textarea($detail_lokasi_attr) ?>
            <p class="help-block"><?php echo form_error('detail_lokasi') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Volume Dimensi' . required(), 'volume_dimensi', $control_label); ?>
        <div class="controls">
            <?= form_input($volume_dimensi_attr) ?>
            <p class="help-block"><?php echo form_error('volume_dimensi') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Usulan Dana' . required(), 'usulan_dana', $control_label); ?>
        <div class="controls">
            <?= form_input($usulan_dana_attr) ?>
            <p class="help-block"><?php echo form_error('usulan_dana') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Sasaran' . required(), 'sasaran', $control_label); ?>
        <div class="controls">
            <?= form_input($sasaran_attr) ?>
            <p class="help-block"><?php echo form_error('sasaran') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Target' . required(), 'target', $control_label); ?>
        <div class="controls">
            <?= form_input($target_attr) ?>
            <p class="help-block"><?php echo form_error('target') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Keterangan', 'keterangan', $control_label); ?>
        <div class="controls">
            <?= form_textarea($keterangan_attr) ?>
            <p class="help-block"><?php echo form_error('keterangan') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Status Musrenbang' . required(), 'status_musrenbang', $control_label); ?>
        <div class="controls">
            <?= form_dropdown('status_musrenbang_id', $status_musrenbang_data, set_value('status_musrenbang_id', $status_musrenbang_id), 'class="input-large"') ?>
            <p class="help-block"><?php echo form_error('status_musrenbang') ?></p>
        </div>
    </div>

    <div class="form-actions well">
        <button class="btn btn-small btn-primary" type="submit">Simpan</button>
    </div>
    <?php form_close() ?>
</div>
<?php $this->load->view('_shared/footer'); ?>