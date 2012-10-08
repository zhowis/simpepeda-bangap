<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
?>
<div class="container-full form-horizontal" id="branch">
    <table cellspacing="0" cellpadding="0" border="0" class="table table-bordered span4">
        <thead>
            <tr>
                    <th>Plural</th>
                    <th>Singular</th>
                </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row) {

                echo '<tr>
                    <td>'.$row->plural.'</td>
                    <td>'.singular($row->plural).'</td>
                </tr>';
            } ?>
        </tbody>
    </table>
</div>
<?php $this->load->view('_shared/footer'); ?>