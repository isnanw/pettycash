<?php
class Belanja_model extends CI_Model{
/**
* Description of Controller
*
* @author isnanw
*/

	var $tablebelanja = 'tb_belanja';
	var $tablelog = 'tbl_log';
	var $column_search_belanja = array('namabelanja');
	var $order = array('id_belanja' => 'ASC'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		//add custom filter here
		if($this->input->post('namabelanja'))
		{
			$this->db->like('namabelanja', $this->input->post('namabelanja'));
		}

		$this->db->from($this->tablebelanja);
		$i = 0;
		foreach ($this->column_search_belanja as $item)
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

				if(count($this->column_search_belanja) - 1 == $i)
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

	function get_datatables($bon){
		// $this->db->where('id_pettycash', $bon);	
		$this->db->order_by('id_belanja', 'ASC');
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		$this->db->last_query();
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
		$this->db->from($this->tablebelanja);
		return $this->db->count_all_results();
	}

	public function get_by_id($id_belanja)
	{
		$this->db->from($this->tablebelanja);
		$this->db->where('id_belanja',$id_belanja);
		$query = $this->db->get();
		return $query->row();
	}

	function insert_belanja($data){
		$insert = $this->db->insert($this->tablebelanja, $data);
		if($insert){
			return true;
		}
	}

	function insert_log_belanja($data2){
		$insert = $this->db->insert($this->tablelog, $data2);
		if($insert){
			return true;
		}
	}

	public function update_entry($id, $data)
	{
			return $this->db->update('tb_belanja', $data, array('id_belanja' => $id));
	}

	public function single_entry($id_belanja)
    {
        $this->db->select('*');
        $this->db->from('tb_belanja');
        $this->db->where('id_belanja', $id_belanja);
        $query = $this->db->get();
        if (count($query->result()) > 0) {
            return $query->row();
        }
    }
    public function update_lock($id_belanja, $data)
    {
        return $this->db->update('tb_belanja', $data, array('id_belanja' => $id_belanja));
    }
    function delete_entry($id_belanja)
    {
        return $this->db->delete('tb_belanja', array('id_belanja' => $id_belanja));
    }

	function import($data){
		$insert = $this->db->insert_batch('tb_belanja', $data);
		if($insert){
			return true;
		}
	}

}


