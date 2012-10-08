<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');

//label
$control_label = array(
    'class' => 'control-label'
);

$status_pelaksanaan_attr = array(
    'name' => 'status_pelaksanaan',
    'class' => 'input-medium',
    'value' => set_value('status_pelaksanaan', $status_pelaksanaan),
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
        <?= form_label('Status Pelaksanaan' . required(), 'status_pelaksanaan', $control_label); ?>
        <div class="controls">
            <?= form_input($status_pelaksanaan_attr) ?>
            <p class="help-block"><?php echo form_error('status_pelaksanaan') ?></p>
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