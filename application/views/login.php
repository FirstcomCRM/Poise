<!DOCTYPE html>
<html lang="en">
<head>
    <?php $b_url = base_url(); ?>
    <title>POISE</title>
    <link rel="shortcut icon" href="<?= base_url(); ?>images/favicon.png" />
    <!-- Bootstrap core CSS -->
    <link href="<?= base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Quattrocento+Sans' rel='stylesheet' type='text/css'>
    <!-- Add custom CSS here -->
    <link href="<?= base_url(); ?>font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>css/animate.css" rel="stylesheet">
    <!-- JavaScript -->
    <script src="<?= base_url(); ?>js/jquery-1.11.1.js"></script>
    <script src="<?= base_url(); ?>js/bootstrap.min.js"></script>
    <style type = "text/css">
        body {
            font-family: 'Quattrocento Sans', 'Arial', sans-serif;
            overflow-x: hidden;
            /*background: url('../mbdesign/images/CRM_Login.png');*/
            color: #333333;
            -webkit-font-smoothing: antialiased;
            font-weight: 300;
            position: relative;
        }
		
		.login-border {
			
			background: rgba(160,160,160,0.5);
			width: 560px !important;
			padding-right: 15px;
			padding-left: 15px;
			margin-right: auto;
			margin-left: auto;
			height: 37em;
			border-radius: 22px;
			padding-top: 15px;
			padding-bottom: 15px;
			/* margin-bottom: 5em; */
			margin-top: 4em;
		}
        
		
		.login-page > .login-container {
            width: 360px !important!important;
        }

		.credentials{
			
			background-color:rgba(160,160,160,0.5);
			
		}
		::-webkit-input-placeholder { /* Chrome */
		  color: black!important;
		}
		
        #logo {
            padding-top : 10px;
            /*width : 300px;*/
        }

        .login-page .login-panel {
            margin-top: 100px;
        }

        .panel.plain.panel-default .panel-heading {
            background-color: rgb(255,255,255);
            /*background-color: rgba(160,160,160,0.8);*/
            color: #333333;
        }
		
		
		.panel.plain.panel-default .panel-body {
          /*  background-color: rgba(160,160,160,0.8);*/
			background-color: rgb(255,255,255);
            color: #333333;
        }

        .panel.plain .panel-heading {
            border-bottom: none;
        }

        span {
            background-color: #FAFAFA !important;
        }

        .btn-primary.btn-alt:hover, .btn-primary.btn-alt:focus, 
        .btn-primary.btn-alt:active, .btn-primary.btn-alt.active, 
        .open .dropdown-toggle.btn-primary.btn-alt {
            /*color: #0bacd3;*/
            background-color: rgba(0, 0, 0, 0);
            /*border-color: #087d99;*/
            border-color: #fff;
            border-width: 2px;
        }

        .btn-primary.btn-alt {
			box-shadow: none;
			color: #282464;
			background-color: transparent;
			border-color: #2f3a97;
			border-width: 2px;
		}

        .ico-unlock {
            margin-right : 5px;
        }

        .sign-in-text{
            margin-top:-5px;
            margin-bottom: 15px;
            color: #fff;
            font-weight: 500;
            font-size: 16px;
        }

        @media (max-width: 640px) {
            .login-page .login-panel, .error-page .error-panel {
                margin-top: 50px;
            }
        }

		
		.input-group-addon {
			background-color:rgba(160,160,160,0.5)!important;
			
		}
		
        .form-control:focus {
            border-color: rgba(126, 239, 104, 0.8);
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6);
            outline: 0 none;
        }

        /* textarea:focus,
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="datetime"]:focus,
        input[type="datetime-local"]:focus,
        input[type="date"]:focus,
        input[type="month"]:focus,
        input[type="time"]:focus,
        input[type="week"]:focus,
        input[type="number"]:focus,
        input[type="email"]:focus,
        input[type="url"]:focus,
        input[type="search"]:focus,
        input[type="tel"]:focus,
        input[type="color"]:focus,
        .uneditable-input:focus {   
            border-color: rgba(126, 239, 104, 0.8);
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6);
            outline: 0 none;
        }*/

    </style>
    <script>
        $(document).keypress(function(e) {
            if(e.which == 13) {
                if($('#username').val() == '' && $('#password').val() == '') {
                    $('#username').parent().parent().addClass('has-error');
                    $('#password').parent().parent().addClass('has-error');
                    $(".alert-area").empty().append("<a href='#' class='close' data-dismiss='alert'>&times;</a>Please Enter Username & Password").show();
                }
                else if($('#username').val() == '') {
                    $('#password').parent().parent().removeClass('has-error');
                    $('#username').parent().parent().addClass('has-error');
                    $(".alert-area").empty().append("<a href='#' class='close' data-dismiss='alert'>&times;</a>Please Enter Username").show();
                }
                else if ($('#password').val() == '') {
                    $('#username').parent().parent().removeClass('has-error');
                    $('#password').parent().parent().addClass('has-error');
                    $(".alert-area").empty().append("<a href='#' class='close' data-dismiss='alert'>&times;</a>Please Enter Password").show();
                }
                else {
                    $('#login-form').submit();
                }
            }
        });

        $(function(){ 
            $('#username').focus();
            $('.alert-area').hide();

            $('#btn-submit').click(function(e) {
                e.preventDefault();
                if($('#username').val() == '' && $('#password').val() == '') {
                    $('#username').parent().parent().addClass('has-error');
                    $('#password').parent().parent().addClass('has-error');
                    $(".alert-area").empty().append("<a href='#' class='close' data-dismiss='alert'>&times;</a>Please Enter Username & Password").show();
                }
                else if($('#username').val() == '') {
                    $('#password').parent().parent().removeClass('has-error');
                    $('#username').parent().parent().addClass('has-error');
                    $(".alert-area").empty().append("<a href='#' class='close' data-dismiss='alert'>&times;</a>Please Enter Username").show();
                }
                else if ($('#password').val() == '') {
                    $('#username').parent().parent().removeClass('has-error');
                    $('#password').parent().parent().addClass('has-error');
                    $(".alert-area").empty().append("<a href='#' class='close' data-dismiss='alert'>&times;</a>Please Enter Password").show();
                }
                else {
                    $('#login-form').submit();
                }
            });
        });
    </script>
