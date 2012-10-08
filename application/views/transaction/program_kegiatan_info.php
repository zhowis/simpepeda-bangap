<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
//label
$control_label = array(
    'class' => 'control-label'
);
?>
<div class="container-full form-horizontal" id="musrenbang_usulan">
    <table cellspacing="0" cellpadding="0" border="0" class="table table-bordered span4">
        <tbody>
            <tr>
                <th class="span2">Nomor Kegiatan</th>
                <td><?= $nomor_kegiatan ?></td>
            </tr>
            <tr>
                <th class="span2">Nama Kegiatan</th>
                <td><?= $nama_kegiatan ?></td>
            </tr>
            <tr>
                <th class="span2">Nama Pengusul</th>
                <td><?= $nama_pengusul ?></td>
            </tr>
            <tr>
                <th class="span2">SKPD</th>
                <td><?= $skpd ?></td>
            </tr>
            <tr>
                <th class="span2">Kecamatan</th>
                <td><?= $kecamatan ?></td>
            </tr>
            <tr>
                <th class="span2">Desa/Kelurahan</th>
                <td><?= $desa_kelurahan ?></td>
            </tr>
            <tr>
                <th class="span2">Detail Lokasi</th>
                <td><?= $detail_lokasi ?></td>
            </tr>
            <tr>
                <th class="span2">Volume Dimensi</th>
                <td><?= $volume_dimensi ?></td>
            </tr>
            <tr>
                <th class="span2">Usulan Dana</th>
                <td><?= $usulan_dana ?></td>
            </tr>
            <tr>
                <th class="span2">Tipe Kegiatan</th>
                <td><?= $status_kegiatan ?></td>
            </tr>
            <tr>
                <th class="span2">Tipe Prioritas</th>
                <td><?= $status_prioritas ?></td>
            </tr>
            <tr>
                <th class="span2">Usulan Dana</th>
                <td><?= $usulan_dana ?></td>
            </tr>
            <tr>
                <th class="span2">Keterangan</th>
                <td><?= $keterangan ?></td>
            </tr>
            <tr>
                <th class="span2">Status MUSRENBANG</th>
                <td><?= $status_musrenbang ?></td>
            </tr>
        </tbody>
    </table>
</div>

<?php $this->load->view('_shared/footer'); ?>