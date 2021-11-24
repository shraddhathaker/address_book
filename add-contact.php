<?php
require_once("includes/functions.inc.php");
$error_flag = false;
if(isset($_POST["submit"]))
{
    $first_name = db_quote($_POST["first_name"]);
    $last_name = db_quote($_POST["last_name"]);
    $email = db_quote($_POST["email"]);
    
    $birthdate = db_quote($_POST["birthdate"]);
    $birthdate = date("Y-m-d", strtotime($birthdate));    
    
    $telephone = db_quote($_POST["telephone"]);
    $address = db_quote($_POST["address"]);
    
    /**
     * HANDLING THE IMAGE UPLOAD
     */
    $image_name = strtolower($first_name . "-" . $last_name);
    
    if(isset($_FILES["pic"]["name"]))
    {
        $file_name = $_FILES["pic"]["name"];
        $temp_file_path = $_FILES["pic"]["tmp_name"];
        
        $temp = explode(".", $file_name);
        $extension = end($temp);
        
        $image_name = "$image_name.$extension";
        
        move_uploaded_file($temp_file_path, "images/users/$image_name");
    }
    
    /**
     * END OF FILE UPLOADS
     */
    
    /**
     * Saving Data to DB
     */
    $first_name = add_single_quotes($first_name);
    $last_name = add_single_quotes($last_name);
    $email = add_single_quotes($email);
    $birthdate = add_single_quotes($birthdate);
    $telephone = add_single_quotes($telephone);
    $address = add_single_quotes($address);
    $image_name = add_single_quotes($image_name);
    
    $query = "INSERT INTO contacts(first_name, last_name, email, birthdate, telephone, address, image_name) VALUES($first_name, $last_name, $email, $birthdate, $telephone, $address, $image_name)";
//    dd($query);
    $result = db_query($query);
//    dd($result);
    if($result)
    {
        redirect("index.php?q=success&op=insert"); //This is the function in functions.inc.php
    }
    else
    {
        $error_flag = true;   
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />

    <!--Import Csutom CSS-->
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Add Contact</title>
</head>

<body>
    <!--NAVIGATION BAR-->
    <nav>
        <div class="nav-wrapper">
            <!-- Dropdown Structure -->
            <ul id="dropdown1" class="dropdown-content">
                <li><a href="#!">Profile</a></li>
                <li><a href="#!">Signout</a></li>
            </ul>
            <nav>
                <div class="nav-wrapper">
                    <a href="#!" class="brand-logo center">Contact Info</a>
                    <ul class="right hide-on-med-and-down">

                        <!-- Dropdown Trigger -->
                        <li><a class="dropdown-trigger" href="#!" data-target="dropdown1"><i
                                    class="material-icons right">more_vert</i></a></li>
                    </ul>
                </div>
            </nav>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
    </nav>
    <!--/NAVIGATION BAR-->
    <div class="container">
        <div class="row mt50">
            <h2>Add New Contact</h2>
        </div>
<?php
if($error_flag):
?>
        <div class="row">
            <div class="materialert error">
                <div class="material-icons">error_outline</div>There are some issues while inserting data! Please retry!
                <button type="button" class="close-alert">×</button>
            </div>
        </div>
<?php
endif;
?>
        <div class="row">
            <form class="col s12 formValidate" action="<?=$_SERVER["PHP_SELF"];?>" id="add-contact-form" method="POST" enctype="multipart/form-data">
                <div class="row mb10"> 
                    <div class="input-field col s6">
                        <input id="first_name" name="first_name" type="text" class="validate" data-error=".first_name_error">
                        <label for="first_name">First Name</label>
                        <div class="first_name_error "></div>
                    </div>
                    <div class="input-field col s6">
                        <input id="last_name" name="last_name" type="text" class="validate" data-error=".last_name_error">
                        <label for="last_name">Last Name</label>
                        <div class="last_name_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s6">
                        <input id="email" name="email" type="email" class="validate" data-error=".email_error">
                        <label for="email">Email</label>
                        <div class="email_error "></div>
                    </div>
                    <div class="input-field col s6">
                        <input id="birthdate" name="birthdate" type="text" class="datepicker" data-error=".birthday_error">
                        <label for="birthdate">Birthdate</label>
                        <div class="birthday_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s12">
                        <input id="telephone" name="telephone" type="tel" class="validate" data-error=".telephone_error">
                        <label for="telephone">Telephone</label>
                        <div class="telephone_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s12">
                        <textarea id="address" name="address" class="materialize-textarea" data-error=".address_error"></textarea>
                        <label for="address">Addess</label>
                        <div class="address_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="file-field input-field col s12">
                        <div class="btn">
                            <span>Image</span>
                            <input type="file" name="pic" id="pic" data-error=".pic_error">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload Your Image">
                        </div>
                        <div class="pic_error "></div>
                    </div>
                </div>
                <button class="btn waves-effect waves-light right" type="submit" name="submit">Submit
                        <i class="material-icons right">send</i>
                    </button>
            </form>
        </div>  
    </div>
    <footer class="page-footer p0">
        <div class="footer-copyright ">
            <div class="container">
                <p class="center-align">© 2020 Study Link Classes</p>
            </div>
        </div>
    </footer>
    <!--JQuery Library-->
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <!--JQuery Validation Plugin-->
    <script src="vendors/jquery-validation/validation.min.js" type="text/javascript"></script>
    <script src="vendors/jquery-validation/additional-methods.min.js" type="text/javascript"></script>
    <!--Include Page Level Scripts-->
    <script src="js/pages/add-contact.js"></script>
    <!--Custom JS-->
    <script src="js/custom.js" type="text/javascript"></script>
</body>

</html>