</head>

<body class="login-page">
    <div class="container login-container" style = "width:360px">
        <div class="login-panel panel panel-default plain">
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title text-center">
                    <img id="logo" src="<?= base_url(). 'images/logo_boshi_navyblue.png' ?>" alt="Dynamic logo"-->
                </h4>
            </div>
            <div class="panel-body" style="padding-top: 0px;">
                <div class="sign-in-text">Login</div>
                <?php if(validation_errors()) { ?>
                  <div class="alert alert-danger"> <?= validation_errors(); ?> </div> 
                <?php } else if(isset($invalid) && $invalid == 1) { ?>
                  <div class="alert alert-danger"><a href='#' class='close' data-dismiss='alert'>&times;</a><?= "Invalid Username or Password."; ?> </div> 
                <?php } ?>  
                <form class="form-horizontal mt0" action="<?= base_url() . 'login' ?>" id="login-form" role="form" method="post">
                    <div class="alert alert-danger alert-area"></div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <div class="input-group input-icon">
                                <span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
                                <input type="text" name="username" id="username" class="form-control valid credentials" placeholder="Enter Username ..." aria-required="true" aria-invalid="false" value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <div class="input-group input-icon">
                                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                <input type="password" name="password" id="password" class="form-control credentials" placeholder="Enter password ..." value="<?= isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb0">
                        <div class="col-lg-12 text-right">
                            <a href="#" class="btn btn-primary btn-alt mr10" id="btn-submit"><i class="fa fa-unlock ico-unlock"></i>Login</a> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>