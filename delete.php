<?php
require_once("includes/functions.inc.php");

if(isset($_POST["delete_contact_id"]))
{
    $delete_id = $_POST["delete_contact_id"];
    $query = "DELETE FROM contacts WHERE id=$delete_id";
    $result = db_query($query);
    if($result)
    {
        redirect("index.php?q=success&op=insert");
    }
    else
    {
        redirect("index.php?q=error&op=insert");
    }
}
?>