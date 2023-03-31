<?php include('server/logUser.php') ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>CUGI  &amp; Mama | Reset Password</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="./assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="assets/css/main.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <link href="./assets/css/pages/auth-light.css" rel="stylesheet" />
</head>

<body class="bg-silver-300">
    <div class="content">
        <div class="brand">
            <a class="link" href="index.php">CUGI Mama</a>
        </div>
        <form id="reset-form" action="" method="post">
            <h3 class="m-t-10 m-b-10">Reset password</h3>
            <?php 
                if(isset($error)){
                        foreach($error as $error){
                            echo '<div class="errmsg">'.$error.'</div><br>';
                        }
                    }
                    if(isset($success)){
                    echo $success;
                }
                            ?>
                <?php if(!isset($hide)){ ?>
            <div class="form-group">
                <input class="form-control" id="password" type="password" name="password_1" placeholder="New Password">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password_2" placeholder="Confirm Password">
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block" name="submit_reset" type="submit">Submit</button>
            </div>
            <?php } ?>
        </form>
    </div>
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS -->
    <script src="./assets/vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/popper.js/dist/umd/popper.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS -->
    <script src="./assets/vendors/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
    <!-- CORE SCRIPTS-->
    <script src="assets/js/app.js" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    <script type="text/javascript">
        $(function() {
            $('#reset-form').validate({
                errorClass: "help-block",
                rules: {
                    password: {
                        required: true,
                        password: true
                    },
                    password_2: {
                        required: true,
                        password: true
                    },
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
            });
        });
    </script>
</body>

</html>
