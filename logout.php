<?PHP
ob_start();
require_once("./include/altalanos.php");
require_once 'db.php';
ob_end_clean();
$db = new AdatbazisKezelo();
$db->KapcsolatKeszites();
$db->SetDataTable("felhasznalok");

/*$fgkeretrendszer->SetRandomKey('qSRcVS6DrTzrPvr');*/

altalanos::LogOut();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-HU" lang="hu-HU">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Kijelentkezés</title>
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
  <fieldset style='width: 15em; background-color: #F2EDDE;'>
  <legend>Kijelentkeztél</legend>
    <p><a href='login.php'>Bejelentkezés</a></p>
  </fieldset>

<div>
 Szörnyes Társas &copy; 2013
</div>

</body>
</html>