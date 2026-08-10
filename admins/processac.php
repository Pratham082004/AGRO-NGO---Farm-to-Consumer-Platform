<?php
if(isset($_POST['save']) && !empty($_POST['bal']))
{	 
    $bal = $_POST['bal'];

    $mysqli = new mysqli("127.0.0.1", "root", "", "tms2"); 
    if($mysqli === false){ 
        die("ERROR: Could not connect. " . $mysqli->connect_error); 
    } 
    
    // Clear old emails so the ML script only emails the newly updated address
    $mysqli->query("DELETE FROM churn");
    
    // Insert new email
    $sql = "INSERT INTO churn (comm, churn, id) VALUES ('$bal', '[ True]', 1)";
    $mysqli->query($sql);
    $mysqli->close();
}

header("Location: manage-users.php");
exit;
?>
