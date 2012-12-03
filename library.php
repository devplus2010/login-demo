<?php
/*///////////////////////////////////////////////////////////////////////////////////////////////////////////
File                   : library.php
Purpose                : This program includes three functions to print out html header, menu bar, and footer.
////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
class DbLink {
	private $link;
	private $lines;
	private $user;
	private $pw;
	private $host;
	
	public function __construct ($database_name) {
		$this->lines = file("/home/int322_123a20/secret/topsecret");
		$this->user = trim($this->lines[0]);
		$this->pw = trim($this->lines[1]);
		$this->host = trim($this->lines[2]);

		$this->link = mysql_connect ($this->host, $this->user, $this->pw);
		mysql_select_db ($database_name, $this->link);
	}
	
	public function query ($sql_query) {
		$result = mysql_query ($sql_query, $this->link);
		return $result;
	}
	
	public function emptyResult($sql_query) {
		$result = $this->query($sql_query) or die('error: ' . mysql_error());
		if(!result) {
			$rtn = true;
		}
		else {
			$row = mysql_fetch_assoc($result);
			if(!$row) {
				$rtn = true;
			} else {
				$rtn = false;
			}
		}
		return $rtn;
	}
	
	public function __destruct() {
		mysql_close ($this->link);
	}
}

class Menu {

	private $menuStr;

	public function __construct($items) {

		$this->menuStr = "\n\t<div class='menu'>\n\t\t<ul>\n\t\t\t<li>\n";
		foreach($items as $item) {
			$this->menuStr .= "\t\t\t\t{$item}\n";
		}
		$this->menuStr .=  "\t\t\t</li>\n\t\t</ul>\n\t</div>\n";
	}
	
	function displayMenu() {
		print $this->menuStr;
	}
}

// This function prints out html header
function html_header($title) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?php print $title; ?></title>
    <link rel="stylesheet" href="css/reset.css"   type="text/css" media="screen" /> 
    <link rel="stylesheet" href="css/lab6.css" type="text/css" media="screen" /> 
</head>
<body>
<?php
}

// This function prints out html footer
function footer() {

//	  date_default_timezone_set('America/Toronto');
//    print "\t<div class='footer'>\n";
//    copy right and time 
//    print ("\t\t<p>This page &copy;2012 generated on " . date ("D d M Y H:i:s") . " by PHP</p>\n");
?>
	</div>
</body>
</html>
<?php
}
?>