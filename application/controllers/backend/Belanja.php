<?php
class Belanja extends CI_Controller{
  /**
* Description of Controller
*
* @author isnanw
*/
	function __construct(){
		parent::__construct();
		//ini_set('memory_limit', "256M");
		error_reporting(0);
		if($this->session->userdata('logged') !=TRUE){
      $url=base_url('login_user');
      redirect($url);
    };
		$this->load->model('backend/Belanja_model','belanja_model');
		$this->load->model('Site_model','site_model');
		$this->load->helper('text');
		$this->load->helper('url');
		$this->load->helper('tanggal');
	}

	public function index(){
		$site = $this->site_model->get_site_data()->row_array();
    $data['site_title'] = $site['site_title'];
    $data['site_favicon'] = $site['site_favicon'];
    $data['images'] = $site['images'];
		$data['title'] = 'Data belanja';

    $this->load->view('backend/menu',$data);
		$this->load->view('backend/modal/belanja_modal');
		$this->load->view('backend/_partials/templatejs');
		$this->load->view('backend/v_belanja', $data);
	}

  public function get_ajax_list()
	{
		$bon = $_GET['bon'];
		$list = $this->belanja_model->get_datatables($bon);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $d) {
			$status = $d->user_status;

			if ($status == 1) {
				$class = 'lock';
				$ket = 'Nonaktifkan Konsumen';
				$icon = 'check';
				$actket = 'Aktif';
				$actclass = 'success';
			} else {
				$class = 'unlock';
				$ket = 'Aktifkan Konsumen';
				$icon = 'exclamation';
				$actket = 'Nonaktif';
				$actclass = 'danger';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $d->namabelanja;
			$row[] = $d->biaya;
      $row[] = '<div class="btn-group mb-1"><div class="dropdown"><button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
      <a class="dropdown-item" href="javascript:void()" title="Edit" onclick="edit_belanja('."'".$d->id_belanja."'".')"><i class="bi bi-pen-fill"></i> Edit</a>
      <a class="dropdown-item" href="javascript:void()" title="Hapus" id="deletebelanja" value="'.$d->id_belanja.'"><i class="bi bi-trash"></i> Hapus</a></div></div></div>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->belanja_model->count_all(),
						"recordsFiltered" => $this->belanja_model->count_filtered(),
						"data" => $data,
				);
		//output to json format

		echo json_encode($output);
	}

  public function ajax_edit($id_belanja)
	{
		$data = $this->belanja_model->get_by_id($id_belanja);
		echo json_encode($data);
	}

  function add(){
		$this->_validate();

		$users = $this->session->userdata('id');
		$nama_users = $this->session->userdata('name');
		// GET NEW ID
		// $get_new_id = $this->belanja_model->get_new_id_cus();
    // foreach($get_new_id as $result){
    //     $kode_New_id =  $result->maxKode;
    // }
    // $noUrut = (int) substr($kode_New_id, 4, 12);
    // $noUrut++;
    // $char = "CST-";
    // $NewID = $char.str_pad($noUrut, 12, '0', STR_PAD_LEFT);
		// GET NEW ID

				$data = array(
					'namabelanja' 	=> $this->input->post('namabelanja'),
					'biaya' 				=> $this->input->post('biaya'),
					'id_pettycash' => $this->input->post('bon'),
					'id_user'				=> $users
				);
				$insert = $this->belanja_model->insert_belanja($data);

				if($insert){
					// INSERT LOG

					$j = $this->input->post('namabelanja');
					$b = '<b>'.$nama_users.'</b> Melakukan Tambah belanja <b>'.$j.'</b>';
					$data2 = array(
						'ket' => $b,
					);
					$this->belanja_model->insert_log_belanja($data2);
					// INSERT LOG
					echo json_encode(array("status" => TRUE));
				}else{
					echo json_encode(array("status" => FALSE));
				}
	}

  function edit(){
		$id=$this->input->post('id',TRUE);
		$this->_validate_edit();


					$users = $this->session->userdata('id');
					$ajax_data['namabelanja'] = $this->input->post('namabelanja');


					if ($this->belanja_model->update_entry($id, $ajax_data)) {
						// INSERT LOG
						$nama_users = $this->session->userdata('name');

						$j = $this->input->post('namabelanja');
						$b = '<b>'.$nama_users.'</b> Melakukan Edit belanja <b>'.$j.'</b>';
						$data2 = array(
							'ket' => $b,
						);
						$this->belanja_model->insert_log_belanja($data2);
						// INSERT LOG
						echo json_encode(array("status" => TRUE));
					} else {
						echo json_encode(array("status" => FALSE));
					}

	}

  public function deletebelanja()
	{
		if ($this->input->is_ajax_request()) {

			$id_belanja = $this->input->post('idkon');



				if ($this->belanja_model->delete_entry($id_belanja)) {

					$data = array('res' => "success", 'message' => "Proses berhasil dilakukan");
				} else {
					$data = array('res' => "error", 'message' => "Proses gagal dilakukan");
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
		$nama = $this->input->post('namabelanja');

		if($this->input->post('namabelanja') == '')
		{
			$data['inputerror'][] = 'namabelanja';
			$data['error_string'][] = 'Form belanja harus berisi';
			$data['status'] = FALSE;
		}

		$namalength= strlen($nama);
		if($namalength < 3)
		{
			$data['inputerror'][] = 'namabelanja';
			$data['error_string'][] = 'Nama belanja Miminal 3 Karakter';
			$data['status'] = FALSE;
		}
		if($namalength > 25)
		{
			$data['inputerror'][] = 'namabelanja';
			$data['error_string'][] = 'Nama belanja Maksimal 25 Karakter';
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

		$nama = $this->input->post('namabelanja');

		if($this->input->post('namabelanja') == '')
		{
			$data['inputerror'][] = 'namabelanja';
			$data['error_string'][] = 'Form Nama belanja harus berisi';
			$data['status'] = FALSE;
		}

		$namalength= strlen($nama);
		if($namalength < 3)
		{
			$data['inputerror'][] = 'namabelanja';
			$data['error_string'][] = 'Nama Miminal 3 Karakter';
			$data['status'] = FALSE;
		}
		if($namalength > 25)
		{
			$data['inputerror'][] = 'namabelanja';
			$data['error_string'][] = 'Nama Maksimal 25 Karakter';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}