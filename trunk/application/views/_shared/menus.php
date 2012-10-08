<?php
if (!$this->session->userdata('logged_in'))
    redirect('login');

$myrole_raw = $auth->my_role();
$this->myrole_ID = $myrole_raw['id'];
$this->myrole_NAME = $myrole_raw['name'];

$primary_nav = $this->uri->segment(1);
$secondary_nav = $this->uri->segment(2);

$icon_color = 'icon-grey';
?> 
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a href="#" class="brand">SIMPEPEDA App&trade;</a>
            <ul class="nav">
                <?php if ($auth->has_capability('dashboard_menu')): ?>
                    <li class="<?= isActive($primary_nav, 'home') ?>"><?= anchor('home', isIconActive($primary_nav, 'home', 'icon-home')) ?></li>
                <?php endif; ?>

                <li class="<?= isActive($primary_nav, 'kecamatan'); ?>"><?= anchor('master', isIconActive($primary_nav, 'kecamatan', 'icon-hdd') . ' Master'); ?></li>

                <li class="<?= ($this->uri->segment(1) == 'transaction') ? 'active' : ''; ?>"><?= anchor('transaction', isIconActive($primary_nav, 'transaction', 'icon-list-alt') . ' Transaction'); ?></li>

                <li class="<?= isActive($primary_nav, 'laporan'); ?>"><?= anchor('laporan', isIconActive($primary_nav, 'laporan', 'icon-info-sign') . ' Laporan'); ?></li>

                <li class="<?= isActive($primary_nav, 'guidance') ? 'active' : ''; ?>"><?= anchor('guidance', isIconActive($primary_nav, 'guidance', 'icon-question-sign') . 'Bantuan'); ?></li>

                <li class="<?= isActive($primary_nav, 'admin'); ?>"><?= anchor('admin', isIconActive($primary_nav, 'admin', 'icon-cog') . ' Admin'); ?></li>              
 
            </ul>
            <ul class="nav pull-right">
                <li class="dropdown <?= ($primary_nav == 'profile') ? 'active' : ''; ?>">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><icon class="icon-user icon-grey"></icon> <?= $auth->logged_in(); ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><?= anchor('home/logout', 'Logout'); ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="container-fluid" id="main-container">
    <?php
    //buat dinamis next version ya!!!
    switch ($primary_nav) {
        case 'master':
            ?>
            <div class="subnav subnav-fixed">
                <ul class="nav nav-pills">
                    <li class="dropdown <?= ($secondary_nav == 'kategori_skpd' || $secondary_nav == 'skpd') ? 'active' : ''; ?>">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">SKPD<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="<?= isActive($secondary_nav, 'kategori_skpd'); ?>"><?= anchor('master/kategori_skpd', 'Kategori SKPD'); ?></li>            
                            <li class="<?= isActive($secondary_nav, 'skpd'); ?>"><?= anchor('master/skpd', 'SKPD'); ?></li>
                        </ul>
                    </li>
                    <li class="<?= isActive($secondary_nav, 'desa_kelurahan'); ?>"><?= anchor('master/desa_kelurahan', 'Desa/Kelurahan'); ?></li>
                    <li class="<?= isActive($secondary_nav, 'kecamatan'); ?>"><?= anchor('master/kecamatan', 'Kecamatan'); ?></li>
                    <li class="<?= isActive($secondary_nav, 'penduduk'); ?>"><?= anchor('master/penduduk', 'Penduduk'); ?></li>
                    <li class="<?= isActive($secondary_nav, 'program'); ?>"><?= anchor('master/program', 'Program'); ?></li>
                    <li class="dropdown <?= ($secondary_nav == 'status_prioritas' || $secondary_nav == 'status_kegiatan' || $secondary_nav == 'status_pelaksanaan' || $secondary_nav == 'status_musrenbang') ? 'active' : ''; ?>">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Setting Parameter<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="<?= isActive($secondary_nav, 'status_prioritas'); ?>"><?= anchor('master/status_prioritas', 'Status Prioritas'); ?></li>  
                            <li class="<?= isActive($secondary_nav, 'status_kegiatan'); ?>"><?= anchor('master/status_kegiatan', 'Status Kegiatan'); ?></li>    
                            <li class="<?= isActive($secondary_nav, 'status_pelaksanaan'); ?>"><?= anchor('master/status_pelaksanaan', 'Status Pelaksanaan'); ?></li>                           
                            <li class="<?= isActive($secondary_nav, 'status_musrenbang'); ?>"><?= anchor('master/status_musrenbang', 'Status Musrenbang'); ?></li>    
                            <li class="<?= isActive($secondary_nav, 'status_tahun_perencanaan'); ?>"><?= anchor('master/status_tahun_perencanaan', 'Status Tahun Perencanaan'); ?></li>                           
                        </ul>
                    </li>  
                </ul>
            </div><?php
        break;
    case 'transaction':
            ?>
            <div class="subnav subnav-fixed">
                <ul class="nav nav-pills">
                    <li class="<?= isActive($secondary_nav, 'musrenbang_usulan'); ?>"><?= anchor('transaction/musrenbang_usulan', 'Program Usulan'); ?></li>                  
                </ul>
            </div>
            <?php
            break;
        case 'admin':
            ?>
            <div class="subnav subnav-fixed">
                <ul class="nav nav-pills">
                    <li class="<?= isActive($secondary_nav, 'users') ? 'active' : ''; ?>"><?= anchor('admin/users', 'Users'); ?></li>
                    <li class="<?= isActive($secondary_nav, 'roles') ? 'active' : ''; ?>"><?= anchor('admin/roles', 'Roles'); ?></li>
                    <li class="<?= isActive($secondary_nav, 'capabilities') ? 'active' : ''; ?>"><?= anchor('admin/capabilities', 'Capabilities'); ?></li>
                    <li class="<?= isActive($secondary_nav, 'options') ? 'active' : ''; ?>"><?= anchor('admin/options', 'Options'); ?></li>
                </ul>
            </div>
            <?php
            break;
        case 'laporan':
            ?>
            <div class="subnav subnav-fixed">           
                <ul class="nav nav-pills">
                    <li class="dropdown <?= ($secondary_nav == 'jangka_pendek' || $secondary_nav == 'jangka_menengah' || $secondary_nav == 'jangka_panjang') ? 'active' : ''; ?>">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Rencana Pembangunan<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="<?= isActive($secondary_nav, 'jangka_pendek'); ?>"><?= anchor('laporan/jangka_pendek', 'Jangka Pendek'); ?></li>            
                            <li class="<?= isActive($secondary_nav, 'jangka_menengah'); ?>"><?= anchor('laporan/jangka_menengah', 'Jangka Menengah'); ?></li>
                            <li class="<?= isActive($secondary_nav, 'jangka_panjang'); ?>"><?= anchor('laporan/jangka_panjang', 'Jangka Panjang'); ?></li>
                        </ul>
                    </li>
                </ul>

            </div>
            <?php
            break;
    }
    ?>
    <?php if (isset($page_title)): ?>
        <div class="well">
            <b class="pull-left"><?php
    $num_results = (isset($num_results)) ? ' <span class="badge"> ' . $num_results . ' </span> ' : ' ';
    echo (isset($page_title)) ? $num_results . strtoupper($page_title) : '';
        ?></b>
            <div class="pull-right">
                <div class="btn-group">
                    <?php
                    if (isset($tools)) {
                        foreach ($tools as $url => $link_label) {
                            $icon = '';
                            $anchor_attr = 'class="btn btn-mini"';
                            switch (strtolower($link_label)) {
                                case 'new':$icon = 'icon-plus';
                                    break;
                                case 'back':$icon = 'icon-arrow-left';
                                    break;
                                case 'edit':$icon = 'icon-pencil';
                                    break;
                                case 'delete':
                                    $icon = 'icon-trash';
                                    $anchor_attr = 'class="btn btn-mini" onclick="return confirm_it(\'Anda yakin akan menghapus data ini ?\');" ';
                                    break;
                                case 'batal':
                                    $icon = 'icon-trash';
                                    $anchor_attr = 'class="btn btn-mini" onclick="return confirm_it(\'Anda yakin ingin membatalkan WO ini ?\');" ';
                                    break;
                            }
                            echo anchor(site_url($url), '<i class="' . $icon . '"></i> ' . $link_label, $anchor_attr);
                        }
                    }
                    ?>
                </div>
            </div>
            <?php if (isset($group_by)): //sementara ditutup, diset di controller  ?>
                <ul class="nav nav-pills pull-left centered">
                    <li <?php if ($sort_by == 'no_polisi') echo 'class="active"'; ?>><?= anchor('master/kendaraan', 'Kendaraan [ ' . $count_kendaraan . ' ]'); ?></li>
                    <li <?php if ($sort_by == 'merk_id') echo 'class="active"'; ?>><?= anchor('master/kendaraan/view/1/merk_id', 'Merk [ ' . $count_merk . ' ]'); ?></li>
                    <li <?php if ($sort_by == 'jenis_id') echo 'class="active"'; ?>><?= anchor('master/kendaraan/view/1/jenis_id', 'Jenis [ ' . $count_jenis . ' ]'); ?></li>
                </ul> 
            <?php endif; ?>
        </div>

    <?php endif; ?>