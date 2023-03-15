<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf as Dompdf;
class Pettycashmerah extends CI_Controller{
/**
* Description of Controller
*
* @author isnanw
*/
	function __construct(){
		parent::__construct();
		error_reporting(0);
		if($this->session->userdata('access') != "3" && $this->session->userdata('access') != "1"){
			$url=base_url('login_user');
            redirect($url);
		};
		$this->load->model('backend/Pengeluaran_model','pengeluaran_model');
		$this->load->model('backend/Pettycashmerah_model','pettycashmerah_model');
		$this->load->model('backend/Stock_model','stock_model');
		$this->load->model('backend/bagian_model','bagian_model');
		$this->load->model('Site_model','site_model');
		$this->load->helper('text');
		$this->load->helper('url');
		$this->load->helper('tanggal');
		$this->load->library('upload');
		$this->load->helper('form');
	}

	public function index(){
		$site 							= $this->site_model->get_site_data()->row_array();
		$x['site_title'] 		= $site['site_title'];
		$x['site_favicon'] 	= $site['site_favicon'];
		$x['images'] 				= $site['images'];
		$x['title'] 				= 'Pettycash || Bon merah';
		$x['karyawan'] 			= $this->pettycashmerah_model->get_all_karyawan();
		$x['dataimage'] 		= $this->pettycashmerah_model->get_all_bukti();
		$x['bagian'] 				= $this->bagian_model->get_all_bagian();

		$this->load->view('backend/menu',$x);
		$this->load->view('backend/modal/pettycashmerah_modal');
		$this->load->view('backend/_partials/templatejs');
		$this->load->view('backend/v_pettycashmerah',$x);
	}

	public function get_ajax_list()
	{
		$id = $this->session->userdata('id');
		// die($id);
		if($this->session->userdata('access') == "1"){
				$list = $this->pettycashmerah_model->get_datatablesadmin();
		}else{
			$list = $this->pettycashmerah_model->get_datatables($id);
		};
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $d) {
			$no++;
			$status = $d->status_bonmerah;
			$gambar = $d->imgbukti;
			$tanpagambar = '<span class="badge bg-light-danger">Belum Upload</span>';
			$adagambar = "<div class='row gallery' data-bs-toggle='modal' data-bs-target='#galleryModal$d->id_pettycash'><a href='#'><img class='w-50 active' src='../assets/images/fotobukti/$d->imgbukti' data-bs-target='#Gallerycarousel'></a></div>";

			if($this->session->userdata('access') == "1"){
					// $button = '<a href="'.base_url('backend/belanja/').$d->id_pettycash.'" class="btn icon icon-left btn-info" title="Rincian Belanja"><i class="bi bi-currency-dollar"></i></a>';
					$button = '-';
				}else{
					$button = '<div class="button">
												<a class="btn icon icon-left btn-warning item_edit" href="javascript:void()" title="Upload Dokumen" onclick="edit_pettycash('."'".$d->id_pettycash."'".')"><i class="bi bi-upload"></i></a>
											</div>';
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
				$actclass = 'primary';
				// $tgl			= '<small><b>'.$d->status.'</b>: '.format_indo(date($d->tgl_pettycash)).'</small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_bonmerah)).'</small>';
			}elseif ($status == 'DISETUJUI'){
				$class = 'lock';
				$ket = 'Disetujui';
				$icon = 'check';
				$actket = 'Disetujui';
				$actclass = 'success';
				$button = '-';
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
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_bonmerah)).'<br> <b>Disetujui</b>: '.format_indo(date($d->tgl_bonmerah_manajer)).'<br><b>Dirilis</b>: '.format_indo(date($d->tgl_bonmerah_direktur)).'</small>';
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

