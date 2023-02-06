<?php

class EmpleadoModelo extends CI_Model
{
	public function obtenerEmpleados()
	{
		return $this->db->get('empleado')->result();
	}
	public function obtenerEmpleado($id)
	{
		return $this->db->get_where('empleado',array('id'=>$id))->result();
	}

	public function obtenerAmortizacionesEmpleado($id){
		return $this->db->get_where('amortizacion',array('idEmpleado'=>$id))->result();
	}

	public function agregarAmortizacion($data){
		return $this->db->insert('amortizacion', $data);
	}

	public function borrarAmortizacion($id){
		return $this->db->delete('amortizacion', array('id' => $id));
	}

	public function obtenerAmortizacion($id){
		return $this->db->get_where('amortizacion',array('id'=>$id))->result();
	}
	function getSearchUsers($perPage, $start_index, $search_text = null, $is_count = 0)
	{
		if ($search_text != NULL) {
			$this->db->like('nombre', $search_text, 'both');
			$this->db->or_like('apellidos', $search_text, 'both');
		}

		if ($is_count == 1) {
			$query = $this->db->get('empleado');
			return $query->num_rows();
		} else {
			return $this->db->get('empleado',$perPage, $start_index);

		}
	}

	public function insertarEmpleado($data)
	{
		return $this->db->insert('empleado', $data);
	}

	public function borrarEmpleado($id)
	{
		return $this->db->delete('empleado', array('id' => $id));
	}

	public function modificarEmpleado($id,$data){
		$this->db->where('id', $id);
		return $this->db->update('empleado', $data);
	}


}
