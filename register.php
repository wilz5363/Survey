<?php
$title = "Register";
session_start();//as usual, all page must have

//this is to check if user has login dy or not
if(isset($_SESSION['user'])){
    header('Location: index.php');
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if($_POST['cPassword'] === $_POST['password']){
        //=== means same value and same type

        require_once dirname(__FILE__)."/include/DbConnect.php";
        $db = new DbConnect();
        $conn = $db->connect();

        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "insert into users(Email, Password) value('$email','$password')";
        $result = mysqli_query($conn, $query);

        //result for insert, update, delete is boolean
        if($result){
           //we use session to make system remember this user is login liao
            $_SESSION['user'] = $email;

            //if resgiered/logim, user will be redirect to index.php
            header('Location: index.php');



        }else{
            $emailError = '<div class="alert alert-danger">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            	<strong>Warning!</strong> Email has been used before. Try login instead.
            </div>';
        }


    }else{
        $pwdError = '<div class="alert alert-danger">
        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        	<strong>Warning!</strong> Password and Confirm Password are not same.
        </div>';
    }

}



include dirname(__FILE__).'/include/header.php';

if(isset($emailError)){
    echo $emailError;
}else if(isset($pwdError)){
    echo $pwdError;
}
?>


    <title>Register</title>

    <div class="Register">
        <h1>Sign Up
        </h1>
    </div>

    <form action="" method="post" role="form">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Please Enter Your Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password"
                           placeholder="Pleae Enter Your Password" required>
                </div>
                <div class="form-group">
                    <label for="cPassword">Confirm Password</label>
                    <input type="password" class="form-control" name="cPassword" id="cPassword"
                           placeholder="Pleae Retype Your Password" required>
                </div>
            </div>

        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

<?php
include dirname(__FILE__).'/include/footer.php';
?>