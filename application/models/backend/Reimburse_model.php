<?php
class Reimburse_model extends CI_Model{


	var $tablereimburse = 'tb_reimburse';
	var $column_search_reimburse = array('id_reimburse','id_user_reimburse');


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		//add custom filter here
		if($this->input->post('id_user_reimburse'))
		{
			$this->db->like('id_user_reimburse', $this->input->post('id_user_reimburse'));
		}

		$this->db->from($this->tablereimburse);
		$i = 0;
		foreach ($this->column_search_reimburse as $item)
		{
			if($_POST['search']['value'])
			{
				if($i===0)
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search_reimburse) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}


	}
	function get_datatables(){
		$this->db->join ( 'tbl_user', 'tbl_user.user_id = tb_reimburse.id_user_reimburse' , 'left' );
		$this->db->order_by('id_reimburse', 'desc');

		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function get_datatablesmanajer($id){
		$this->db->join ( 'tbl_user', 'tbl_user.user_id = tb_reimburse.id_user_reimburse' , 'left' );
		$this->db->join ( 'tb_atasan', 'tb_reimburse.id_user_manager = tb_atasan.id_atasan' , 'left' );
		$this->db->join ( 'tb_bagian', 'tb_reimburse.id_bagian = tb_bagian.id_bagian' , 'left' );
		$this->db->where('tbl_user.user_atasan', $id);
		$this->db->order_by('id_reimburse', 'desc');
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());
		return $query->result();
	}

	function get_datatablesdirektur(){
		$this->db->join ( 'tbl_user', 'tbl_user.user_id = tb_reimburse.id_user_reimburse' , 'left' );
		$this->db->join ( 'tb_atasan', 'tb_reimburse.id_user_manager = tb_atasan.id_atasan' , 'left' );
		$this->db->join ( 'tb_bagian', 'tb_reimburse.id_bagian = tb_bagian.id_bagian' , 'left' );
		$this->db->where('tb_reimburse.status', 'DISETUJUI');
		$this->db->or_where('tb_reimburse.status', 'RILIS');
		$this->db->or_where('tb_reimburse.status', 'NONRILIS');
		$this->db->order_by('id_reimburse', 'desc');
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());
		return $query->result();
	}

	public function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->tablereimburse);
		return $this->db->count_all_results();
	}
	function get_all_karyawan(){
		$dev = 'IsWah';
		$this->db->select('tbl_user.*');
		$this->db->from('tbl_user');
		$this->db->where_not_in ( 'user_name', $dev);
		$this->db->where('user_level',3);
		$this->db->order_by('user_id', 'DESC');
		$query = $this->db->get();
		return $query;
	}
	public function single_entry($id)
    {
        $this->db->select('*');
        $this->db->from('tb_reimburse');
        $this->db->where('id_reimburse', $id);
        $query = $this->db->get();
        if (count($query->result()) > 0) {
            return $query->row();
        }
    }
	public function get_by_id($id_reimburse)
	{
		$this->db->from($this->tablereimburse);
		$this->db->where('id_reimburse',$id_reimburse);
		$query = $this->db->get();
		return $query->row();
	}
	function insert_reimburse($arraysql){
		$insert = $this->db->insert($this->tablereimburse, $arraysql);
		if($insert){
			return true;
		}
	}
   	public function update_entry($reimburseid, $ajax_data)
    {
        return $this->db->update('tb_reimburse', $ajax_data, array('id_reimburse' => $reimburseid));
    }
	public function delete_entry($id)
    {
        return $this->db->delete('tb_reimburse', array('id_reimburse' => $id));
    }

	function get_all_bukti(){

		$this->db->select('tb_reimburse.*');
		$this->db->from('tb_reimburse');
		$query = $this->db->get();
		return $query;
	}

}