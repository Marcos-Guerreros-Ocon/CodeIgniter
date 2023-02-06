<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
		  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<title>Empleados</title>
</head>
<style>
	body {
		font-family: "Segoe UI";
	}
</style>
<script>
	function borrarEmpleado(id) {
		Swal.fire({
			title: '¿Seguro de que quieres borrar el empleado?',
			showDenyButton: true,
			confirmButtonText: 'Volver',
			denyButtonText: `Borrar`,
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isDenied) {
				window.location.href = '<?=site_url()?>empleado/baja/' + id;
			}
		})
	}
</script>
<body>
<div class="container" style="margin-top: 20px">
	<?php if ($this->session->flashdata('exito')): ?>
		<div class="alert alert-success">
			<?= $this->session->flashdata('exito') ?>
		</div>
	<?php elseif ($this->session->flashdata('fallo')): ?>
		<div class="alert alert-danger">
			<?= $this->session->flashdata('fallo') ?>
		</div>
	<?php endif; ?>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<form action="<?= base_url() ?>" method="get">
						<div class="row g-3 align-items-center">
							<div class="col-auto">
								<label for="search_text" class="col-form-label">Buscar</label>
							</div>
							<div class="col-auto">
								<input type="text" placeholder="Introduce tu búsqueda"
									   value="<?php if ($this->input->get('search_text')) {
										   echo $this->input->get('search_text');
									   } ?>" id="search_text" name="search_text" class="form-control"
									   aria-describedby="">
							</div>
							<div class="col-auto">
								<input type="submit" class="btn btn-primary" value="Search">
							</div>
						</div>
					</form>
					<?php if (count($empleados) == 0): ?>
						<p>No se encuentran empleados.</p>
					<?php else:?>
					<table class="table table-striped table-bordered" style="margin-top: 20px">
						<thead>
						<tr>
							<th>Nombre</th>
							<th>Apellidos</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($empleados as $empleado): ?>
						<tr>
							<td><?= $empleado->nombre ?></td>
							<td><?= $empleado->apellidos ?></td>
							<td>
								<button type="button" class="btn btn-primary"
										onclick=location.href="<?= site_url() . 'empleado/modificar/' . $empleado->id ?>">
									Información
								</button>
								<button type="button" class="btn btn-danger"
										onclick="borrarEmpleado(<?= $empleado->id ?>)">Borrar
								</button>
							</td>
						</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<?php endif ?>
				</div>
				<?= $links ?>
			</div>
			<button type="button" class="btn btn-success" style="margin-top: 25px"
					onclick="window.location.href = '<?= site_url() ?>empleado/alta';">Añadir empleados
			</button>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
		crossorigin="anonymous"></script>
</body>

</html>
