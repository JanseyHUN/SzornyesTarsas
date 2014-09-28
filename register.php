<?PHP
header("Content-Type: text/html; charset=UTF-8");
ob_start();
require_once("./include/altalanos.php");
require_once 'db.php';
ob_end_clean();
$db = new AdatbazisKezelo();
$db->KapcsolatKeszites();
$db->SetWebsiteName('http://fejesattila.ininet.hu');
$db->SetDataTable("felhasznalok");

if(isset($_POST['submitted']))
{
   if($db->RegisterUser())
   {
        altalanos::RedirectToURL("koszonjuk_reg.php");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-hu" lang="hu-hu">
<head>
    <title>Regisztráció</title>
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>

<form id='register' action='register.php' method='post' accept-charset='UTF-8'>
<fieldset style='width: 15em; background-color: #F2EDDE;'>
<legend>Regisztráció</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<div style="color: orange;"><span><?php echo $db->GetErrorMessage(); ?></span></div>

<div>* kötelező mezők</div>

<div>
    <label for='teljesnev' >Teljes neved*: </label><br/>
    <input type='text' name='teljesnev' id='teljesnev' value='<?php echo altalanos::SafeDisplay('nev') ?>' maxlength="50" /><br/>
    <span id='register_teljesnev_errorloc' style="color: orange;"></span>
</div>
<div>
    <label for='email' >Email cím*:</label><br/>
    <input type='text' name='email' id='email' value='<?php echo altalanos::SafeDisplay('email') ?>' maxlength="50" /><br/>
    <span id='register_email_errorloc' style="color: orange;"></span>
</div>
<div>
    <label for='username' >Felhasználónév*:</label><br/>
    <input type='text' name='username' id='username' value='<?php echo altalanos::SafeDisplay('username') ?>' maxlength="50" /><br/>
    <span id='register_username_errorloc' style="color: orange;"></span>
</div>
<div>
    <label for='jelszo' >Jelszó*:</label><br/>
    <input type='password' name='jelszo' id='jelszo' maxlength="50" />
    <div id='register_jelszo_errorloc'  style="color: orange;" style='clear:both'></div>
</div>

<div>
    <input type='submit' name='Submit' value='Elküld' />
</div>

</fieldset>
</form>

<!-- Itt jön a validálás -->
<script type='text/javascript'>
// <![CDATA[
    
    var frmvalidator  = new Validator("register");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("teljesnev","req","Kérlek add meg a neved");
    frmvalidator.addValidation("email","req","Kérlek add meg az email címed");
    frmvalidator.addValidation("email","email","Kérlek érvényes email címet adj meg");
    frmvalidator.addValidation("username","req","Kérlek add meg a felhasználónevedet");
    frmvalidator.addValidation("jelszo","req","Kérlek adj meg egy jelszót");

// ]]>
</script>

<div>
	Szörnyes Társas &copy; 2013
</div>

</body>
</html>