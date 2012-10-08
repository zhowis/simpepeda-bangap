<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
?>

<div class="container-fluid form-inline well" id="skpd-search">
    <?php
    $skpd_attr = array(
        'id' => 'skpd',
        'name' => 'skpd',
        'class' => 'input-medium',
        'style' => 'text-transform : uppercase;',
        'placeholder' => 'SKPD'
    );
    echo form_open('master/skpd/search/') .
    form_input($skpd_attr) . ' ' .
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

<table class="table table-bordered table-striped container-full data_list" id="skpd" controller="master">
    <thead>
        <tr>
            <th>SKPD</th>
            <th>Kategori SKPD</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($results->result() as $row) {
            echo '<tr id="' . $row->id . '">
              <td>' . $row->skpd . '</td>
              <td>' . $row->kategori_skpd . '</td>     
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