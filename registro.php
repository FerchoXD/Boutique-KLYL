<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login boutique - KLYL</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body style="background-color: #666666;">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="POST" action="">
					<span class="login100-form-title p-b-43">
						Registrate
					</span>

					<div class="wrap-input100 validate-input" data-validate="el nombre es requerido">
						<input class="input100" type="text" name="name">
						<span class="focus-input100"></span>
						<span class="label-input100">Nombre</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="El apellido es requerido">
						<input class="input100" type="text" name="lastname">
						<span class="focus-input100"></span>
						<span class="label-input100">Apellido</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="El telefono es requerido">
						<input class="input100" type="tel" name="phone">
						<span class="focus-input100"></span>
						<span class="label-input100">Telefono</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Ingresa un email valido">
						<input class="input100" type="text" name="email">
						<span class="focus-input100"></span>
						<span class="label-input100">Email</span>
					</div>
					
					<div class="wrap-input100 validate-input" data-validate="No olvides colocar la contraseña">
						<input class="input100" type="password" name="pass">
						<span class="focus-input100"></span>
						<span class="label-input100">Password</span>
					</div>

			
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Registrarse
						</button>
					</div>
					
				</form>

				<div class="login100-more" style="background-image: url('images/bg-01.jpg');">
				</div>
			</div>
		</div>
	</div>
	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<script src="js/main.js"></script>
</body>
</html>

<?php
//Conexion a la base de datos
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    // Validaciones básicas
    if (empty($name) || empty($lastname) || empty($phone) || empty($email) || empty($password)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Verificamos si el email ya está registrado
    $sql_check = "SELECT * FROM usuarios WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('s', $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "El correo ya está registrado.";
        exit();
    }
	
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql_insert = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param('sss', $name, $email, $hashed_password);

    if ($stmt_insert->execute()) {
		header('Location: login.php');
		exit();

    } else {
        echo "Ocurrió un error al registrar al usuario.";
    }

    // Cerrar conexiones
    $stmt_check->close();
    $stmt_insert->close();
    $conn->close();
} else {
}
?>

