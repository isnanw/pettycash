<?php
class Role_model extends CI_Model{
/**
* Description of Controller
*
* @author isnanw
*/

	var $tablerole = 'tb_role';
	var $tablelog = 'tbl_log';
	var $column_search_role = array('namarole');
	var $order = array('id_role' => 'ASC'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		//add custom filter here
		if($this->input->post('namarole'))
		{
			$this->db->like('namarole', $this->input->post('namarole'));
		}

		$this->db->from($this->tablerole);
		$i = 0;
		foreach ($this->column_search_role as $item)
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

				if(count($this->column_search_role) - 1 == $i)
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
		$this->db->order_by('id_role', 'ASC');
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
		$this->db->from($this->tablerole);
		return $this->db->count_all_results();
	}

	public function get_by_id($id_role)
	{
		$this->db->from($this->tablerole);
		$this->db->where('id_role',$id_role);
		$query = $this->db->get();
		return $query->row();
	}

	function insert_role($data){
		$insert = $this->db->insert($this->tablerole, $data);
		if($insert){
			return true;
		}
	}

	function insert_log_role($data2){
		$insert = $this->db->insert($this->tablelog, $data2);
		if($insert){
			return true;
		}
	}

	public function update_entry($id, $data)
	{
			return $this->db->update('tb_role', $data, array('id_role' => $id));
	}

	public function single_entry($id_role)
    {
        $this->db->select('*');
        $this->db->from('tb_role');
        $this->db->where('id_role', $id_role);
        $query = $this->db->get();
        if (count($query->result()) > 0) {
            return $query->row();
        }
    }
    public function update_lock($id_role, $data)
    {
        return $this->db->update('tb_role', $data, array('id_role' => $id_role));
    }
    function delete_entry($id_role)
    {
        return $this->db->delete('tb_role', array('id_role' => $id_role));
    }

	function import($data){
		$insert = $this->db->insert_batch('tb_role', $data);
		if($insert){
			return true;
		}
	}

}


