<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login boutique - KLYL</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="/Login_registro/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="/Login_registro/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/Login_registro/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/Login_registro/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="/Login_registro/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="/Login_registro/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="/Login_registro/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="/Login_registro/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="/Login_registro/vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="/Login_registro/css/util.css">
	<link rel="stylesheet" type="text/css" href="/Login_registro/css/main.css">
</head>
<body style="background-color: #666666;">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="POST">
					<span class="login100-form-title p-b-43">
						Inicia sesión para continuar
					</span>
					
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="text" name="email">
						<span class="focus-input100"></span>
						<span class="label-input100">Email</span>
					</div>
					
					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="pass">
						<span class="focus-input100"></span>
						<span class="label-input100">Contraseña</span>
					</div>

					<div class="flex-sb-m w-full p-t-3 p-b-32">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Recuérdame
							</label>
						</div>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
					
					<div class="text-center p-t-46 p-b-20">
						<span class="txt2">
							¿No tienes una cuenta? <a href="/Login_registro/registro.php">Regístrate</a>
						</span>
					</div>
				</form>

				<div class="login100-more" style="background-image: url('/Login_registro/images/bg-01.jpg');">
				</div>
			</div>
		</div>
	</div>
	
	<script src="/Login_registro/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="/Login_registro/vendor/animsition/js/animsition.min.js"></script>
	<script src="/Login_registro/vendor/bootstrap/js/popper.js"></script>
	<script src="/Login_registro/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="/Login_registro/vendor/select2/select2.min.js"></script>
	<script src="/Login_registro/vendor/daterangepicker/moment.min.js"></script>
	<script src="/Login_registro/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="/Login_registro/vendor/countdowntime/countdowntime.js"></script>
	<script src="/Login_registro/js/main.js"></script>

</body>
</html>

<?php
require '../conexion.php'; // Archivo de conexión
require '../Usuario.php'; // Modelo de Usuario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['pass'];

    // Busca el usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usuario = new Usuario($row['id'], $row['nombre'], $row['email'], $row['password']);

        // Verificar contraseña
        if (password_verify($password, $usuario->getPassword())) {
            session_start();
            header('Location: ../index.html');
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
} else {
}
?>
