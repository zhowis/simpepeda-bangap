<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');

//label
$control_label = array(
    'class' => 'control-label'
);

$status_kegiatan_attr = array(
    'name' => 'status_kegiatan',
    'class' => 'input-medium',
    'value' => set_value('status_kegiatan', $status_kegiatan),
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

?>
<div class="container-full" id="status_prioritas">
    <?= form_open($action_url, array('class' => 'form-horizontal')); ?>

    <div class="control-group">
        <?= form_label('Status Kegiatan' . required(), 'status_kegiatan', $control_label); ?>
        <div class="controls">
            <?= form_input($status_kegiatan_attr) ?>
            <p class="help-block"><?php echo form_error('status_kegiatan') ?></p>
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