<?php
require_once "config.php";
session_start([
	'cookie_lifetime' => 2592000,
	'read_and_close'  => true,
]);

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	header("location: index.php");
	exit;
}

$username = $password = $userRank = "";
$username_err = $password_err = $login_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = array_key_exists('username', $_POST) ? trim($_POST['username']) : null;
	$password = array_key_exists('password', $_POST) ? trim($_POST['password']) : null;
	if (empty($username)) {
		$username_err = "Please enter username.";
	} else {
		$username = trim($_POST["username"]);
	}
	if (empty($password)) {
		$password_err = "Please enter your password.";
	} else {
		$password = trim($_POST["password"]);
	}
	if (empty($username_err) && empty($password_err)) {
		$sql = "SELECT userId, username, password, userRank, finishTime, language FROM users WHERE username = ?";
		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = $username;
			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_store_result($stmt);
				if (mysqli_stmt_num_rows($stmt) == 1) {
					mysqli_stmt_bind_result($stmt, $myid, $username, $hashed_password, $userRank, $finishTime, $language);
					if (mysqli_stmt_fetch($stmt)) {
						if (password_verify($password, $hashed_password)) {
							session_start();
							$_SESSION["loggedin"] = true;
							$_SESSION["myid"] = $myid;
							$_SESSION["username"] = $username;
							$_SESSION["userRank"] = $userRank;
							$_SESSION["finishTime"] = $finishTime;
							$_SESSION["lang"] = $language;
							setcookie('lang', $language);
							setcookie('finishTime', $finishTime);
							header("location: index.php");
						} else {
							$username_err = "Invalid Username.";
						}
					}
				} else {
					$username_err = "Invalid username or it doesn't exist.";
				}
			} else {
				echo "Oops! Something went wrong. Please try again later.";
			}
			mysqli_stmt_close($stmt);
		}
	}
	mysqli_close($link);
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>Register - SB Admin</title>
	<link href="css/styles.css" rel="stylesheet" />
	<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
	<div id="layoutAuthentication">
		<div id="layoutAuthentication_content">
			<h4 class="text-center font-weight-light my-4">Workflow</h4>
			<main>
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-5">
							<div class="card shadow-lg border-0 rounded-lg mt-5">
								<div class="card-header">
									<h3 class="text-center font-weight-light my-4">Create Account</h3>
								</div>
								<div class="card-body">
									<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

										<div class="form-floating mb-3 mb-md-3">
											<input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
											<label for="name">Username</label> <span class="invalid-feedback"><?php echo $username_err; ?></span>
										</div>

										<div class="form-floating mb-3 mb-md-3">
											<input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
											<label for="Password"> Password</label> <span class="invalid-feedback"><?php echo $password_err; ?></span>
										</div>


										<div class="mt-4 mb-0">
											<div class="d-grid">
												<input class="btn btn-dark btn-block" type="submit" value="Login"><BR>
											</div>
											<div class="d-grid">
												<a type="reset" class="btn btn-secondary ml-2" value="Reset" href="register.php">Reset</a>
											</div>
										</div>
									</form>
								</div>
								<div class="card-footer text-center py-3">
									<div class="small"><a href="register.php">Dont you have an account? Go to register</a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main>
		</div>
		<div id="layoutAuthentication_footer">
			<footer class="py-4 bg-light mt-auto">
				<div class="container-fluid px-4">
					<div class="d-flex align-items-center justify-content-between small">
						<div class="text-muted">Copyright &copy; Workflow 2022</div>

					</div>
				</div>
			</footer>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
	<script src="js/scripts.js"></script>
</body>

</html>