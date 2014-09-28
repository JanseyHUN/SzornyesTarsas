<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>ez mos mér</title>
    </head>
    <body>
         <?php
        // put your code here
        ?>
        <form method="post" action="feldolgozo2.php">
        A szörny szintje:
            <input type="radio" name="szint" value="1" checked="checked" />
            <input type="radio" name="szint" value="2" />
            <input type="radio" name="szint" value="3" />
            <input type="radio" name="szint" value="4" />
            <input type="radio" name="szint" value="5" /> 
            <br />
        Aranymososító bónusz:
            <input type="text" name="bonusz" value="1" />
            <br />
           
            <input type="submit" name="Számol">
            <br />
        </form>
        <?php
 
    
    ?>
    </body>
</html>
