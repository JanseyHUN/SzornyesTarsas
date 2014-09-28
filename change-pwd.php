<?PHP
header("Content-Type: text/html; charset=UTF-8");
ob_start();
require_once("./include/altalanos.php");
require_once 'db.php';
ob_end_clean();
$db = new AdatbazisKezelo();
//$fgkeretrendszer->SetWebsiteName('http://susu.jgypk.hu/~csjlacf/');
$db->KapcsolatKeszites();
$db->SetDataTable("felhasznalok");
/*$fgkeretrendszer->SetRandomKey('qSRcVS6DrTzrPvr');*/

 if(!altalanos::CheckLogin())
{
    altalanos::RedirectToURL("login.php");
    exit;
}

if(isset($_POST['submitted']))
{
   if(altalanos::ChangePassword())
   {
        altalanos::RedirectToURL("changed-pwd.php");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-HU" lang="hu-HU">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Jelszó váltás</title>
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>

<form id='changepwd' action='change-pwd.php' method='post' accept-charset='UTF-8'>
  <fieldset style='width: 15em; background-color: #F2EDDE;'>
    <legend>Jelszó váltás</legend>
    
    <input type='hidden' name='submitted' id='submitted' value='1'/>
    
      <div>* kötelező mezők</div>
    
      <div><span style="color: orange;"><?php echo $db->GetErrorMessage(); ?></span></div>
      <div>
          <label for='oldpwd' >Régi Jelszó*:</label><br/>
          <input type='password' name='oldpwd' id='oldpwd' maxlength="50" />
          <span id='changepwd_oldpwd_errorloc' style="color: orange;"></span>
      </div>
      
      <div>
          <label for='newpwd' >Új Jelszó*:</label><br/>
          <input type='password' name='newpwd' id='newpwd' maxlength="50" /><br/>
          <span id='changepwd_newpwd_errorloc' style="color: orange;"></span>
      </div>
    
        <br/>
        <br/>
        <br/>
    
      <div>
          <input type='submit' name='Submit' value='Megváltoztat' />
      </div>
    <p><a href='loginhome.php'>Kezdőlap</a></p>
  </fieldset>
</form>
<script type='text/javascript'>
// <![CDATA[
    
    var frmvalidator  = new Validator("changepwd");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("oldpwd","req","Kérlek add meg a régi jelszót");
    
    frmvalidator.addValidation("newpwd","req","Kérlek add meg az új jelszót");

// ]]>
</script>

<div>
	Szörnyes Társas &copy; 2013
</div>

</body>
</html>