			// $row[] = "<div class='row gallery' data-bs-toggle='modal' data-bs-target='#galleryModal$d->id_pettycashmerah'><a href='#'><img class='w-100 active' src='../assets/images/fotobukti/$d->imgbukti' data-bs-target='#Gallerycarousel'></a></div>";
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
				"recordsTotal" => $this->pettycashmerah_model->count_all(),
				"recordsFiltered" => $this->pettycashmerah_model->count_filtered(),
				"data" => $data,
			);
		//output to json format
		echo json_encode($output);
	}
	public function ajax_edit($id_pettycash)
	{
		$data = $this->pettycashmerah_model->get_by_id($id_pettycash);
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
				"ket_pettycash" 	=> $this->input->post('ket',TRUE),
				"biaya_pettycash" => $this->input->post('biaya',TRUE),
				"status" 					=> 'PENGAJUAN',
				"tgl_pettycash_manajer"	 => NULL,
				"tgl_pettycash_direktur" => NULL,
				"id_user_pettycash" => $users,
				"id_user_manager" 	=> $user_atasan,
				"id_bagian"					=> $this->input->post('bagian',TRUE)
		);
		$insert = $this->pettycashmerah_model->insert_pettycash($arraysql);

		if($insert){
			// INSERT LOG
			$nama_users = $this->session->userdata('name');
			$b = '<b>'.$nama_users.'</b> Menambah pettycash Sebesar <b>'.$biayashow.'</b> Untuk Keperluan '.$ket;
			$data2 = array(
				'ket' => $b.'',
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
		if (isset($_FILES["picture_1"]["name"])) {
			$config['upload_path'] = APPPATH . '../assets/images/fotobukti/';
			$config['allowed_types'] = 'jpg|png|jpeg|webp';
			$config['encrypt_name'] = TRUE;
			$this->upload->initialize($config);
			if (!$this->upload->do_upload("picture_1")) {
					$data = array();
			$data['error_string'] = array();
			$data['inputerror'] = array();
			$data['inputerror'][] = 'picture_1';
			$data['error_string'][] = 'Format File *jpg,png,jpeg,webp';
			$data['status'] = FALSE;
			if($data['status'] === FALSE)
			{
				echo json_encode($data);
				exit();
			}
		} else {
				$gbr = $this->upload->data();
				//Compress Image
				$config['image_library']='gd2';
				$config['source_image'] = APPPATH . '../assets/images/fotobukti/'.$gbr['file_name'];
				$config['create_thumb']= FALSE;
				$config['maintain_ratio']= FALSE;
				$config['quality']= '100%';
				$config['new_image'] = APPPATH . '../assets/images/fotobukti/'.$gbr['file_name'];
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();

				$gambar=$gbr['file_name'];
				$id = $this->input->post('id',TRUE);
				$post2 = $this->pettycashmerah_model->single_entry($id);
				$check = $post2->imgbukti;
				if ($check == 'user_blank.webp') {
					// Tidak hapus user_blank.webp
				} else {
					unlink(APPPATH . '../assets/images/fotobukti/' . $post2->imgbukti);
				}
				$ajax_data['imgbukti'] = $gambar;
			}
		}

		$id = $this->input->post('id',TRUE);
		$biaya = $this->input->post('biaya',TRUE);
		$ket = $this->input->post('ket',TRUE);
		$biayashow = "Rp " . number_format($biaya, 0, "", ",");

		$post = $this->pettycashmerah_model->single_entry($id);
		$biaya_lama = $post->biaya_pettycashmerah;
		$biaya_lama_show = "Rp " . number_format($biaya_lama, 0, "", ",");

		// INSERT LOG
		$nama_users = $this->session->userdata('name');
		$b = '<b>'.$nama_users.'</b> Mengubah pettycashmerah Sebesar <b>'.$biaya_lama_show.' Menjadi '.$biayashow.'</b> Untuk Keperluan '.$ket;
		$data2 = array(
			'ket' => $b.'',
		);
		$this->stock_model->insert_log_stock($data2);
		// INSERT LOG
		$users = $this->session->userdata('id');

		date_default_timezone_set("Asia/Jayapura");
		$DATE_FORMAT = "Y-m-d H:i:s";
		$waktu = date($DATE_FORMAT);

		$ajax_data['ket_pettycash'] = $this->input->post('ket',TRUE);
		$ajax_data['biaya_pettycash'] = $this->input->post('biaya',TRUE);
		$ajax_data['id_bagian'] = $this->input->post('bagian',TRUE);
		$ajax_data['id_user_pettycash'] = $users;
		$ajax_data['status_bonmerah'] = 'PENGAJUAN';
		$ajax_data['tgl_bonmerah'] = $waktu;

		if ($this->pettycashmerah_model->update_entry($pettycashid, $ajax_data)) {

			echo json_encode(array("status" => TRUE));
		} else {
			echo json_encode(array("status" => FALSE));
		}
	}

	public function delete() {
    	if ($this->input->is_ajax_request()) {
    		$id = $this->input->post('id');
	    	$post = $this->pettycashmerah_model->single_entry($id);
			$biaya_lama = $post->biaya_pettycashmerah;
			$ket = $post->ket_pettycashmerah;
			$biaya_lama_show = "Rp " . number_format($biaya_lama, 0, "", ",");

			unlink(APPPATH . '../assets/images/fotobukti/' . $post->imgbukti);

				if ($this->pettycashmerah_model->delete_entry($id)) {
					// INSERT LOG

					$nama_users = $this->session->userdata('name');
					$b = '<b>'.$nama_users.'</b> Menghapus pettycashmerah Sebesar <b>'.$biaya_lama_show.'</b> Untuk Keperluan '.$ket;
					$data2 = array(
						'ket' => $b.'',
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
		$allowed_image_extension = array(
	        "png",
	        "jpg",
	        "jpeg",
	        "webp",
	        "",
	    );
	    $file_extension_picture_1 = pathinfo($_FILES["picture_1"]["name"], PATHINFO_EXTENSION);
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

		if (($_FILES["picture_1"]["size"] > 5000000)) {
			$data['inputerror'][] = 'picture_1';
			$data['error_string'][] = 'Image size maksimal 5MB';
			$data['status'] = FALSE;
	    }
	    if (!in_array($file_extension_picture_1, $allowed_image_extension)) {
			$data['inputerror'][] = 'picture_1';
			$data['error_string'][] = 'Format File *jpg,png,jpeg,webp';
			$data['status'] = FALSE;
	    }
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function excel()
		{
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'No');
			$sheet->setCellValue('B1', 'Tanggal');
			$sheet->setCellValue('C1', 'Biaya');
			$sheet->setCellValue('D1', 'Keterangan / Bagian');
			$sheet->setCellValue('E1', 'Karyawan / Atasan');
			$sheet->setCellValue('F1', 'Status');
			$id = $this->uri->segment(4);
			$petty = $this->pettycashmerah_model->get_laporan_excel($id);
			$no = 1;
			$x = 2;
			foreach($petty as $row)
			{
				$sheet->setCellValue('A'.$x, $no++);
				$sheet->setCellValue('B'.$x, $row->tgl_pettycash);
				$sheet->setCellValue('C'.$x, $row->biaya_pettycash);
				$sheet->setCellValue('D'.$x, $row->ket_pettycash.' ('.$row->namabagian.')');
				$sheet->setCellValue('E'.$x, $row->user_name.' ('.$row->namaatasan.') ');
				$sheet->setCellValue('F'.$x, $row->status);
				$x++;
			}
			$writer = new Xlsx($spreadsheet);
			$filename = 'laporan-pettymerah';

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
			header('Cache-Control: max-age=0');

			$writer->save('php://output');
		}

		public function pdf()
		{
			$data['title'] = 'Laporan Pdf';
			$id = $this->uri->segment(4);
			$data['bon'] = $this->pettycashmerah_model->get_laporan($id);
			$data['hariini'] = date('d F Y');

			$dompdf = new Dompdf();
			$dompdf->setPaper('A4', 'Landscape');
			$html = $this->load->view('backend/laporan/cetakbonmerah', $data, true);
			$dompdf->load_html($html);
			$dompdf->render();
			$dompdf->stream('Laporan Data Bon Merah ', array("Attachment" => false));
		}


}
