<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');

//label
$control_label = array(
    'class' => 'control-label'
);

$desa_kelurahan_attr = array(
    'name' => 'desa_kelurahan',
    'class' => 'input-medium',
    'value' => set_value('desa_kelurahan', $desa_kelurahan),
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


?>
<div class="container-full" id="desa_kelurahan">
    <?= form_open($action_url, array('class' => 'form-horizontal')); ?>

    <div class="control-group">
        <?= form_label('Desa/kelurahan' . required(), 'desa_kelurahan', $control_label); ?>
        <div class="controls">
            <?= form_input($desa_kelurahan_attr) ?>
            <p class="help-block"><?php echo form_error('desa_kelurahan') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Kecamatan' . required(), 'kecamatan_id', $control_label); ?>
        <div class="controls">
            <?= form_dropdown('kecamatan_id', $kecamatan_data, set_value('kecamatan_id', $kecamatan_id), 'class="input-medium"') ?>
            <p class="help-block"><?php echo form_error('kecamatan_id') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Keterangan', 'keterangan', $control_label); ?>
        <div class="controls">
            <?= form_textarea($keterangan_attr) ?>
            <p class="help-block"><?php echo form_error('keterangan') ?></p>
        </div>
    </div>

    <div class="form-actions well">
        <button class="btn btn-small btn-primary" type="submit">Simpan</button>
    </div>
    <?php form_close() ?>
</div>
<?php $this->load->view('_shared/footer'); ?>