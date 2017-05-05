<?php
$title="Login";
session_start(); //very important. every part of the php file need geh

//this is to check if user has login dy or not
if(isset($_SESSION['user'])){
    header('Location: index.php');
}


//check if is a post type. bcause when submit a form, it is a post request.
//in form method is post, so wanna check if is a post.
//form action is '', so means when submit, it will refresh this page with the php code, then check if the refresh is a get or post
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    //$email is just to store nia, nothing much
    //$_POST['email'] is when submit the form, it post, and check the info u wanna post
    //'email' is the name of input u wanna post.
    $email = $_POST['email'];
    $password = $_POST['password'];

    //every time wanna make connection, must do this 3 line
    require_once dirname(__FILE__)."/include/DbConnect.php";
    $db = new DbConnect();
    $conn = $db->connect();

    //write ur query
    $query = "SELECT Email from users WHERE Email = '$email' and Password = '$password'";

    //this one is to execute select statement nia, there's other method for insert, update and delete
    $result = mysqli_query($conn,$query);


    //check if result is 0 or not
    //result in select returns the object
    if(mysqli_num_rows($result) >0){

        //keep result into row
       // $row = mysqli_fetch_assoc($result);


        //we use session to make system remember this user is login liao
        $_SESSION['user'] = $email;

        //if resgiered/logim, user will be redirect to index.php
        header('Location: index.php');


       // echo $row['Email'];
    }
    else{
        $noUser = '<div class="alert alert-danger">
        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        	<strong>Oops!</strong> Wrong Email or Password.
        </div>';
    }


}

include dirname(__FILE__).'/include/header.php';
?>

    <div class="page-header">
        <h1>Login</h1>
    </div>
    <?php if(isset($noUser)){ echo $noUser;}?>
    <form action="login.php" method="post" role="form">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Please Enter Your Email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Pleae Enter Your Password">
                </div>
            </div>

        </div>


        <button type="submit" class="btn btn-primary">Log In</button>
    </form>

<?php
include dirname(__FILE__).'/include/footer.php';
?>