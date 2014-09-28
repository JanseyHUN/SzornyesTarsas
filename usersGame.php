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

if(!altalanos::CheckLogin())
{
    altalanos::RedirectToURL("login.php");
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-HU" lang="hu-HU">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Szörnyes Társas</title>
</head>
<body>

<div style='margin-left: auto; margin-right: auto; width: 65em; padding-left: 1em; padding-bottom: 3em; padding-top: 2em; background-color: #e5dbcf;'>

  <fieldset style='height: 20em; width: 15em; background-color: #F2EDDE; float: left;'>
    <legend>FőMenü</legend>
      <div>
        Bejelentkezve:<br/><?= altalanos::UserFullName(); ?>
        
        <p><a href='change-pwd.php'>Jelszó váltás</a></p>
        <p><a href='createGame.php'>Játék indítása</a></p>
        <p><a href='joinGame.php'>Kapcsolódás Játékhoz</a></p>        
        <p><a href='usersGame.php'>Felhasználók/KÉSŐBB/</a></p>
        
        <br/><br/><br/>
        <p><a href='logout.php'>Kijelentkezés</a></p>
      </div>
  </fieldset>
  
  <fieldset style='margin-left: 4em; height: 35em; width: 45em; background-color: #F2EDDE;'>
  <legend>Tartalom</legend>
    Ezen az oldalon a csatlakozott felhasználókat lehet megnézni
  </fieldset>

  <div>
	<p style="font-size: x-small;">Szörnyes Társas &copy; 2013</p>
  </div>
  
</div>  
  


</body>
</html>
