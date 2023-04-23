<?php
class Reimburse extends CI_Controller{
/**
* Description of Controller
*
* @author isnanw
*/
	function __construct(){
		parent::__construct();
		error_reporting(0);
		if($this->session->userdata('access') != "3" && $this->session->userdata('access') != "1" && $this->session->userdata('access') != "4" && $this->session->userdata('access') != "5"){
			$url=base_url('login_user');
            redirect($url);
		};
		$this->load->model('backend/Reimburse_model','reimburse_model');
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
		$x['title'] = 'Reimburse';
		$x['karyawan'] = $this->reimburse_model->get_all_karyawan();
		$x['dataimage'] = $this->reimburse_model->get_all_bukti();
		$this->load->view('backend/menu',$x);
		$this->load->view('backend/modal/reimburse_modal');
		$this->load->view('backend/_partials/templatejs');
		$this->load->view('backend/v_reimburse',$x);
	}
	
	public function get_ajax_list()
	{
		if($this->session->userdata('access') == "4"){
			$id = $this->session->userdata('user_atasan');
			$list = $this->reimburse_model->get_datatablesmanajer($id);
		}elseif($this->session->userdata('access') == "5"){
			$list = $this->reimburse_model->get_datatablesdirektur();
		}else{
			$list = $this->reimburse_model->get_datatables();
		}
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $d) {
			$status = $d->status;
			$aksi = '';
			if($this->session->userdata('access') == "5"){
				if($status == 'DISETUJUI'){
					$aksi = '<div class="button">
											<a class="btn icon icon-left btn-primary item_edit" href="javascript:void()" title="Rilis" onclick="edit_pettycashdirektur('."'".$d->id_reimburse."'".')"><i class="bi bi-pen-fill"></i> Rilis</a>
										</div>';
				}else{
					$aksi = '-';
				}
			}elseif($this->session->userdata('access') == "4"){
				$aksi = '<div class="button">
											<a class="btn icon icon-left btn-primary item_edit" href="javascript:void()" title="Bahas" onclick="edit_pettycashmanajer('."'".$d->id_reimburse."'".')"><i class="bi bi-pen-fill"></i> Bahas</a>
										</div>';
			}else{
				if($status == 'PENGAJUAN' || $status == '' || $status == NULL){
					$aksi = '<div class="btn-group mb-1"><div class="dropdown"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton7"><a class="dropdown-item item_edit" href="javascript:void()" title="Edit" onclick="edit_reimburse('."'".$d->id_reimburse."'".')"><i class="bi bi-pen-fill"></i> Edit</a>
					<a class="dropdown-item delete_record" href="javascript:void()" title="Hapus" id="del" value="'.$d->id_reimburse.'"><i class="bi bi-trash"></i> Hapus</a>
					</div></div></div>';
				}else{
					$aksi = '-';
				}
			}

			if ($status == 'PENGAJUAN' || $status == '') {
				$class = 'lock';
				$ket = 'Pengajuan';
				$icon = 'arrow-up';
				$actket = 'Pengajuan';
				$actclass = 'primary';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo_nohari(date($d->tgl_reimburse)).'</small>';

			}elseif ($status == 'DISETUJUI'){
				$class = 'lock';
				$ket = 'Disetujui';
				$icon = 'check';
				$actket = 'Disetujui';
				$actclass = 'success';
				$button = '<small><span class="bg-light-success"><b>Disetujui</b>, Pada: <br>'.format_indo_nohari(date($d->tgl_reimburse_manajer)).'<br>'.$d->catatan_manajer.'</span></small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo_nohari(date($d->tgl_reimburse)).'<br> <b>Dibahas</b>: '.format_indo_nohari(date($d->tgl_reimburse_manajer)).'</small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo_nohari(date($d->tgl_reimburse)).'<br> <b>Dibahas</b>: '.format_indo_nohari(date($d->tgl_reimburse_manajer)).'</small>';
			}elseif ($status == 'DITOLAK'){
				$class = 'unlock';
				$ket = 'Ditolak';
				$icon = 'exclamation';
				$actket = 'Ditolak';
				$actclass = 'danger';
				$button = '<small><span class="bg-light-danger"><b>Ditolak</b>, Pada: <br>'.format_indo_nohari(date($d->tgl_reimburse_manajer)).'<br>'.$d->catatan_manajer.'</span></small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo_nohari(date($d->tgl_reimburse)).'<br> <b>Dibahas</b>: '.format_indo_nohari(date($d->tgl_reimburse_manajer)).'</small>';
			}elseif ($status == 'NONRILIS'){
				$class = 'unlock';
				$ket = 'Tidak Dirilis';
				$icon = 'exclamation';
				$actket = 'Tidak Dirilis';
				$actclass = 'warning';
				$button = '<small><span class="bg-light-danger"><b>Tidak Rilis</b>, Pada: <br>'.format_indo_nohari(date($d->tgl_reimburse_direktur)).'<br>'.$d->catatan_direktur.'</span></small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo_nohari(date($d->tgl_reimburse)).'<br> <b>Dibahas</b>: '.format_indo_nohari(date($d->tgl_reimburse_manajer)).'<br> <b>Tidak Dirilis</b>: '.format_indo_nohari(date($d->tgl_reimburse_direktur)).'</small>';
			}else{
				$class = 'unlock';
				$ket = 'Rilis';
				$icon = 'check';
				$actket = 'Rilis';
				$actclass = 'primary';
				$button = '<small><span class="bg-light-primary"><b>Dirilis</b>, Pada: <br>'.format_indo_nohari(date($d->tgl_reimburse_direktur)).'<br>'.$d->catatan_direktur.'</span></small>';
				$tgl			= '<small><b>Diajukan</b>: '.format_indo_nohari(date($d->tgl_reimburse)).'<br> <b>Dibahas</b>: '.format_indo_nohari(date($d->tgl_reimburse_manajer)).'<br> <b>Dirilis</b>: '.format_indo_nohari(date($d->tgl_reimburse_direktur)).'</small>';
			}

			$no++;
			$row = array();
			$row[] = $no;

			$row[] = "<div class='row gallery' data-bs-toggle='modal' data-bs-target='#galleryModal$d->id_reimburse'><a href='#'><img class='w-100 active' src='../assets/images/fotobukti/$d->imgbukti' data-bs-target='#Gallerycarousel'></a></div>";
			$row[] = $d->ket_reimburse;
			$row[] = "Rp " . number_format($d->biaya_reimburse, 0, "", ",").'<br>'.'<div class="badge bg-light-'.$actclass.' color-'.$actclass.'"><i class="bi bi-'.$icon.'-circle"></i> '.$actket.'</div>';
			$row[] = $tgl;
			$row[] = $d->user_name;
			$row[] = $aksi;
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->reimburse_model->count_all(),
						"recordsFiltered" => $this->reimburse_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function ajax_edit($id_reimburse)
	{
		$data = $this->reimburse_model->get_by_id($id_reimburse);
		echo json_encode($data);
	}

	public function ajax_editmanajer($id_reimburse)
	{
		$data = $this->reimburse_model->get_by_id($id_reimburse);
		echo json_encode($data);
	}

	function add(){

		$this->_validate();
    	$config['upload_path'] = './assets/images/fotobukti/'; //path folder
	    $config['allowed_types'] = 'jpg|png|jpeg|webp';
	    $config['encrypt_name'] = TRUE;
	    $this->upload->initialize($config);
	    	if(!empty($_FILES['picture_1']['name'])){

	    		if ($this->upload->do_upload('picture_1')){
		            $filefotobukti = $this->upload->data();
		            $bg_filefotobukti=$filefotobukti['file_name'];
		            $biaya = $this->input->post('biaya',TRUE);
					$ket = $this->input->post('ket',TRUE);
					$biayashow = "Rp " . number_format($biaya, 0, "", ",");
					$users = $this->session->userdata('id');
					$arraysql = array(
		    			"ket_reimburse" => $this->input->post('ket',TRUE),
		    			"biaya_reimburse" => $this->input->post('biaya',TRUE),
		    			"imgbukti" => $bg_filefotobukti,
							"tgl_reimburse_manajer" => NULL,
							"tgl_reimburse_direktur" => NULL,
		    			"id_user_reimburse" => $users
					);
					$insert = $this->reimburse_model->insert_reimburse($arraysql);

					if($insert){
						// INSERT LOG

						$nama_users = $this->session->userdata('name');
						$b = '<b>'.$nama_users.'</b> Menambah reimburse Sebesar <b>'.$biayashow.'</b> Untuk Keperluan '.$ket;
						$data2 = array(
							'ket' => $b,
						);
						$this->stock_model->insert_log_stock($data2);
						// INSERT LOG
						echo json_encode(array("status" => TRUE));
					}else{
						echo json_encode(array("status" => FALSE));
					}
		        } else {
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

		        }

			} else {
				echo json_encode(array("status" => FALSE));
			}

    }


    function edit() {
    	$reimburseid=$this->input->post('id',TRUE);
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
							$post2 = $this->reimburse_model->single_entry($id);
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

				$post = $this->reimburse_model->single_entry($id);
				$biaya_lama = $post->biaya_reimburse;
				$biaya_lama_show = "Rp " . number_format($biaya_lama, 0, "", ",");

				// INSERT LOG
				$nama_users = $this->session->userdata('name');
				$b = '<b>'.$nama_users.'</b> Mengubah reimburse Sebesar <b>'.$biaya_lama_show.' Menjadi '.$biayashow.'</b> Untuk Keperluan '.$ket;
				$data2 = array(
					'ket' => $b,
				);
				$this->stock_model->insert_log_stock($data2);
				// INSERT LOG

				$users = $this->session->userdata('id');

				date_default_timezone_set("Asia/Jayapura");
				$DATE_FORMAT = "Y-m-d H:i:s";
				$waktu = date($DATE_FORMAT);


				if(!empty($this->input->post('pembahasan'))){
					$ajax_data['status'] = $this->input->post('pembahasan',TRUE);
					$ajax_data['catatan_manajer'] = $this->input->post('catatanmanajer',TRUE);
					$ajax_data['tgl_reimburse_manajer'] = $waktu;
					$ajax_data['id_user_manager'] = $users;
				}elseif(!empty($this->input->post('rilis'))){
					$ajax_data['status'] = $this->input->post('rilis',TRUE);
					$ajax_data['catatan_direktur'] = $this->input->post('catatandirektur',TRUE);
					$ajax_data['tgl_reimburse_direktur'] = $waktu;
					// $ajax_data['id_user_direktur'] = $users;
				}else{
					$ajax_data['ket_reimburse'] = $this->input->post('ket',TRUE);
					$ajax_data['biaya_reimburse'] = $this->input->post('biaya',TRUE);
					$ajax_data['id_user_reimburse'] = $users;
				}


				if ($this->reimburse_model->update_entry($reimburseid, $ajax_data)) {
					echo json_encode(array("status" => TRUE));
				} else {
					echo json_encode(array("status" => FALSE));
				}

    }
    public function delete() {
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			$post = $this->reimburse_model->single_entry($id);
			$biaya_lama = $post->biaya_reimburse;
			$ket = $post->ket_reimburse;
			$biaya_lama_show = "Rp " . number_format($biaya_lama, 0, "", ",");

			unlink(APPPATH . '../assets/images/fotobukti/' . $post->imgbukti);

				if ($this->reimburse_model->delete_entry($id)) {
					// INSERT LOG

					$nama_users = $this->session->userdata('name');
					$b = '<b>'.$nama_users.'</b> Menghapus reimburse Sebesar <b>'.$biaya_lama_show.'</b> Untuk Keperluan '.$ket;
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
		$allowed_image_extension = array(
				"png",
				"jpg",
				"jpeg",
				"webp",
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
		if (empty($_FILES['picture_1']['name'])) {
			$data['inputerror'][] = 'picture_1';
			$data['error_string'][] = 'Form Upload Bukti harus berisi';
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



}
