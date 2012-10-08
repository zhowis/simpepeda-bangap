<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "login";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */

/*
 * CUSTOM
 */

$route['transaction/letter_of_authority'] = "letter_of_authority";
$route['transaction/letter_of_authority/view/(:any)'] = "letter_of_authority/index/$1";
$route['transaction/letter_of_authority/(:any)/(:any)/(:any)'] = "letter_of_authority/index/$1/$2/$3";
$route['transaction/letter_of_authority/(:any)/info'] = "letter_of_authority/info/$1";
$route['transaction/letter_of_authority/(:any)/delete'] = "letter_of_authority/delete/$1";
$route['transaction/letter_of_authority/(:any)/print'] = "letter_of_authority/print_preview/$1";
$route['transaction/letter_of_authority/create'] = "letter_of_authority/create";
$route['transaction/letter_of_authority/search'] = "letter_of_authority/search";

$route['transaction/dropoff'] = "dropoff";
$route['transaction/dropoff/view/(:any)'] = "dropoff/index/$1";
$route['transaction/dropoff/(:any)/info'] = "dropoff/info/$1";
$route['transaction/dropoff/(:any)/delete'] = "dropoff/delete/$1";
$route['transaction/dropoff/(:any)/(:any)/(:any)'] = "dropoff/index/$1/$2/$3";
$route['transaction/dropoff/create/(:any)'] = "dropoff/create/$1";
$route['transaction/dropoff/create'] = "dropoff/create";
$route['transaction/dropoff/search'] = "dropoff/search";

$route['master/type/(:num)/swap'] = "type/swap/$1";

//MEMBER
$route['master/member'] = "member";
$route['master/member/view/(:any)'] = "member/index/$1";
$route['master/member/(:any)/info'] = "member/info/$1";
$route['master/member/(:any)/edit'] = "member/edit/$1";
$route['master/member/(:any)/delete'] = "member/delete/$1";
$route['master/member/(:any)/(:any)/(:any)'] = "member/index/$1/$2/$3";
$route['master/member/create/(:any)'] = "member/create/$1";
$route['master/member/create'] = "member/create";
$route['master/member/search'] = "member/search";
//END

//DESA_KELURAHAN
$route['master/desa_kelurahan'] = "desa_kelurahan";
$route['master/desa_kelurahan/view/(:any)'] = "desa_kelurahan/index/$1";
$route['master/desa_kelurahan/(:any)/info'] = "desa_kelurahan/info/$1";
$route['master/desa_kelurahan/(:any)/edit'] = "desa_kelurahan/edit/$1";
$route['master/desa_kelurahan/(:any)/delete'] = "desa_kelurahan/delete/$1";
$route['master/desa_kelurahan/(:any)/(:any)/(:any)'] = "desa_kelurahan/index/$1/$2/$3";
$route['master/desa_kelurahan/create/(:any)'] = "desa_kelurahan/create/$1";
$route['master/desa_kelurahan/create'] = "desa_kelurahan/create";
$route['master/desa_kelurahan/search'] = "desa_kelurahan/search";
//END

//KECAMATAN
$route['master/kecamatan'] = "kecamatan";
$route['master/kecamatan/view/(:any)'] = "kecamatan/index/$1";
$route['master/kecamatan/(:any)/info'] = "kecamatan/info/$1";
$route['master/kecamatan/(:any)/edit'] = "kecamatan/edit/$1";
$route['master/kecamatan/(:any)/delete'] = "kecamatan/delete/$1";
$route['master/kecamatan/(:any)/(:any)/(:any)'] = "kecamatan/index/$1/$2/$3";
$route['master/kecamatan/create/(:any)'] = "kecamatan/create/$1";
$route['master/kecamatan/create'] = "kecamatan/create";
$route['master/kecamatan/search'] = "kecamatan/search";
//END

//KATEGORI SKPD
$route['master/kategori_skpd'] = "kategori_skpd";
$route['master/kategori_skpd/view/(:any)'] = "kategori_skpd/index/$1";
$route['master/kategori_skpd/(:any)/info'] = "kategori_skpd/info/$1";
$route['master/kategori_skpd/(:any)/edit'] = "kategori_skpd/edit/$1";
$route['master/kategori_skpd/(:any)/delete'] = "kategori_skpd/delete/$1";
$route['master/kategori_skpd/(:any)/(:any)/(:any)'] = "kategori_skpd/index/$1/$2/$3";
$route['master/kategori_skpd/create/(:any)'] = "kategori_skpd/create/$1";
$route['master/kategori_skpd/create'] = "kategori_skpd/create";
$route['master/kategori_skpd/search'] = "kategori_skpd/search";
//END

//SKPD
$route['master/skpd'] = "skpd";
$route['master/skpd/view/(:any)'] = "skpd/index/$1";
$route['master/skpd/(:any)/info'] = "skpd/info/$1";
$route['master/skpd/(:any)/edit'] = "skpd/edit/$1";
$route['master/skpd/(:any)/delete'] = "skpd/delete/$1";
$route['master/skpd/(:any)/(:any)/(:any)'] = "skpd/index/$1/$2/$3";
$route['master/skpd/create/(:any)'] = "skpd/create/$1";
$route['master/skpd/create'] = "skpd/create";
$route['master/skpd/search'] = "skpd/search";
//END

//STATUS PRIORITAS
$route['master/status_prioritas'] = "status_prioritas";
$route['master/status_prioritas/view/(:any)'] = "status_prioritas/index/$1";
$route['master/status_prioritas/(:any)/info'] = "status_prioritas/info/$1";
$route['master/status_prioritas/(:any)/edit'] = "status_prioritas/edit/$1";
$route['master/status_prioritas/(:any)/delete'] = "status_prioritas/delete/$1";
$route['master/status_prioritas/(:any)/(:any)/(:any)'] = "status_prioritas/index/$1/$2/$3";
$route['master/status_prioritas/create/(:any)'] = "status_prioritas/create/$1";
$route['master/status_prioritas/create'] = "status_prioritas/create";
$route['master/status_prioritas/search'] = "status_prioritas/search";
//END

