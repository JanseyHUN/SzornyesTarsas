<?PHP
header("Content-Type: text/html; charset=UTF-8");
ob_start();
require_once("./include/altalanos.php");
require_once 'db.php';
ob_end_clean();

$altalanos = new altalanos();
$db = new AdatbazisKezelo();
$db->KapcsolatKeszites();
$db->SetDataTable("felhasznalok");
/*$fgkeretrendszer->SetRandomKey('qSRcVS6DrTzrPvr');*/

if(isset($_POST['submitted']))
{
   if($db->Login())
   {
     $altalanos->RedirectToURL("loginhome.php");
   }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-hu" lang="hu-hu">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Bejelentkezés</title>
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>


<form id='login' action='login.php' method='post' accept-charset='UTF-8'>
<fieldset style='width: 15em; background-color: #F2EDDE;'>
<legend>Bejelentkezés</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<div>* kötelező mezők</div>
<br/>
<div>
    <label for='username' >Felhasználónév*:</label><br/>
    <input type='text' name='username' id='username' maxlength="50" /><br/>
    <span id='login_username_errorloc' style="color: orange;" ></span>
</div>
<div>
    <label for='jelszo' >Jelszó*:</label><br/>
    <input type="password" name='jelszo' id='jelszo' maxlength="50" /><br/>
    <span id='login_jelszo_errorloc' style="color: orange;"></span>
</div>

<div>
    <input type='submit' name='Submit' value='Bejelentkezés' />
</div>
<div><a href='reset-pwd-req.php'>Elfelejtetted a jelszavadat?</a></div>
</fieldset>
</form>

<!-- Alább található a login oldal validátora -->
<script type='text/javascript'>
// <![CDATA[

    var frmvalidator  = new Validator("login");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("username","req","Kérlek add meg a felhasználónevedet");
    
    frmvalidator.addValidation("jelszo","req","Kérlek add meg a jelszavadat");

// ]]>
</script>

<div class="footerText">
	Szörnyes Társas &copy; 2013
</div>


</body>
</html>