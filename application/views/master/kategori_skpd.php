<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
?>

<div class="container-fluid form-inline well" id="kategori_skpd-search">
    <?php
    $kategori_skpd_attr = array(
        'id' => 'kategori_skpd',
        'name' => 'kategori_skpd',
        'class' => 'input-medium',
        'style' => 'text-transform : uppercase;',
        'placeholder' => 'Kategori SKPD'
    );
    echo form_open('master/kategori_skpd/search/') .
    form_input($kategori_skpd_attr) . ' ' .
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

<table class="table table-bordered table-striped container-full data_list" id="kategori_skpd" controller="master">
    <thead>
        <tr>
            <th>Kategori SKPD</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($results->result() as $row) {
            echo '<tr id="' . $row->id . '">
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