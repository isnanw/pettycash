<?php
class Bagian_model extends CI_Model{
/**
* Description of Controller
*
* @author isnanw
*/

	var $tablebagian = 'tb_bagian';
	var $tablelog = 'tbl_log';
	var $column_search_bagian = array('namabagian');
	var $order = array('id_bagian' => 'ASC'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		//add custom filter here
		if($this->input->post('namabagian'))
		{
			$this->db->like('namabagian', $this->input->post('namabagian'));
		}

		$this->db->from($this->tablebagian);
		$i = 0;
		foreach ($this->column_search_bagian as $item)
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

				if(count($this->column_search_bagian) - 1 == $i)
					$this->db->group_end();
			}
			$column_search_stock[$i] = $item; // set column array variable to order processing
			$i++;
		}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_search_stock[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}

	}

	function get_datatables(){
		$this->db->order_by('id_bagian', 'ASC');
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	public function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_all_bagian(){
		$this->db->select('tb_bagian.*');
		$this->db->from('tb_bagian');
		$this->db->order_by('id_bagian', 'ASC');
		$query = $this->db->get();
		return $query;
	}

	public function count_all()
	{
		$this->db->from($this->tablebagian);
		return $this->db->count_all_results();
	}

	public function get_by_id($id_bagian)
	{
		$this->db->from($this->tablebagian);
		$this->db->where('id_bagian',$id_bagian);
		$query = $this->db->get();
		return $query->row();
	}

	function insert_bagian($data){
		$insert = $this->db->insert($this->tablebagian, $data);
		if($insert){
			return true;
		}
	}

	function insert_log_bagian($data2){
		$insert = $this->db->insert($this->tablelog, $data2);
		if($insert){
			return true;
		}
	}

	public function update_entry($id, $data)
	{
			return $this->db->update('tb_bagian', $data, array('id_bagian' => $id));
	}

	public function single_entry($id_bagian)
    {
        $this->db->select('*');
        $this->db->from('tb_bagian');
        $this->db->where('id_bagian', $id_bagian);
        $query = $this->db->get();
        if (count($query->result()) > 0) {
            return $query->row();
        }
    }
    public function update_lock($id_bagian, $data)
    {
        return $this->db->update('tb_bagian', $data, array('id_bagian' => $id_bagian));
    }
    function delete_entry($id_bagian)
    {
        return $this->db->delete('tb_bagian', array('id_bagian' => $id_bagian));
    }

	function import($data){
		$insert = $this->db->insert_batch('tb_bagian', $data);
		if($insert){
			return true;
		}
	}

}


