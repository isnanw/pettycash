<?php
class Atasan_model extends CI_Model{
/**
* Description of Controller
*
* @author isnanw
*/

	var $tableatasan = 'tb_atasan';
	var $tablelog = 'tbl_log';
	var $column_search_atasan = array('namaatasan');
	var $order = array('id_atasan' => 'ASC'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		//add custom filter here
		if($this->input->post('namaatasan'))
		{
			$this->db->like('namaatasan', $this->input->post('namaatasan'));
		}

		$this->db->from($this->tableatasan);
		$i = 0;
		foreach ($this->column_search_atasan as $item)
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

				if(count($this->column_search_atasan) - 1 == $i)
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
		$this->db->order_by('id_atasan', 'ASC');
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

	public function count_all()
	{
		$this->db->from($this->tableatasan);
		return $this->db->count_all_results();
	}

	public function get_by_id($id_atasan)
	{
		$this->db->from($this->tableatasan);
		$this->db->where('id_atasan',$id_atasan);
		$query = $this->db->get();
		return $query->row();
	}

	function insert_atasan($data){
		$insert = $this->db->insert($this->tableatasan, $data);
		if($insert){
			return true;
		}
	}

	function insert_log_atasan($data2){
		$insert = $this->db->insert($this->tablelog, $data2);
		if($insert){
			return true;
		}
	}

	public function update_entry($id, $data)
	{
			return $this->db->update('tb_atasan', $data, array('id_atasan' => $id));
	}

	public function single_entry($id_atasan)
    {
        $this->db->select('*');
        $this->db->from('tb_atasan');
        $this->db->where('id_atasan', $id_atasan);
        $query = $this->db->get();
        if (count($query->result()) > 0) {
            return $query->row();
        }
    }
    public function update_lock($id_atasan, $data)
    {
        return $this->db->update('tb_atasan', $data, array('id_atasan' => $id_atasan));
    }
    function delete_entry($id_atasan)
    {
        return $this->db->delete('tb_atasan', array('id_atasan' => $id_atasan));
    }

	function import($data){
		$insert = $this->db->insert_batch('tb_atasan', $data);
		if($insert){
			return true;
		}
	}

}


