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
	<title>Empleado</title>
</head>
<script>
	function borrarAmortizacion(id) {
		Swal.fire({
			title: '¿Seguro de que quieres la amortizacion?',
			showDenyButton: true,
			confirmButtonText: 'Volver',
			denyButtonText: `Borrar`,
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isDenied) {
				location.href="<?=site_url().'empleado/borrarAmortizacion/'?>"+id;
			}
		})
	}
</script>
<style>
	.container {
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		justify-content: space-evenly;
</style>
<body>
<?php if ($this->session->flashdata('exitos')): ?>
	<div class="alert alert-success">
		<?= $this->session->flashdata('exitos') ?>
	</div>
<?php elseif ($this->session->flashdata('fallos')): ?>
	<div class="alert alert-danger">
		<?= $this->session->flashdata('fallos') ?>
	</div>
<?php endif; ?>
<div class="container" style="margin-top: 20px">

	<div class="w-25">

		<?= form_open_multipart(site_url() . 'empleado/accionModificar/'.$empleado[0]->id); ?>
		<div class="mb-3">
			<label for="nombre" class="form-label">Nombre</label>
			<input type="text" class="form-control" id="nombre" name="nombre" value="<?= $empleado[0]->nombre ?>">
			<?php echo form_error('nombre'); ?>
		</div>
		<div class="mb-3">
			<label for="apellidos" class="form-label">Apellidos</label>
			<input type="text" class="form-control" id="apellidos" name="apellidos"
				   value="<?= $empleado[0]->apellidos ?>">
			<?php echo form_error('apellidos'); ?>
		</div>
		<div class="mb-3">
			<label for="fecha" class="form-label">Fecha</label>
			<input type="date" class="form-control" id="fecha" name="fecha" value="<?= $empleado[0]->fecha ?>">
			<?php echo form_error('fecha'); ?>
		</div>
		<div class="mb-3">
			<label for="foto" class="form-label">Foto</label>
			<input type="file" class="form-control" id="foto" name="foto">
		</div>

		<button type="submit" name="modificar" class="btn btn-success">Modificar</button>
		<?php if ($empleado[0]->foto != null): ?>
			<button type="button" name="borrarFoto" class="btn btn-danger"
					onclick=location.href='<?= base_url() . 'empleado/borrarImagen/'.$empleado[0]->id ?>'>Borrar foto
			</button>
		<?php endif; ?>
		<button type="button" class="btn btn-primary" onclick=location.href='<?= base_url() ?>'>Volver</button>
		<?= form_close() ?>
	</div>
	<div class="w-25">
		<?php if ($empleado[0]->foto != null): ?>
			<img src="<?= base_url() . "uploads/" . $empleado[0]->foto ?>" style="width: 250px;">
		<?php endif; ?>
	</div>
</div>
<div class="container" style="margin-top: 20px">
	<div class="w-25">
		<form action="<?= site_url() . 'empleado/agregarAmortizacion' ?>" method="post" id="formularioAmortizacion">
			<input type="hidden" name="id" value="<?= $empleado[0]->id ?>">
			<div class="mb-3">
				<label for="numeroHoras" class="form-label">Número horas</label>
				<input type="number" class="form-control" id="numeroHoras" name="numeroHoras" min="1" value="1">
				<?php echo form_error('numeroHoras'); ?>
			</div>
			<div class="mb-3">
				<label for="precioHora" class="form-label">Precio Hora</label>
				<input type="number" class="form-control" id="precioHora" name="precioHora" min="0.01" step="0.01"
					   value="1">
				<?php echo form_error('precioHora'); ?>
			</div>
			<button type="submit" name="agregarAmortizacion" class="btn btn-success">Añadir amortización</button>
		</form>
	</div>
	<div class="w-25">
		<?php if (count($amortizaciones) == 0): ?>
			<p>Sin amortizaciones</p>
		<?php else: ?>
			<table class="table table-striped table-bordered" style="margin-top: 20px">
				<thead>
				<tr>
					<th>Horas</th>
					<th>Precio hora</th>
					<th>Total</th>
					<th>Acción</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($amortizaciones as $amortizacion): ?>
					<tr>
						<td><?= $amortizacion->numeroHoras ?></td>
						<td><?= $amortizacion->precioHora ?></td>
						<td><?= $amortizacion->numeroHoras * $amortizacion->precioHora ?></td>
						<td>
							<button type="button" class="btn btn-danger" name="borrarAmortizacion"
									onclick="borrarAmortizacion(<?= $amortizacion->id ?>)">
								Borrar
							</button>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
</div>
</div>
</body>
</html>
