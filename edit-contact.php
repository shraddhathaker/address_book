<?php
require_once("includes/functions.inc.php");
$error_flag = false;

if(isset($_GET["id"]))
{
    $id = $_GET["id"];
    $rows = db_select("SELECT *FROM contacts WHERE id = $id");
    if(empty($rows))
    {
        redirect("404.html");
    }
}
else
{
    redirect("404.html");
}

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
    $has_to_update_image = false;
    
    if($_FILES["pic"]["name"] !== "")
    {
        $file_name = $_FILES["pic"]["name"];
        $temp_file_path = $_FILES["pic"]["tmp_name"];
        
        $temp = explode(".", $file_name);
        $extension = end($temp);
        
        $image_name = "$image_name.$extension";
        
        move_uploaded_file($temp_file_path, "images/users/$image_name");
    }
    
    /**
     * If the user has updated the contact name i.e. first_name or/and last_name then we have 2 conditions:
     * Either the photo has been changed or the photo hasn't been changed!
     * If the photo was changed, then the above code would already created a new file with new name, so OUR TASK IS TO JUST DELETE OLD PHOTO!
     * Otherwise, if only the name was changed then OUR TASK IS TO RENAME TH OLD FILE!
     */
    $old_image_name = $rows[0]["image_name"];
    if($has_to_update_image)
    {
        $temp_array = explode(".", $old_image_name);
        $extension = end($temp_array);
        $image_name = "$image_name.$extension";
    }
    
    if($old_image_name === $image_name)
    {
        //no issue and no image name has to be updated in the database!   
    }
    else
    {
        //Now two paths either rename or delete
        $path = "images/users/";
        if($has_to_update_image)
        {
            unlink($path.$old_image_name);
        }
        else
        {
            $old_file_path = $path.$old_image_name;
            $new_file_path = $path.$image_name;
            rename($old_image_name, $new_file_path);
        }
    }
    
    /**
     * PHP Methods:
     * rename($old_file_path, $new_file_path)
     * unlink($resource_path_to_be_deleted)
     */
    
    /**
     * END OF FILE UPLOADS
     */
    
    /**
     * Updating Data to DB
     */
    $first_name = add_single_quotes($first_name);
    $last_name = add_single_quotes($last_name);
    $email = add_single_quotes($email);
    $birthdate = add_single_quotes($birthdate);
    $telephone = add_single_quotes($telephone);
    $address = add_single_quotes($address);
    $image_name = add_single_quotes($image_name);
    
    $query = "UPDATE contacts SET first_name=$first_name, last_name=$last_name, email=$email, birthdate=$birthdate, telephone=$telephone, address=$address, image_name=$image_name WHERE id=$id";
//    dd($query);
    $result = db_query($query);
//    dd($result);
    if($result)
    {
        redirect("index.php?q=success&op=update"); //This is the function in functions.inc.php
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

    <title>Edit Contact</title>
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
            <h2>Edit Contact</h2>
        </div>
<?php
if($error_flag):
?>
        <div class="row">
            
            <div class="materialert error">
                <div class="material-icons">error_outline</div>
                There are some issues while inserting data! Please retry!
                <button type="button" class="close-alert">×</button>
            </div>
        </div>
<?php
endif;
?>
        <div class="row">
            <form class="col s12 formValidate" action="<?= $_SERVER["PHP_SELF"]."?id=".$rows[0]['id']; ?>" id="edit-contact-form" method="POST" enctype="multipart/form-data">
               <input name="id" id="id" type="hidden" value="<?= $rows[0]['id']; ?>">
                <div class="row mb10">
                    <div class="input-field col s6">
                        <input id="first_name" name="first_name" type="text" class="validate" data-error=".first_name_error" value="<?= $rows[0]['first_name'];?>">
                        <label for="first_name">First Name</label>
                        <div class="first_name_error "></div>
                    </div>
                    <div class="input-field col s6">
                        <input id="last_name" name="last_name" type="text" class="validate" data-error=".last_name_error" value="<?= $rows[0]['last_name'];?>">
                        <label for="last_name">Last Name</label>
                        <div class="last_name_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s6">
                        <input id="email" name="email" type="email" class="validate" data-error=".email_error" value="<?= $rows[0]['email'];?>">
                        <label for="email">Email</label>
                        <div class="email_error "></div>
                    </div>
                    <div class="input-field col s6">
                        <input id="birthdate" name="birthdate" type="text" class="datepicker" data-error=".birthday_error" value="<?= $rows[0]['birthdate'];?>">
                        <label for="birthdate">Birthdate</label>
                        <div class="birthday_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s12">
                        <input id="telephone" name="telephone" type="tel" class="validate" data-error=".telephone_error" value="<?= $rows[0]['telephone'];?>">
                        <label for="telephone">Telephone</label>
                        <div class="telephone_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s12">
                        <textarea id="address" name="address" class="materialize-textarea" data-error=".address_error"><?= $rows[0]['address'];?></textarea>
                        <label for="address">Address</label>
                        <div class="address_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                   <div class="col s2">
                       <img id="temp_img" src="images/users/<?= $rows[0]['image_name'];?>" alt="">
                    </div>
                    <div class="file-field input-field col s10">
                        <div class="btn">
                            <span>Image</span>
                            <input type="file" name="pic" id="pic" data-error=".pic_error">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload Your Image" value="<?= $rows[0]['image_name'];?>">
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
    <script src="js/pages/edit-contact.js"></script>
    <!--Custom JS-->
    <script src="js/custom.js" type="text/javascript"></script>
</body>

</html>