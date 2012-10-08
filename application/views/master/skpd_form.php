<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');

//label
$control_label = array(
    'class' => 'control-label'
);

$skpd_attr = array(
    'name' => 'skpd',
    'class' => 'input-medium',
    'value' => set_value('skpd', $skpd),
    'autocomplete' => 'off',
    'style' => 'text-transform : uppercase'
);

$kategori_skpd_attr = array(
    'name' => 'kategori_skpd',
    'class' => 'input-medium',
    'value' => set_value('kategori_skpd', $kategori_skpd),
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
<div class="container-full" id="skpd">
    <?= form_open($action_url, array('class' => 'form-horizontal')); ?>

    <div class="control-group">
        <?= form_label('SKPD' . required(), 'skpd', $control_label); ?>
        <div class="controls">
            <?= form_input($skpd_attr) ?>
            <p class="help-block"><?php echo form_error('skpd') ?></p>
        </div>
    </div>
    
     <div class="control-group">
        <?= form_label('Kategoi SKPD' . required(), 'kategori_skpd', $control_label); ?>
        <div class="controls">
            <?= form_input($kategori_skpd_attr) ?>
            <p class="help-block"><?php echo form_error('kategori_skpd') ?></p>
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