<?php
session_start();
require_once "config.php";

if (isset($_POST["addToCart"])) {
    
    $user_id = session_id();
    $p_id = $_POST["proId"];

    $sql = "SELECT * FROM cart WHERE product_id = " . $p_id  . " AND user_id = '$user_id'";
    $result = mysqli_query($link, $sql);
    $count = mysqli_num_rows($result);
    if($count > 0) {
        echo "Product already added!";
    }
    else {

        $sql = "INSERT INTO `cart`
			(`product_id`, `user_id`, `count`) 
			VALUES ($p_id,'$user_id',1)";
        if(mysqli_query($link,$sql)){
            echo "Product added successfully!";
        }

    }

    

}

if (isset($_POST["logout"])) {
    
    $user_id = $_POST["userId"];

    $sql = "DELETE FROM cart WHERE user_id = '$user_id'";
    $result = mysqli_query($link, $sql);
    if(!$result) {
        echo "error deleting cart items!";
    }

}

?>