<?php
class Atasan extends CI_Controller{
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
		$this->load->model('backend/atasan_model','atasan_model');
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
		$data['title'] = 'Data Atasan';

    $this->load->view('backend/menu',$data);
		$this->load->view('backend/modal/atasan_modal');
		$this->load->view('backend/_partials/templatejs');
		$this->load->view('backend/v_atasan', $data);
	}

  public function get_ajax_list()
	{
		$list = $this->atasan_model->get_datatables();
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
			$row[] = $d->namaatasan;
      $row[] = '<div class="btn-group mb-1"><div class="dropdown"><button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
      <a class="dropdown-item" href="javascript:void()" title="Edit" onclick="edit_atasan('."'".$d->id_atasan."'".')"><i class="bi bi-pen-fill"></i> Edit</a>
      <a class="dropdown-item" href="javascript:void()" title="Hapus" id="deleteatasan" value="'.$d->id_atasan.'"><i class="bi bi-trash"></i> Hapus</a></div></div></div>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->atasan_model->count_all(),
						"recordsFiltered" => $this->atasan_model->count_filtered(),
						"data" => $data,
				);
		//output to json format

		echo json_encode($output);
	}

  public function ajax_edit($id_atasan)
	{
		$data = $this->atasan_model->get_by_id($id_atasan);
		echo json_encode($data);
	}

  function add(){
		$this->_validate();

		$users = $this->session->userdata('id');
		$nama_users = $this->session->userdata('name');
		// GET NEW ID
		// $get_new_id = $this->atasan_model->get_new_id_cus();
    // foreach($get_new_id as $result){
    //     $kode_New_id =  $result->maxKode;
    // }
    // $noUrut = (int) substr($kode_New_id, 4, 12);
    // $noUrut++;
    // $char = "CST-";
    // $NewID = $char.str_pad($noUrut, 12, '0', STR_PAD_LEFT);
		// GET NEW ID

				$data = array(
					'namaatasan' => $this->input->post('namaatasan')
				);
				$insert = $this->atasan_model->insert_atasan($data);

				if($insert){
					// INSERT LOG

					$j = $this->input->post('namaatasan');
					$b = '<b>'.$nama_users.'</b> Melakukan Tambah atasan <b>'.$j.'</b>';
					$data2 = array(
						'ket' => $b,
					);
					$this->atasan_model->insert_log_atasan($data2);
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
					$ajax_data['namaatasan'] = $this->input->post('namaatasan');


					if ($this->atasan_model->update_entry($id, $ajax_data)) {
						// INSERT LOG
						$nama_users = $this->session->userdata('name');

						$j = $this->input->post('namaatasan');
						$b = '<b>'.$nama_users.'</b> Melakukan Edit atasan <b>'.$j.'</b>';
						$data2 = array(
							'ket' => $b,
						);
						$this->atasan_model->insert_log_atasan($data2);
						// INSERT LOG
						echo json_encode(array("status" => TRUE));
					} else {
						echo json_encode(array("status" => FALSE));
					}

	}

  public function deleteatasan()
	{
		if ($this->input->is_ajax_request()) {

			$id_atasan = $this->input->post('idkon');



				if ($this->atasan_model->delete_entry($id_atasan)) {

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
		$nama = $this->input->post('namaatasan');

		if($this->input->post('namaatasan') == '')
		{
			$data['inputerror'][] = 'namaatasan';
			$data['error_string'][] = 'Form atasan harus berisi';
			$data['status'] = FALSE;
		}

		$namalength= strlen($nama);
		if($namalength < 3)
		{
			$data['inputerror'][] = 'namaatasan';
			$data['error_string'][] = 'Nama atasan Miminal 3 Karakter';
			$data['status'] = FALSE;
		}
		if($namalength > 25)
		{
			$data['inputerror'][] = 'namaatasan';
			$data['error_string'][] = 'Nama atasan Maksimal 25 Karakter';
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

		$nama = $this->input->post('namaatasan');

		if($this->input->post('namaatasan') == '')
		{
			$data['inputerror'][] = 'namaatasan';
			$data['error_string'][] = 'Form Nama atasan harus berisi';
			$data['status'] = FALSE;
		}

		$namalength= strlen($nama);
		if($namalength < 3)
		{
			$data['inputerror'][] = 'namaatasan';
			$data['error_string'][] = 'Nama Miminal 3 Karakter';
			$data['status'] = FALSE;
		}
		if($namalength > 25)
		{
			$data['inputerror'][] = 'namaatasan';
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