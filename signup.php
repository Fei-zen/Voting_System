<?php include 'includes/header.php'; ?>
<body class="Background-color hold-transition login-page">
<div class="login-box">
    <div>
        <div class="login-box-body">
            <div class="login-logo">
                <b>Create Account</b>
            </div>
            <p class="login-box-msg">Please put your credentials here!</p>

            <form action="signup_process.php" method="POST">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="name" placeholder="First Name" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="lastname" placeholder="Lastname" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="User Name" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" name="signup"><i class="fa fa-sign-in"></i> Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/scripts.php' ?>
</body>