//STATUS KEGIATAN
$route['master/status_kegiatan'] = "status_kegiatan";
$route['master/status_kegiatan/view/(:any)'] = "status_kegiatan/index/$1";
$route['master/status_kegiatan/(:any)/info'] = "status_kegiatan/info/$10 ";
$route['master/status_kegiatan/(:any)/edit'] = "status_kegiatan/edit/$1";
$route['master/status_kegiatan/(:any)/delete'] = "status_kegiatan/delete/$1";
$route['master/status_kegiatan/(:any)/(:any)/(:any)'] = "status_kegiatan/index/$1/$2/$3";
$route['master/status_kegiatan/create/(:any)'] = "status_kegiatan/create/$1";
$route['master/status_kegiatan/create'] = "status_kegiatan/create";
$route['master/status_kegiatan/search'] = "status_kegiatan/search";
//END

//STATUS PRIORITAS
$route['master/status_prioritas'] = "status_prioritas";
$route['master/status_prioritas/view/(:any)'] = "status_prioritas/index/$1";
$route['master/status_prioritas/(:any)/info'] = "status_prioritas/info/$1";
$route['master/status_prioritas/(:any)/edit'] = "status_prioritas/edit/$1";
$route['master/status_prioritas/(:any)/delete'] = "status_prioritas/delete/$1";
$route['master/status_prioritas/(:any)/(:any)/(:any)'] = "status_prioritas/index/$1/$2/$3";
$route['master/status_prioritas/create/(:any)'] = "status_prioritas/create/$1";
$route['master/status_prioritas/create'] = "status_prioritas/create";
$route['master/status_prioritas/search'] = "status_prioritas/search";
//END

//STATUS PELAKSANAAN
$route['master/status_pelaksanaan'] = "status_pelaksanaan";
$route['master/status_pelaksanaan/view/(:any)'] = "status_pelaksanaan/index/$1";
$route['master/status_pelaksanaan/(:any)/info'] = "status_pelaksanaan/info/$1";
$route['master/status_pelaksanaan/(:any)/edit'] = "status_pelaksanaan/edit/$1";
$route['master/status_pelaksanaan/(:any)/delete'] = "status_pelaksanaan/delete/$1";
$route['master/status_pelaksanaan/(:any)/(:any)/(:any)'] = "status_pelaksanaan/index/$1/$2/$3";
$route['master/status_pelaksanaan/create/(:any)'] = "status_pelaksanaan/create/$1";
$route['master/status_pelaksanaan/create'] = "status_pelaksanaan/create";
$route['master/status_pelaksanaan/search'] = "status_pelaksanaan/search";
//END

//STATUS MUSRENBANG
$route['master/status_musrenbang'] = "status_musrenbang";
$route['master/status_musrenbang/view/(:any)'] = "status_musrenbang/index/$1";
$route['master/status_musrenbang/(:any)/info'] = "status_musrenbang/info/$1";
$route['master/status_musrenbang/(:any)/edit'] = "status_musrenbang/edit/$1";
$route['master/status_musrenbang/(:any)/delete'] = "status_musrenbang/delete/$1";
$route['master/status_musrenbang/(:any)/(:any)/(:any)'] = "status_musrenbang/index/$1/$2/$3";
$route['master/status_musrenbang/create/(:any)'] = "status_musrenbang/create/$1";
$route['master/status_musrenbang/create'] = "status_musrenbang/create";
$route['master/status_musrenbang/search'] = "status_musrenbang/search";
//END

//MUSRENBANG USULAN
$route['transaction/musrenbang_usulan'] = "musrenbang_usulan";
$route['transaction/musrenbang_usulan/view/(:any)'] = "musrenbang_usulan/index/$1";
$route['transaction/musrenbang_usulan/(:any)/info'] = "musrenbang_usulan/info/$1";
$route['transaction/musrenbang_usulan/(:any)/edit'] = "musrenbang_usulan/edit/$1";
$route['transaction/musrenbang_usulan/(:any)/delete'] = "musrenbang_usulan/delete/$1";
$route['transaction/musrenbang_usulan/(:any)/(:any)/(:any)'] = "musrenbang_usulan/index/$1/$2/$3";
$route['transaction/musrenbang_usulan/create/(:any)'] = "musrenbang_usulan/create/$1";
$route['transaction/musrenbang_usulan/create'] = "musrenbang_usulan/create";
$route['transaction/musrenbang_usulan/search'] = "musrenbang_usulan/search";
//END

//MUSRENBANG LAPORAN
$route['laporan/musrenbang_laporan'] = "musrenbang_laporan";
$route['laporan/musrenbang_laporan/view/(:any)'] = "musrenbang_laporan/index/$1";
$route['laporan/musrenbang_laporan/(:any)/info'] = "musrenbang_laporan/info/$1";
$route['laporan/musrenbang_laporan/(:any)/edit'] = "musrenbang_laporan/edit/$1";
$route['laporan/musrenbang_laporan/(:any)/delete'] = "musrenbang_laporan/delete/$1";
$route['laporan/musrenbang_laporan/(:any)/(:any)/(:any)'] = "musrenbang_laporan/index/$1/$2/$3";
$route['laporan/musrenbang_laporan/create/(:any)'] = "musrenbang_laporan/create/$1";
$route['laporan/musrenbang_laporan/create'] = "musrenbang_laporan/create";
$route['laporan/musrenbang_laporan/search'] = "musrenbang_laporan/search";
//END