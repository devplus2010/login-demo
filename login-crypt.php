<?php
//////////////////////////////////////////////////////////
// login, crypted password, oo programming              //
//////////////////////////////////////////////////////////
session_start();

include 'library.php';

$items = array ('<a href="login-crypt.php"><span>Lab6</span></a>', 
				'<a href="#"><span>Item1</span></a>', 
				'<a href="#"><span>Item2</span></a>',
				'<a href="logout.php"><span>Logout</span></a>');

if ($_GET['forgot'] == "forgot") {
	html_header("Retrieve Password");
	$myMenu = new Menu($items);
	$myMenu->displayMenu();
?>
	<br><br>
	<form method="POST" action="email.php">
		<div class="login_t"><h1>Retrieve password</h1></div>
		<div class="login_b">
			<p>Enter your username</p>
			<input type="text" name="username" value="<?php print $username; ?>" size="55"><br /><br />
			<input type="submit" name="submit" value="Submit">
		</div>
	</form>
	<br>
<?php
	footer();
}
else {
	$username 		= "";
	$password 		= "";
	$loginErr 		= "";
	$valid			= true;

	if ($_POST) {
		$username = strtolower(trim(htmlentities($_POST['username'])));
		$password = trim(htmlentities($_POST['password']));

		// validate user input using regular expression
		if ( !preg_match("/^\w{4,}$/", $username) )  {
			$loginErr = "Incorrect username or password";
			$valid = false;
		}
		if ( !preg_match("/^[\w\d!@#\$%\^\&\*\(\)-\+\=\~]{4,32}$/", $password) )  {
			$loginErr = "Incorrect username or password";
			$valid = false;
		}
		
		if ($valid) {
			//Create connection to the mysql server
			$lines = file("/home/int322_123a20/secret/topsecret");
			$db = trim($lines[3]);
			
			//Create connection to the mysql server
			$myLink = new DbLink($db);

			$sql = "select username, password from users where username = '{$username}'";
			
			if ($result = $myLink->query($sql)) {
			
				if($row = mysql_fetch_assoc($result)){
					$pswd=$row['password'];
					$salt=substr($pswd, 0, 12);
					if (crypt($password, $salt) == $row['password']) {
					session_regenerate_id(); 
					$_SESSION['username'] = $username;
					}
					else {
						$loginErr = "Incorrect username or password";
					}
				}
				else {
					$loginErr = "Incorrect username or password";
				}
			}
			else {
				$loginErr = "Incorrect username or password";
			}

			// Free resultset
			mysql_free_result($result);		
		}
	}

	if (isset($_SESSION['username'])) {
		html_header("logged in");
		$myMenu = new Menu($items);
		$myMenu->displayMenu();
?>
	<p>Welcome - you are already logged in.</p>
	<a href="login-crypt.php?logout=logout" >Logout</a>
<?php
		footer();
	}
	else {
		html_header("login");
		$myMenu = new Menu($items);
		$myMenu->displayMenu();
?>
	<br><br>
	<form method="POST" action="">
		<?php if ($loginErr)  print '<div class="loginErr"><h1>' . $loginErr . '</h1></div>' ; ?>
		<div class="login_t"><h1>Log in</h1></div>
		<div class="login_b">
			<p>User Name</p>
			<input type="text" name="username" value="<?php print $username; ?>" size="55"><br /><br />
			<p>Password <a href="login-crypt.php?forgot=forgot">(Forgot password)</a></p>
			<input type="text" name="password" value="<?php print $password; ?>" size="55"><br /><br />
			<input type="submit" name="submit" value="Submit">
		</div>
	</form>
	<br>
<?php
		footer();
	}
}
?>
