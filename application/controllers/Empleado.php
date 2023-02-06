<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Empleado extends CI_Controller
{

	public function index()
	{
		$this->load->model('EmpleadoModelo');
		$this->load->library('pagination');


		$perPage = 3;

		$page = 0;

		if ($this->input->get('page')) {
			$page = $this->input->get('page');
		}

		$start_index = 0;
		if ($page != 0) {
			$start_index = $perPage * ($page - 1);
		}

		$total_rows = 0;

		if ($this->input->get('search_text') != null) {
			$search_text = $this->input->get('search_text');
			$this->data['empleados'] = $this->EmpleadoModelo->getSearchUsers($perPage, $start_index, $search_text, $is_count = 0)->result();
			$total_rows = $this->EmpleadoModelo->getSearchUsers(null, null, $search_text, $is_count = 1);
		} else {
			$this->data['empleados'] = $this->EmpleadoModelo->getSearchUsers($perPage, $start_index, null, $is_count = 0)->result();
			$total_rows = $this->EmpleadoModelo->getSearchUsers(null, null, null, $is_count = 1);
		}

		$this->configurarPaginador($total_rows);
		$this->data['page'] = $page;
		$this->data['links'] = $this->pagination->create_links();
		$this->load->view('empleados', $this->data);
	}

	function alta()
	{
		$this->load->view("altaEmpleado");
	}

	public function modificar($id = 0)
	{
		$this->load->model('EmpleadoModelo');
		$data['empleado'] = $this->EmpleadoModelo->obtenerEmpleado($id);

		if ($id == 0):
			$this->session->set_flashdata('fallo', 'Error al obtener los datos');;
			redirect(base_url());
			return;
		endif;

		$data['empleado'] = $this->EmpleadoModelo->obtenerEmpleado($id);
		$data['amortizaciones'] = $this->EmpleadoModelo->obtenerAmortizacionesEmpleado($id);

		$this->load->view('empleado', $data);

	}

	function baja($id)
	{

		$this->load->model('EmpleadoModelo');
		$this->load->helper('file');
		$data = $this->EmpleadoModelo->obtenerEmpleado($id);

		$exito = true;
		if ($data[0]->foto != null):
			$exito = unlink("./uploads/" . $data[0]->foto);
		endif;

		if (!$exito):
			$this->session->set_flashdata('fallo', 'Error al borrar al empleado');
			redirect(base_url());
		endif;

		$exito = $this->EmpleadoModelo->borrarEmpleado($id);
		if ($exito):
			$this->session->set_flashdata('exito', 'Empleado borrado con exito');
		else:
			$this->session->set_flashdata('fallo', 'Error al borrar al empleado');
		endif;

		redirect(base_url());

	}

	public function accionAlta()
	{

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = 500;
		$config['max_width'] = 1024;
		$config['max_height'] = 768;

		$this->load->library('upload', $config);
		$this->load->library('session');
		$this->load->model('EmpleadoModelo');

		$data['nombre'] = $this->input->post('nombre');
		$data['apellidos'] = $this->input->post('apellidos');
		$data['fecha'] = $this->input->post('fecha');
		$data['foto'] = null;

		$exito = true;
		if (!$this->validarFormulario()):
			$this->alta();
			return;
		endif;

		if (!empty($_FILES['foto']['name'])):
			$exito = $this->upload->do_upload('foto');
			$data['foto'] = $this->upload->data('file_name');

		endif;


		if (!$exito):
			$this->session->set_flashdata('fallo', 'Error al subir la imagen');
			$this->alta();
			return;
		endif;

		$exito = $this->EmpleadoModelo->insertarEmpleado($data);
		if ($exito):
			$this->session->set_flashdata('exito', 'Empleado añadido con exito');
		else:
			$this->session->set_flashdata('fallo', 'Error al añadir al empleado');
		endif;

		redirect(base_url());
	}

	public function accionModificar($id)
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = 500;
		$config['max_width'] = 1024;
		$config['max_height'] = 768;

		$this->load->library('upload', $config);
		$this->load->library('session');
		$this->load->model('EmpleadoModelo');
		$this->load->helper('file');

		$nombre = $this->input->post('nombre');
		$apellidos = $this->input->post('apellidos');
		$fecha = $this->input->post('fecha');


		if (!$this->validarFormulario()):
			$this->modificar($id);
			return;
		endif;

		$foto = null;
		$tratoFichero = true;
		$empleado = $this->EmpleadoModelo->obtenerEmpleado($id);

		if ($_FILES['foto']['name'] != null):
			$tratoFichero = $this->upload->do_upload('foto');
			$foto = $this->upload->data('file_name');
			if ($empleado[0]->foto != null && $tratoFichero):
				unlink("./uploads/" . $empleado[0]->foto);
			endif;
		else:
			$foto = $empleado[0]->foto;
		endif;

		$datos['nombre'] = $nombre;
		$datos['apellidos'] = $apellidos;
		$datos['fecha'] = $fecha;
		$datos['foto'] = $foto;


		$exito = false;
		if ($tratoFichero):
			$exito = $this->EmpleadoModelo->modificarEmpleado($id, $datos);
		endif;
		if ($exito):
			$this->session->set_flashdata('exitos', 'Empleado modificado con exito');
		else:
			$this->session->set_flashdata('fallos', 'Error al modificar el empleado');
		endif;
		redirect(base_url() . 'empleado/modificar/' . $id);
	}

	public function validarFormulario()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre', 'Nombre', 'required');
		$this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
		$this->form_validation->set_rules('fecha', 'Fecha', 'required');

		return $this->form_validation->run();
	}

	public function borrarImagen($id)
	{
		$this->load->helper('file');
		$this->load->model('EmpleadoModelo');
		$empleado = $this->EmpleadoModelo->obtenerEmpleado($id);

		$exito = unlink("./uploads/" . $empleado[0]->foto);

		if (!$exito):
			$this->session->set_flashdata('fallos', 'Error al borrar la foto del empleado');
			redirect(base_url() . 'empleado/modificar/' . $id);
		endif;

		$datos['nombre'] = $empleado[0]->nombre;
		$datos['apellidos'] = $empleado[0]->apellidos;
		$datos['fecha'] = $empleado[0]->fecha;
		$datos['foto'] = null;

		$exito = $this->EmpleadoModelo->modificarEmpleado($empleado[0]->id, $datos);

		if ($exito):
			$this->session->set_flashdata('exitos', 'Empleado modificado con exito');
		else:
			$this->session->set_flashdata('fallos', 'Error al modificar el empleado');
		endif;

		redirect(base_url() . 'empleado/modificar/' . $id);
	}

	public function validarAmortizacion()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('numeroHoras', 'Numero de horas', 'required');
		$this->form_validation->set_rules('precioHora', 'Precio hora', 'required');

		return $this->form_validation->run();
	}

	public function agregarAmortizacion()
	{
		$this->load->library('session');
		$this->load->model('EmpleadoModelo');

		$data['idEmpleado'] = $this->input->post('id');
		$data['numeroHoras'] = $this->input->post('numeroHoras');
		$data['precioHora'] = $this->input->post('precioHora');

		if (!$this->validarAmortizacion()):
			$this->modificar($data['idEmpleado']);
			return;
		endif;

		$exito = $this->EmpleadoModelo->agregarAmortizacion($data);
		if ($exito):
			$this->session->set_flashdata('exitos', 'Amortización agregada con exito');
		else:
			$this->session->set_flashdata('fallos', 'Error al agregar la amortización');
		endif;

		redirect(base_url() . 'empleado/modificar/' . $data['idEmpleado']);

	}

	public function borrarAmortizacion($id)
	{
		$this->load->library('session');
		$this->load->model('EmpleadoModelo');

		$amortizacion = $this->EmpleadoModelo->obtenerAmortizacion($id);

		$exito = $this->EmpleadoModelo->borrarAmortizacion($id);
		if ($exito):
			$this->session->set_flashdata('exitos', 'Amortización borrada con exito');
		else:
			$this->session->set_flashdata('fallos', 'Error al borrar la amortización');
		endif;

		redirect(base_url() . 'empleado/modificar/' . $amortizacion[0]->idEmpleado);
	}

	public function configurarPaginador($total_rows)
	{
		$perPage = 3;

		$config['base_url'] = base_url();
		$config['total_rows'] = $total_rows;

		$config['per_page'] = $perPage;
		$config['enable_query_strings'] = true;
		$config['use_page_numbers'] = true;
		$config['page_query_string'] = true;
		$config['query_string_segment'] = 'page';
		$config['reuse_query_string'] = true;
		$config['full_tag_open'] = '<ul  class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li  class="page-item"><spann class="page-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';

		$config['prev_tag_close'] = '</span></li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close'] = '</span></li>';
		$config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close'] = '</span></li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close'] = '</span></li>';

		$this->pagination->initialize($config);
	}

}
