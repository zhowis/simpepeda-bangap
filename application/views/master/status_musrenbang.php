<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
?>

<div class="container-fluid form-inline well" id="status_musrenbang-search">
    <?php
    $status_musrenbang_attr = array(
        'id' => 'status_musrenbang',
        'name' => 'status_musrenbang',
        'class' => 'input-medium',
        'style' => 'text-transform : uppercase;',
        'placeholder' => 'Status MUSRENBANG'
    );
    echo form_open('master/status_musrenbang/search/') .
    form_input($status_musrenbang_attr) . ' ' .
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

<table class="table table-bordered table-striped container-full data_list" id="status_musrenbang" controller="master">
    <thead>
        <tr>
            <th>Status MUSRENBANG</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($results->result() as $row) {
            echo '<tr id="' . $row->id . '">
              <td>' . $row->status_musrenbang . '</td>
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