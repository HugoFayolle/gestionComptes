<?php 
	try
	{
		if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password']))
		{
			$db = mysql_connect('localhost', 'root', '');
			mysql_select_db('browniemoneymanagement', $db);

			$query = mysql_query('SELECT * FROM users WHERE login="' . $_POST['login'] . '" AND pass_md5 = "' . sha1(md5($_POST['password'])) . '"');

			$dataLogs = mysql_fetch_assoc($query);
			if ($dataLogs['login'] == $_POST['login'] && $dataLogs['pass_md5'] == sha1(md5($_POST['password'])))
			{
				header('Location: index.php');
				exit();
			}
			else
			{
				header('Location: login.php');
				echo '<script type="text/javascript">document.getElementById("error").innerHTML = "Identifiants incorrectes";</script>';
			}
		}
	}
	catch (Exception $e)
	{
		echo $e;
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login - BMM - Brownie money management</title>
	<link rel="stylesheet" type="text/css" href="css/styleLogin.css">
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
</head>
<body>
	<center><h1>Brownie money management</h1>
	<h2>Login</h2>

<br><br><br><br><br><br>
	<div id="container">
        <form action="login.php" method="post">
            <label for="username">Login :</label>
             
            <input type="text" id="username" name="login" placeholder="Login" autocomplete="off" maxlength="40">
             
            <label for="password">Password :</label>
             
			<!--<p><a href="#">Mot de passe oublié ?</a></p>-->             
            <input type="password" id="password" name="password" placeholder="Mot de passe" maxlength="40">
             
            <div id="lower">
                <input type="checkbox" name="remember"><label class="check" for="checkbox">Rester connecté</label>
                <input type="submit" value="Se connecter" id="coButton" name="sumbit">
                <a id="linkNoCo" href="consult.php"><input type="button" value="Continuer sans authentification" id="noCoButton"></a>
                <p id="error"></p>
            </div>
        </form>
    </div>

	</center>
</body>
</html>