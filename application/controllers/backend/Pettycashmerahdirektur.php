<?php
class Pettycashmerahdirektur extends CI_Controller{
/**
* Description of Controller
*
* @author isnanw
*/
	function __construct(){
		parent::__construct();
		error_reporting(0);
		if($this->session->userdata('access') != "5"){
			$url=base_url('login_user');
            redirect($url);
		};
		$this->load->model('backend/pettycashmerahdirektur_model','pettycashmerahdirektur_model');
		$this->load->model('backend/Stock_model','stock_model');
		// $this->load->model('backend/Absensi_model','absensi_model');
		$this->load->model('Site_model','site_model');
		$this->load->helper('text');
		$this->load->helper('url');
		$this->load->helper('tanggal');
		$this->load->library('upload');
		$this->load->helper('form');
	}

	public function index(){
		$site = $this->site_model->get_site_data()->row_array();
		$x['site_title'] = $site['site_title'];
		$x['site_favicon'] = $site['site_favicon'];
		$x['images'] = $site['images'];
		$x['title'] = 'Release Pettycash || Bon Merah';
		$x['karyawan'] = $this->pettycashmerahdirektur_model->get_all_karyawan();
		$x['dataimage'] = $this->pettycashmerahdirektur_model->get_all_bukti();
		$this->load->view('backend/menu',$x);
		$this->load->view('backend/modal/pettycashmerahdirektur_modal');
		$this->load->view('backend/_partials/templatejs');
		$this->load->view('backend/v_pettycashmerahdirektur',$x);
	}
	public function get_ajax_list()
	{
		$id = $this->session->userdata('user_atasan');
		$list = $this->pettycashmerahdirektur_model->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $d) {
			$no++;
			$status = $d->status_bonmerah;
			$gambar = $d->imgbukti;
			$tanpagambar = '<span class="badge bg-light-danger">Belum Upload</span>';
			$adagambar = "<div class='row gallery' data-bs-toggle='modal' data-bs-target='#galleryModal$d->id_pettycash'><a href='#'><img class='w-50 active' src='../assets/images/fotobukti/$d->imgbukti' data-bs-target='#Gallerycarousel'></a></div>";

			if($this->session->userdata('access') == "5"){
					// $button = '<a href="'.base_url('backend/belanja/').$d->id_pettycash.'" class="btn icon icon-left btn-info" title="Rincian Belanja"><i class="bi bi-currency-dollar"></i></a>';
					$button1 = '<div class="button">
												<a class="btn icon icon-left btn-warning item_edit" href="javascript:void()" title="Upload Dokumen" onclick="edit_pettycash('."'".$d->id_pettycash."'".')"><i class="bi bi-upload"></i></a>
											</div>';
				}else{
					$button1 = '-';
			}

			if($gambar != NULL){
				$statusbukti = $adagambar;
			}else{
				$statusbukti = $tanpagambar;
			}

			if ($status == 'PENGAJUAN') {
				$class 		= 'lock';
				$ket 			= 'Pengajuan';
				$icon 		= 'arrow-up';
				$actket 	= 'Pengajuan';
				$actclass = 'warning';
				$button 	= '-';
				// $tgl			= '<small><b>'.$d->status.'</b>: '.format_indo(date($d->tgl_pettycash)).'</small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_bonmerah)).'</small>';
			}elseif ($status == 'DISETUJUI'){
				$class = 'lock';
				$ket = 'Disetujui';
				$icon = 'check';
				$actket = 'Disetujui';
				$actclass = 'success';
				// $button = '<small><span class="bg-light-success"><b>Disetujui</b>, Pada: <br>'.format_indo(date($d->tgl_pettycash_manajer)).'<br><b>'.$d->catatan_manajer.'</b></span></small>';
				$button = '<div class="button">
											<a class="btn icon icon-left btn-primary item_edit" href="javascript:void()" title="Edit" onclick="edit_pettycashmerahdirektur('."'".$d->id_pettycash."'".')"><i class="bi bi-pen-fill"></i> Rilis</a>
										</div>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_bonmerah)).'<br> <b>Disetujui</b>: '.format_indo(date($d->tgl_bonmerah_manajer)).'</small>';
			}elseif ($status == 'DITOLAK'){
				$class = 'unlock';
				$ket = 'Ditolak';
				$icon = 'exclamation';
				$actket = 'Ditolak';
				$actclass = 'danger';
				$button = '-';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_bonmerah)).'<br> <b>Disetujui</b>: '.format_indo(date($d->tgl_bonmerah_manajer)).'</small>';
			}elseif ($status == 'RILIS'){
				$class 		= 'unlock';
				$ket 			= 'Rilis';
				$icon 		= 'check2';
				$actket 	= 'Rilis';
				$actclass = 'primary';
				$button 	= '<small><span class="bg-light-primary"><b>Rilis</b>, Pada: <br>'.format_indo(date($d->tgl_bonmerah_direktur)).'<br><b>'.$d->catatan_direktur_bonmerah.'</b></span></small>';
				$tgl			= '<small><b>Dirilis</b>: <br>'.format_indo(date($d->tgl_pettycash_direktur)).'</small>';
			}else{
				$class 		= 'unlock';
				$ket 			= 'Rilis Bon Hijau';
				$icon 		= 'check';
				$actket 	= 'Rilis Bon Hijau';
				$actclass = 'success';
				$button 	= $button;
				$tgl			= '<small><b>Bon Hijau Dirilis</b>: <br>'.format_indo(date($d->tgl_pettycash_direktur)).'</small>';
			}

			$row = array();
			$row[] = $no;

			// $row[] = "<div class='row gallery' data-bs-toggle='modal' data-bs-target='#galleryModal$d->id_pettycash'><a href='#'><img class='w-100 active' src='../assets/images/fotobukti/$d->imgbukti' data-bs-target='#Gallerycarousel'></a></div>";
			$row[] = $tgl;
			$row[] = "Rp " . number_format($d->biaya_pettycash, 0, "", ",");
			$row[] = $d->ket_pettycash.''.'<br><small><span class="badge bg-light-warning">'.$d->namabagian.'</span></small>';
			$row[] = $d->user_name. '<br><span class="badge bg-light-warning">'.$d->namaatasan.'</span>' ;
			$row[] = '<div class="badge bg-light-'.$actclass.' color-'.$actclass.'"><i class="bi bi-'.$icon.'-circle"></i> '.$actket.'</div>'.$statusbukti.'';
			$row[] = $button;
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->pettycashmerahdirektur_model->count_all(),
						"recordsFiltered" => $this->pettycashmerahdirektur_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function ajax_edit($id_pettycash)
	{
		$data = $this->pettycashmerahdirektur_model->get_by_id($id_pettycash);
		echo json_encode($data);
	}

	function add(){

		$this->_validate();
		$biaya = $this->input->post('biaya',TRUE);
		$ket = $this->input->post('ket',TRUE);
		$biayashow = "Rp " . number_format($biaya, 0, "", ",");
		$users = $this->session->userdata('id');
		$user_atasan = $this->session->userdata('user_atasan');
		$arraysql = array(
				"ket_pettycash" => $this->input->post('ket',TRUE),
				"biaya_pettycash" => $this->input->post('biaya',TRUE),
				"id_user_pettycash" => $users,
				"status" => 'PENGAJUAN',
				"tgl_pettycash_manajer" => NULL,
				"tgl_pettycash_direktur" => NULL,
				"id_user_manager" => $user_atasan
		);
		$insert = $this->pettycashmerahdirektur_model->insert_pettycashmerahdirektur($arraysql);

		if($insert){
			// INSERT LOG
			$nama_users = $this->session->userdata('name');
			$b = '<b>'.$nama_users.'</b> Menambah pettycash Sebesar <b>'.$biayashow.'</b> Untuk Keperluan '.$ket;
			$data2 = array(
				'ket' => $b,
			);
			$this->stock_model->insert_log_stock($data2);
			// INSERT LOG
			echo json_encode(array("status" => TRUE));
		}else{
			echo json_encode(array("status" => FALSE));
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}

	}


	function edit() {
		$pettycashid=$this->input->post('id',TRUE);
		$this->_validate_edit();

		$id 		= $this->input->post('id',TRUE);
		$status = $this->input->post('pembahasan',TRUE);
		$catatan_manajer = $this->input->post('catatandirektur',TRUE);
		$biayashow = "Rp " . number_format($biaya, 0, "", ",");

		$post = $this->pettycashmerahdirektur_model->single_entry($id);
		$biaya_lama = $post->biaya_pettycash;
		$biaya_lama_show = "Rp " . number_format($biaya_lama, 0, "", ",");

		// INSERT LOG
		$nama_users = $this->session->userdata('name');
		$b = '<b>'.$nama_users.'</b> Rilis pettycash bon merah dengan status <b>'.$status.' dan catatan '.$catatan_manajer.'</b>';
		$data2 = array(
			'ket' => $b,
		);
		$this->stock_model->insert_log_stock($data2);
		// INSERT LOG
		$users = $this->session->userdata('user_atasan');

		date_default_timezone_set("Asia/Jayapura");
		$DATE_FORMAT = "Y-m-d H:i:s";
		$waktu = date($DATE_FORMAT);

		// $ajax_data['ket_pettycash'] = $this->input->post('ket',TRUE);
		// $ajax_data['biaya_pettycash'] = $this->input->post('biaya',TRUE);
		$ajax_data['status_bonmerah'] = $this->input->post('pembahasan',TRUE);
		$ajax_data['catatan_direktur_bonmerah'] = $this->input->post('catatandirektur',TRUE);
		$ajax_data['tgl_bonmerah_direktur'] = $waktu;
		$ajax_data['id_user_manager'] = $users;

		if ($this->pettycashmerahdirektur_model->update_entry($pettycashid, $ajax_data)) {

			echo json_encode(array("status" => TRUE));
		} else {
			echo json_encode(array("status" => FALSE));
		}
	}

	public function delete() {
    	if ($this->input->is_ajax_request()) {
    		$id = $this->input->post('id');
	    	$post = $this->pettycashmerahdirektur_model->single_entry($id);
			$biaya_lama = $post->biaya_pettycash;
			$ket = $post->ket_pettycash;
			$biaya_lama_show = "Rp " . number_format($biaya_lama, 0, "", ",");

			unlink(APPPATH . '../assets/images/fotobukti/' . $post->imgbukti);

				if ($this->pettycashmerahdirektur_model->delete_entry($id)) {
					// INSERT LOG

					$nama_users = $this->session->userdata('name');
					$b = '<b>'.$nama_users.'</b> Menghapus pettycashmerahdirektur Sebesar <b>'.$biaya_lama_show.'</b> Untuk Keperluan '.$ket;
					$data2 = array(
						'ket' => $b,
					);
					$this->stock_model->insert_log_stock($data2);
					// INSERT LOG
					$data = array('res' => "success", 'message' => "Data berhasil dihapus");
				} else {
					$data = array('res' => "error", 'message' => "Delete query error");
				}
				echo json_encode($data);
		} else {
			echo "No direct script access allowed";
		}
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		// $allowed_image_extension = array(
	  //       "png",
	  //       "jpg",
	  //       "jpeg",
	  //       "webp",
	  //   );
	  //   $file_extension_picture_1 = pathinfo($_FILES["picture_1"]["name"], PATHINFO_EXTENSION);
		$ket = $this->input->post('ket');
		if($this->input->post('ket') == '')
		{
			$data['inputerror'][] = 'ket';
			$data['error_string'][] = 'Form Keterangan harus berisi';
			$data['status'] = FALSE;
		}
		$ketlength= strlen($ket);
		if($ketlength < 3)
		{
			$data['inputerror'][] = 'ket';
			$data['error_string'][] = 'Keterangan Minimal 3 karakter';
			$data['status'] = FALSE;
		}
		if($this->input->post('biaya') == '')
		{
			$data['inputerror'][] = 'biaya';
			$data['error_string'][] = 'Form Biaya harus berisi';
			$data['status'] = FALSE;
		}
		// if (empty($_FILES['picture_1']['name'])) {
		// 	$data['inputerror'][] = 'picture_1';
		// 	$data['error_string'][] = 'Form Upload Bukti harus berisi';
		// 	$data['status'] = FALSE;
		// }
		// if (($_FILES["picture_1"]["size"] > 5000000)) {
		// 	$data['inputerror'][] = 'picture_1';
		// 	$data['error_string'][] = 'Image size maksimal 5MB';
		// 	$data['status'] = FALSE;
	  //   }
	  //   if (!in_array($file_extension_picture_1, $allowed_image_extension)) {
	  //       $data['inputerror'][] = 'picture_1';
		// 	$data['error_string'][] = 'Format File *jpg,png,jpeg,webp';
		// 	$data['status'] = FALSE;
	  //   }
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	private function _validate_edit()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		// $allowed_image_extension = array(
	  //       "png",
	  //       "jpg",
	  //       "jpeg",
	  //       "webp",
	  //       "",
	  //   );
	  //   $file_extension_picture_1 = pathinfo($_FILES["picture_1"]["name"], PATHINFO_EXTENSION);
    	$ket = $this->input->post('catatandirektur');
		if($this->input->post('catatandirektur') == '')
		{
			$data['inputerror'][] = 'catatandirektur';
			$data['error_string'][] = 'Form Catatan harus berisi';
			$data['status'] = FALSE;
		}
		$ketlength= strlen($ket);
		if($ketlength < 3)
		{
			$data['inputerror'][] = 'catatandirektur';
			$data['error_string'][] = 'Catatan Minimal 3 karakter';
			$data['status'] = FALSE;
		}
		if($this->input->post('pembahasan') == '')
		{
			$data['inputerror'][] = 'pembahasan';
			$data['error_string'][] = 'Form Status harus berisi';
			$data['status'] = FALSE;
		}

		// if (($_FILES["picture_1"]["size"] > 5000000)) {
		// 	$data['inputerror'][] = 'picture_1';
		// 	$data['error_string'][] = 'Image size maksimal 5MB';
		// 	$data['status'] = FALSE;
	  //   }
	  //   if (!in_array($file_extension_picture_1, $allowed_image_extension)) {
	  //       $data['inputerror'][] = 'picture_1';
		// 	$data['error_string'][] = 'Format File *jpg,png,jpeg,webp';
		// 	$data['status'] = FALSE;
	  //   }
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}



}
