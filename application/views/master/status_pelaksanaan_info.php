<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
//label
$control_label = array(
    'class' => 'control-label'
);
?>
<div class="container-full form-horizontal" id="status_pelaksanaan">
    <table cellspacing="0" cellpadding="0" border="0" class="table table-bordered span4">
        <tbody>
            <tr>
                <th class="span2">Status Pelaksanaan</th>
                <td><?= $status_pelaksanaan ?></td>
            </tr>
            <tr>
                <th class="span2">Keterangan</th>
                <td><?= $keterangan ?></td>
            </tr>
        </tbody>
    </table>
</div>

<?php $this->load->view('_shared/footer'); ?>