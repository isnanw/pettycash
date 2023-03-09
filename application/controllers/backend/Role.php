<?php
class Role extends CI_Controller{
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
		$this->load->model('backend/role_model','role_model');
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
		$data['title'] = 'Role User';

    $this->load->view('backend/menu',$data);
		$this->load->view('backend/modal/role_modal');
		$this->load->view('backend/_partials/templatejs');
		$this->load->view('backend/v_role', $data);
	}

  public function get_ajax_list()
	{
		$list = $this->role_model->get_datatables();
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
			$row[] = $d->namarole;
      $row[] = '<div class="btn-group mb-1"><div class="dropdown"><button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
      <a class="dropdown-item" href="javascript:void()" title="Edit" onclick="edit_role('."'".$d->id_role."'".')"><i class="bi bi-pen-fill"></i> Edit</a>
      <a class="dropdown-item" href="javascript:void()" title="Hapus" id="deleterole" value="'.$d->id_role.'"><i class="bi bi-trash"></i> Hapus</a></div></div></div>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->role_model->count_all(),
						"recordsFiltered" => $this->role_model->count_filtered(),
						"data" => $data,
				);
		//output to json format

		echo json_encode($output);
	}

  public function ajax_edit($id_role)
	{
		$data = $this->role_model->get_by_id($id_role);
		echo json_encode($data);
	}

  function add(){
		$this->_validate();

		$users = $this->session->userdata('id');
		$nama_users = $this->session->userdata('name');
		// GET NEW ID
		// $get_new_id = $this->role_model->get_new_id_cus();
    // foreach($get_new_id as $result){
    //     $kode_New_id =  $result->maxKode;
    // }
    // $noUrut = (int) substr($kode_New_id, 4, 12);
    // $noUrut++;
    // $char = "CST-";
    // $NewID = $char.str_pad($noUrut, 12, '0', STR_PAD_LEFT);
		// GET NEW ID

				$data = array(
					'namarole' => $this->input->post('namarole')
				);
				$insert = $this->role_model->insert_role($data);

				if($insert){
					// INSERT LOG

					$j = $this->input->post('namarole');
					$b = '<b>'.$nama_users.'</b> Melakukan Tambah Role <b>'.$j.'</b>';
					$data2 = array(
						'ket' => $b,
					);
					$this->role_model->insert_log_role($data2);
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
					$ajax_data['namarole'] = $this->input->post('namarole');


					if ($this->role_model->update_entry($id, $ajax_data)) {
						// INSERT LOG
						$nama_users = $this->session->userdata('name');

						$j = $this->input->post('namarole');
						$b = '<b>'.$nama_users.'</b> Melakukan Edit Role <b>'.$j.'</b>';
						$data2 = array(
							'ket' => $b,
						);
						$this->role_model->insert_log_role($data2);
						// INSERT LOG
						echo json_encode(array("status" => TRUE));
					} else {
						echo json_encode(array("status" => FALSE));
					}

	}

  public function deleterole()
	{
		if ($this->input->is_ajax_request()) {

			$id_role = $this->input->post('idkon');



				if ($this->role_model->delete_entry($id_role)) {

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
		$nama = $this->input->post('namarole');

		if($this->input->post('namarole') == '')
		{
			$data['inputerror'][] = 'namarole';
			$data['error_string'][] = 'Form Role harus berisi';
			$data['status'] = FALSE;
		}

		$namalength= strlen($nama);
		if($namalength < 3)
		{
			$data['inputerror'][] = 'namarole';
			$data['error_string'][] = 'Nama Role Miminal 3 Karakter';
			$data['status'] = FALSE;
		}
		if($namalength > 25)
		{
			$data['inputerror'][] = 'namarole';
			$data['error_string'][] = 'Nama Role Maksimal 25 Karakter';
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

		$nama = $this->input->post('namarole');

		if($this->input->post('namarole') == '')
		{
			$data['inputerror'][] = 'namarole';
			$data['error_string'][] = 'Form Nama Role harus berisi';
			$data['status'] = FALSE;
		}

		$namalength= strlen($nama);
		if($namalength < 3)
		{
			$data['inputerror'][] = 'namarole';
			$data['error_string'][] = 'Nama Miminal 3 Karakter';
			$data['status'] = FALSE;
		}
		if($namalength > 25)
		{
			$data['inputerror'][] = 'namarole';
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