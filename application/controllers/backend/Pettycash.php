<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf as Dompdf;
class Pettycash extends CI_Controller{
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
		$this->load->model('backend/Pettycash_model','pettycash_model');
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
		$x['title'] 				= 'Pettycash';
		$x['karyawan'] 			= $this->pettycash_model->get_all_karyawan();
		$x['dataimage'] 		= $this->pettycash_model->get_all_bukti();
		$x['bagian'] 				= $this->bagian_model->get_all_bagian();

		$this->load->view('backend/menu',$x);
		$this->load->view('backend/modal/pettycash_modal');
		$this->load->view('backend/_partials/templatejs');
		$this->load->view('backend/v_pettycash',$x);
	}

	public function get_ajax_list()
	{
		$id = $this->session->userdata('id');
		// die($id);
		if($this->session->userdata('access') == "1"){
				$list = $this->pettycash_model->get_datatablesadmin();
		}else{
			$list = $this->pettycash_model->get_datatables($id);
		};
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $d) {
			$no++;
			$status = $d->status;
			if ($status == 'PENGAJUAN') {
				$class = 'lock';
				$ket = 'Pengajuan';
				$icon = 'arrow-up';
				$actket = 'Pengajuan';
				$actclass = 'primary';
				if($this->session->userdata('access') == "1"){
					$button = '<small><span class="bg-light-info">Tidak ada aksi</span></small>';
				}else{
					$button = '<div class="btn-group mb-1"><div class="dropdown"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton7"><a class="dropdown-item item_edit" href="javascript:void()" title="Edit" onclick="edit_pettycash('."'".$d->id_pettycash."'".')"><i class="bi bi-pen-fill"></i> Edit</a>
					<a class="dropdown-item delete_record" href="javascript:void()" title="Hapus" id="del" value="'.$d->id_pettycash.'"><i class="bi bi-trash"></i> Hapus</a>
					</div></div></div>';
				};
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_pettycash)).'</small>';

			}elseif ($status == 'DISETUJUI'){
				$class = 'lock';
				$ket = 'Disetujui';
				$icon = 'check';
				$actket = 'Disetujui';
				$actclass = 'success';
				$button = '<small><span class="bg-light-success"><b>Disetujui</b>, Pada: <br>'.format_indo(date($d->tgl_pettycash_manajer)).'<br>'.$d->catatan_manajer.'</span></small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_pettycash)).'<br> <b>Dibahas</b>: '.format_indo(date($d->tgl_pettycash_manajer)).'</small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_pettycash)).'<br> <b>Dibahas</b>: '.format_indo(date($d->tgl_pettycash_manajer)).'</small>';
			}elseif ($status == 'DITOLAK'){
				$class = 'unlock';
				$ket = 'Ditolak';
				$icon = 'exclamation';
				$actket = 'Ditolak';
				$actclass = 'danger';
				$button = '<small><span class="bg-light-danger"><b>Ditolak</b>, Pada: <br>'.format_indo(date($d->tgl_pettycash_manajer)).'<br>'.$d->catatan_manajer.'</span></small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_pettycash)).'<br> <b>Dibahas</b>: '.format_indo(date($d->tgl_pettycash_manajer)).'</small>';
			}elseif ($status == 'NONRILIS'){
				$class = 'unlock';
				$ket = 'Tidak Dirilis';
				$icon = 'exclamation';
				$actket = 'Tidak Dirilis';
				$actclass = 'warning';
				$button = '<small><span class="bg-light-danger"><b>Tidak Rilis</b>, Pada: <br>'.format_indo(date($d->tgl_pettycash_direktur)).'<br>'.$d->catatan_direktur.'</span></small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_pettycash)).'<br> <b>Dibahas</b>: '.format_indo(date($d->tgl_pettycash_manajer)).'<br> <b>Tidak Dirilis</b>: '.format_indo(date($d->tgl_pettycash_direktur)).'</small>';
			}else{
				$class = 'unlock';
				$ket = 'Rilis';
				$icon = 'check';
				$actket = 'Rilis';
				$actclass = 'primary';
				$button = '<small><span class="bg-light-primary"><b>Dirilis</b>, Pada: <br>'.format_indo(date($d->tgl_pettycash_direktur)).'<br>'.$d->catatan_direktur.'</span></small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo(date($d->tgl_pettycash)).'<br> <b>Dibahas</b>: '.format_indo(date($d->tgl_pettycash_manajer)).'<br> <b>Dirilis</b>: '.format_indo(date($d->tgl_pettycash_direktur)).'</small>';
			}
			$row = array();
			$row[] = $no;

			// $row[] = "<div class='row gallery' data-bs-toggle='modal' data-bs-target='#galleryModal$d->id_pettycash'><a href='#'><img class='w-100 active' src='../assets/images/fotobukti/$d->imgbukti' data-bs-target='#Gallerycarousel'></a></div>";
			$row[] = $tgl;
			$row[] = "Rp " . number_format($d->biaya_pettycash, 0, "", ",");
			$row[] = $d->ket_pettycash.''.'<br><small><span class="badge bg-light-warning">'.$d->namabagian.'</span></small>';
			$row[] = $d->user_name. '<br><span class="badge bg-light-warning">'.$d->namaatasan.'</span>' ;
			$row[] = '<div class="alert alert-light-'.$actclass.' color-'.$actclass.'"><i class="bi bi-'.$icon.'-circle"></i> '.$actket.'</div>';
			$row[] = $button;
			$data[] = $row;
		}

		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->pettycash_model->count_all(),
				"recordsFiltered" => $this->pettycash_model->count_filtered(),
				"data" => $data,
			);
		//output to json format
		echo json_encode($output);
	}
	public function ajax_edit($id_pettycash)
	{
		$data = $this->pettycash_model->get_by_id($id_pettycash);
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
				"tgl_bonmerah"	 => NULL,
				"tgl_bonmerah_manajer"	 => NULL,
				"tgl_bonmerah_direktur" => NULL,
				"id_user_pettycash" => $users,
				"id_user_manager" 	=> $user_atasan,
				"id_bagian"					=> $this->input->post('bagian',TRUE)
		);
		$insert = $this->pettycash_model->insert_pettycash($arraysql);

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

		$id = $this->input->post('id',TRUE);
		$biaya = $this->input->post('biaya',TRUE);
		$ket = $this->input->post('ket',TRUE);
		$biayashow = "Rp " . number_format($biaya, 0, "", ",");

		$post = $this->pettycash_model->single_entry($id);
		$biaya_lama = $post->biaya_pettycash;
		$biaya_lama_show = "Rp " . number_format($biaya_lama, 0, "", ",");

		// INSERT LOG
		$nama_users = $this->session->userdata('name');
		$b = '<b>'.$nama_users.'</b> Mengubah pettycash Sebesar <b>'.$biaya_lama_show.' Menjadi '.$biayashow.'</b> Untuk Keperluan '.$ket;
		$data2 = array(
			'ket' => $b.'',
		);
		$this->stock_model->insert_log_stock($data2);
		// INSERT LOG
		$users = $this->session->userdata('id');

		$ajax_data['ket_pettycash'] = $this->input->post('ket',TRUE);
		$ajax_data['biaya_pettycash'] = $this->input->post('biaya',TRUE);
		$ajax_data['id_bagian'] = $this->input->post('bagian',TRUE);
		$ajax_data['id_user_pettycash'] = $users;

		if ($this->pettycash_model->update_entry($pettycashid, $ajax_data)) {

			echo json_encode(array("status" => TRUE));
		} else {
			echo json_encode(array("status" => FALSE));
		}
	}

	public function delete() {
    	if ($this->input->is_ajax_request()) {
    		$id = $this->input->post('id');
	    	$post = $this->pettycash_model->single_entry($id);
			$biaya_lama = $post->biaya_pettycash;
			$ket = $post->ket_pettycash;
			$biaya_lama_show = "Rp " . number_format($biaya_lama, 0, "", ",");

			unlink(APPPATH . '../assets/images/fotobukti/' . $post->imgbukti);

				if ($this->pettycash_model->delete_entry($id)) {
					// INSERT LOG

					$nama_users = $this->session->userdata('name');
					$b = '<b>'.$nama_users.'</b> Menghapus pettycash Sebesar <b>'.$biaya_lama_show.'</b> Untuk Keperluan '.$ket;
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
		// $allowed_image_extension = array(
	  //       "png",
	  //       "jpg",
	  //       "jpeg",
	  //       "webp",
	  //       "",
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
			$petty = $this->pettycash_model->get_laporan_excel($id);
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
			$filename = 'laporan-petty';

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
			header('Cache-Control: max-age=0');

			$writer->save('php://output');
		}

		public function pdf()
		{
			$data['title'] = 'Laporan Pdf';
			$id = $this->uri->segment(4);
			$data['bon'] = $this->pettycash_model->get_laporan($id);
			$data['hariini'] = date('d F Y');

			$dompdf = new Dompdf();
			$dompdf->setPaper('A4', 'Landscape');
			$html = $this->load->view('backend/laporan/cetakbonhijau', $data, true);
			$dompdf->load_html($html);
			$dompdf->render();
			$dompdf->stream('Laporan Data Bon Hijau ', array("Attachment" => false));
		}


}
