<?php
require_once 'model/Class/User.php';
session_start();
?>
<!DOCTYPE html>
<html>
    <head lang=en>				
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Index - PanzerTest</title>

        <!-- CSS -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="/WebBristol/bootstrap/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="/WebBristol/bootstrap/css/bootstrap-theme.min.css">
        <!-- Custom css -->
        <link rel="stylesheet" type="text/css" href="/WebBristol/css/portfolio.css">

        <!-- FONT -->
        <link href='http://fonts.googleapis.com/css?family=Rock+Salt' rel='stylesheet' type='text/css'>

        <!-- JAVASCRIPT -->
        <!-- JQuery -->
        <script src="/WebBristol/js/jquery-1.11.3.min.js"></script>
        <script src="/WebBristol/js/jquery.validate.min.js"></script>  
        <!-- Latest compiled and minified JavaScript -->		
        <script src="/WebBristol/bootstrap/js/bootstrap.min.js"></script>	
    </head>
    <body>
        <?php
//        if (!isset($_SESSION["user"]))
//        {
//            header('Location: login');
//        }
        ?>
        <div class="page-header col-sm-offset-2 col-sm-8">
            <h1 class="white chalk"><em>ABC University</em></h1>
        </div>
        <?php
        // If we have a message
        if (isset($_SESSION['message'])):
            foreach ($_SESSION['message'] as $type => $message):
        ?>
                <div class="alert alert-<?php echo $type; ?> alert-dismissible col-sm-offset-2 col-sm-8" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $message; ?>
                </div>
        <?php
            endforeach;
        endif;
        // We're destroying the message so it is not displayed twice.
        unset($_SESSION['message']);
//        if (isset($_GET["page"]) && isset($_GET["action"]) && file_exists('controller/' . $_GET["page"] . '/' . $_GET["page"] . '_' . $_GET["action"] . '.php'))
//        {
//            require_once('controller/' . $_GET["page"] . '/' . $_GET["page"] . '_' . $_GET["action"] . '.php');
//        }
//        else if (isset($_GET["page"]) && file_exists('controller/' . $_GET["page"] . '.php'))
//        {
//            require_once('controller/' . $_GET["page"] . '.php');
//        }
//        else
//        {
//            header('Location: home');
//        }        
        ?>
        <a href="controller/page/testDB.php">testDB</a>
    </body>
</html>