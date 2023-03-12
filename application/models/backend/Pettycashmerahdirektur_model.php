<?php
class Pettycashmerahdirektur_model extends CI_Model{


	var $tablepettycash = 'tb_pettycash';
	var $column_search_pettycash = array('id_pettycash','id_user_pettycash');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		//add custom filter here
		if($this->input->post('id_user_pettycash'))
		{
			$this->db->like('id_user_pettycash', $this->input->post('id_user_pettycash'));
		}

		$this->db->from($this->tablepettycash);
		$i = 0;
		foreach ($this->column_search_pettycash as $item)
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

				if(count($this->column_search_pettycash) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}


	}
	function get_datatables($id){
		$this->db->join ( 'tbl_user', 'tbl_user.user_id = tb_pettycash.id_user_pettycash' , 'left' );
		$this->db->join ( 'tb_atasan', 'tb_pettycash.id_user_manager = tb_atasan.id_atasan' , 'left' );
		$this->db->join ( 'tb_bagian', 'tb_pettycash.id_bagian = tb_bagian.id_bagian' , 'left' );
		// $this->db->where('tbl_user.user_atasan', $id);
		$this->db->where('tb_pettycash.status_bonmerah', 'DISETUJUI');
		$this->db->or_where('tb_pettycash.status_bonmerah', 'RILIS');
		$this->db->order_by('id_pettycash', 'desc');
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());
		return $query->result();
	}

	public function count_filtered()
	{
		$this->db->join ( 'tbl_user', 'tbl_user.user_id = tb_pettycash.id_user_pettycash' , 'left' );
		$this->db->join ( 'tb_atasan', 'tb_pettycash.id_user_manager = tb_atasan.id_atasan' , 'left' );
		// $this->db->where('tbl_user.user_atasan', $id);
		// $this->db->where('tb_pettycash.status', 'RILIS');
		// $this->db->where('tb_pettycash.status_bonmerah', 'PENGAJUAN');
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->tablepettycash);
		$this->db->join ( 'tbl_user', 'tbl_user.user_id = tb_pettycash.id_user_pettycash' , 'left' );
		$this->db->join ( 'tb_atasan', 'tb_pettycash.id_user_manager = tb_atasan.id_atasan' , 'left' );
		// $this->db->where('tbl_user.user_atasan', $id);
		// $this->db->where('tb_pettycash.status', 'RILIS');
		// $this->db->where('tb_pettycash.status_bonmerah', 'PENGAJUAN');
		return $this->db->count_all_results();
	}
	function get_all_karyawan(){
		$dev = 'IsW';
		$this->db->select('tbl_user.*');
		$this->db->from('tbl_user');
		$this->db->where_not_in ( 'user_name', $dev);
		$this->db->order_by('user_id', 'DESC');
		$query = $this->db->get();
		return $query;
	}
	public function single_entry($id)
    {
        $this->db->select('*');
        $this->db->from('tb_pettycash');
        $this->db->where('id_pettycash', $id);
        $query = $this->db->get();
        if (count($query->result()) > 0) {
            return $query->row();
        }
    }
    public function get_by_id($id_pettycash)
	{
		$this->db->from($this->tablepettycash);
		$this->db->where('id_pettycash',$id_pettycash);
		$query = $this->db->get();
		return $query->row();
	}
	function insert_pettycash($arraysql){
		$insert = $this->db->insert($this->tablepettycash, $arraysql);
		if($insert){
			return true;
		}
	}
   	public function update_entry($pettycashid, $ajax_data)
    {
        return $this->db->update('tb_pettycash', $ajax_data, array('id_pettycash' => $pettycashid));
    }
	public function delete_entry($id)
    {
        return $this->db->delete('tb_pettycash', array('id_pettycash' => $id));
    }

	function get_all_bukti(){

		$this->db->select('tb_pettycash.*');
		$this->db->from('tb_pettycash');
		$query = $this->db->get();
		return $query;
	}

}