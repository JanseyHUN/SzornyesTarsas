<?PHP
ob_start();
require_once("./include/altalanos.php");
require_once 'db.php';
ob_end_clean();
//$fgkeretrendszer->SetWebsiteName('http://susu.jgypk.hu/~csjlacf/');
$db = new AdatbazisKezelo();
$db->KapcsolatKeszites();
$db->SetDataTable("felhasznalok");

/*altalanos::SetRandomKey('qSRcVS6DrTzrPvr');*/

$success = false;
if( altalanos::ResetPassword() )
{
    $success=true;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-HU" lang="hu-HU">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Jelszó beállítás</title>
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>

<fieldset style='width: 15em; background-color: #F2EDDE;'>
<legend>Jelszó beállítás</legend>
<?php
if($success){
  echo "Sikeres jelszó belállítás! Az új jelszavad el lett küldve e-mail-ben!";
}else{
  echo "<h2>Hiba</h2>";
  echo "<span style='color: orange;'>";
  echo $db->GetErrorMessage();
  echo "</span>";
}  
?>

</fieldset>
<div>
	Szörnyes Társas &copy; 2013
</div>
</body>
</html>