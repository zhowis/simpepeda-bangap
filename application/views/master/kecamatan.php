 <?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
?>

<div class="container-fluid form-inline well" id="kecamatan-search">
    <?php
    $kecamatan_attr = array(
        'id' => 'kecamatan',
        'name' => 'kecamatan',
        'class' => 'input-medium',
        'style' => 'text-transform : uppercase;',
        'placeholder' => 'Kecamatan'
    );
    echo form_open('master/kecamatan/search/') .
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

<table class="table table-bordered table-striped container-full data_list" id="kecamatan" controller="master">
    <thead>
        <tr>
            <th>Kecamatan</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($results->result() as $row) {
            echo '<tr id="' . $row->id . '">
              <td>' . $row->kecamatan . '</td>
              <td>' . $row->keterangan . '</td>    
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