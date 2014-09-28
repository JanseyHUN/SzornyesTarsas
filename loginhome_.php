<?PHP
header("Content-Type: text/html; charset=UTF-8");
ob_start();
require_once("./include/altalanos.php");
require_once 'db.php';
ob_end_clean();
$altalanos = new altalanos();
$db = new AdatbazisKezelo();

$db->SetAdminEmail('csapo.janos@gmail.com');
$db->InitDB(	/*hostname*/"127.0.0.1",
				/*username*/"fejesattila",
				/*password*/"monor123",
				/*database name*/"fejesattila",
				/*table name*/"felhasznalok");
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

  <fieldset style='width: 15em; background-color: #F2EDDE;'>
    <legend>A Játék</legend>
      <div>
        Bejelentkezve:<br/><?= altalanos::UserFullName(); ?>
        
        <p><a href='change-pwd.php'>Jelszó váltás</a></p>
        <p><a href='createGame.php'>Játék indítása</a></p>
        <p><a href='joinGame.php'>Kapcsolódás Játékokhoz</a></p>        
        <p><a href='users.php'>Felhasználók/KÉSŐBB/</a></p>
        
        <br/><br/><br/>
        <p><a href='logout.php'>Kijelentkezés</a></p>
      </div>
  </fieldset>
  
  
  
  
<div>
	Szörnyes Társas &copy; 2013
</div>

</body>
</html>
