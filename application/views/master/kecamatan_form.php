<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');

//label
$control_label = array(
    'class' => 'control-label'
);

$kecamatan_attr = array(
    'name' => 'kecamatan',
    'class' => 'input-medium',
    'value' => set_value('kecamatan', $kecamatan),
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

$desa_kelurahan_data[0] = '-PILIH-';
foreach ($desa_kelurahan_options as $row) {
    $desa_kelurahan_data[$row->id] = $row->desa_kelurahan;
}
?>
<div class="container-full" id="kecamatan">
    <?= form_open($action_url, array('class' => 'form-horizontal')); ?>

    <div class="control-group">
        <?= form_label('Desa/Kelurahan' . required(), 'desa_kelurahan', $control_label); ?>
        <div class="controls">
            <?= form_dropdown('desa_kelurahan_id', $desa_kelurahan_data, set_value('desa_kelurahan_id', $desa_kelurahan_id), 'id="desa_kelurahan_id" class="input-medium" prevData-selected="' . set_value('desa_kelurahan_id', $desa_kelurahan_id) . '"') . '&nbsp;&nbsp;' ; ?>
            <a class="btn btn-mini" href="#" id="add-desa_kelurahan"><i class="icon-plus"></i> Pilih</a>
            <p class="help-block"><?php echo form_error('desa_kelurahan') ?></p>
        </div>
    </div>

    <div class="control-group">
        <?= form_label('Kecamatan' . required(), 'kecamatan', $control_label); ?>
        <div class="controls">
            <?= form_input($kecamatan_attr) ?>
            <p class="help-block"><?php echo form_error('nama_kecamatan') ?></p>
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