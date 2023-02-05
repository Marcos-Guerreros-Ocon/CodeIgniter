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

	<title>Hello, world!</title>
</head>
<body>
<div class="container" style="margin-top: 20px">
	<?php if ($this->session->flashdata('fallo')): ?>
		<div class="alert alert-danger">
			<?= $this->session->flashdata('fallo') ?>
		</div>
	<?php endif; ?>


	<div class="w-50 p-3 col-md-8 offset-md-2">
		<?= form_open_multipart('empleado/accionAlta'); ?>
		<div class="mb-3">
			<label for="nombre" class="form-label">Nombre</label>
			<input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre') ?>">
			<?php echo form_error('nombre'); ?>
		</div>
		<div class="mb-3">
			<label for="apellidos" class="form-label">Apellidos</label>
			<input type="text" class="form-control" id="apellidos" name="apellidos"
				   value="<?= set_value('apellidos') ?>">
			<?php echo form_error('apellidos'); ?>
		</div>
		<div class="mb-3">
			<label for="fecha" class="form-label">Fecha</label>
			<input type="date" class="form-control" id="fecha" name="fecha" value="<?= set_value('fecha') ?>">
			<?php echo form_error('fecha'); ?>
		</div>
		<div class="mb-3">
			<label for="foto" class="form-label">Foto</label>
			<input type="file" class="form-control" id="foto" name="foto">
		</div>

		<button type="submit" class="btn btn-success">Añadir</button>
		<button type="button" class="btn btn-primary" onclick=location.href='<?= base_url() ?>'>Volver</button>
		<?= form_close() ?>
	</div>
</div>
</body>

</html>
