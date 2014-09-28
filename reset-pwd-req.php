<?PHP
ob_start();
require_once("./include/altalanos.php");
require_once 'db.php';
ob_end_clean();
$db = new AdatbazisKezelo();

$db->SetWebsiteName('http://susu.jgypk.hu/~csjlacf/');
$db->KapcsolatKeszites();
$db->SetDataTable("felhasznalok");

$emailsent = false;
if(isset($_POST['submitted']))
{
   if(altalanos::EmailResetPasswordLink())
   {
        altalanos::RedirectToURL("reset-pwd-link-sent.php");
        exit;
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-HU" lang="hu-HU">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Jelszó módosító lap</title>
      <link rel="STYLESHEET" type="text/css" href="style/keretrendszer.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>


<form id='resetreq' action='reset-pwd-req.php' method='post' accept-charset='UTF-8'>
<fieldset style='width: 15em; background-color: #F2EDDE;'>
<legend>Jelszó módosítás</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<div>* kötelező mezők</div>

<div><span style="color: orange;"><?php echo $db->GetErrorMessage(); ?></span></div>
<div>
    <label for='email' >Email címed*:</label><br/>
    <input type='text' name='email' id='email' value='<?php echo altalanos::SafeDisplay('email') ?>' maxlength="50" /><br/>
    <span id='resetreq_email_errorloc' class='error'></span>
</div>
<div style="color: orange;">Egy linket fogunk küldeni a póstafiókodba. Kattints rá a jelszómódosítás véglegesítéséhez</div>
<div>
    <input type='submit' name='Submit' value='Módosít' />
</div>

</fieldset>
</form>
<!-- kliens oldali validáció:
Innen vettem: JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[

    var frmvalidator  = new Validator("resetreq");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("email","req","Kérlek add meg a bejelentkezéshez használt email címedet");
    frmvalidator.addValidation("email","email","Kérlek add meg a bejelentkezéshez használt email címedet");

// ]]>
</script>

<div>
	Szörnyes Társas &copy; 2013
</div>
</body>
</html>