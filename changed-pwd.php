<?PHP
header("Content-Type: text/html; charset=UTF-8");
ob_start();
require_once("./include/altalanos.php");
require_once 'db.php';
ob_end_clean();
$db = new AdatbazisKezelo();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-HU" lang="hu-HU">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>A jelszó megváltozott</title>
</head>
<body>

<fieldset style='width: 15em; background-color: #F2EDDE;'>
<legend>A jelszó megváltozott</legend>
A jelszavad frissítve lett!

<p><a href='logout.php'>Kijelentkezés</a></p>
</fieldset>

<div class="footerText">
	Szörnyes Társas &copy; 2013
</div>

</body>
</html>
