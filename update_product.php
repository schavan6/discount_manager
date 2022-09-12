<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $description = $price = "";
$name_err = $description_err = $price_err = "";
 
// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } else {
        $name = $input_name;
    }
    
    
    $input_description = trim($_POST["description"]);
    if (empty($input_description)) {
        $description_err = "Please enter a description.";     
    } else {
        $description = $input_description;
    }
    
    
    $input_price = trim($_POST["price"]);
    if (empty($input_price)) {
        $price_err = "Please enter the value.";     
    } elseif(!ctype_digit($input_price)){
        $price_err = "Please enter a positive integer value.";
    } else{
        $price = $input_price;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($description_err) && empty($price_err)){
        // Prepare an update statement
        $sql = "UPDATE product SET name =?, description=?, price=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_name, $param_description, $param_price, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_description = $description;
            $param_price = $price;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                $sql3 = "DELETE FROM product_discount WHERE product_id=" . $id;
        
                $delete_result = mysqli_query($link, $sql3);

                $discounts_checked = $_POST['discount_checked'];

                foreach ($discounts_checked as $discount_checked){ 
                    $sql4 = "INSERT INTO `product_discount`
                    (`product_id`, `discount_id`, `count`) 
                    VALUES ($id, $discount_checked, 1)";
                    if(!mysqli_query($link,$sql4)){
                        echo "error updating discounts!";
                        exit();
                    }
                }
                
                header("location: products.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }

    
        
   
    
    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM product WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $description = $row["description"];
                    $price = $row["price"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            $sql1 = "SELECT d.id, d.name, d.isPercentage, d.value, pd.product_id from discount d, product_discount pd where d.id = pd.discount_id AND d.isActive = 1 AND pd.product_id = " . $id;

            $data = mysqli_query($link, $sql1);

            $discounts_applied = $data->fetch_all(MYSQLI_ASSOC);

            $applied_discount_ids = array();

            foreach ($discounts_applied as $discount) {
                array_push($applied_discount_ids, $discount['id']);
            }

           

            $sql2 = "SELECT * FROM discount";

            $all_discount_data = mysqli_query($link, $sql2);

            $all_discounts = $all_discount_data->fetch_all(MYSQLI_ASSOC);
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the product record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>

                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" ><?php echo $description; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                            <span class="invalid-feedback"><?php echo $price_err;?></span>
                        </div>
                        <?php foreach ($all_discounts as $discount) {
                                echo "<div class='form-check'>";
                                $checkStatus = in_array($discount['id'], $applied_discount_ids) ?  "checked='checked'" : "";
                                    echo "<input name ='discount_checked[]' type='checkbox' " . $checkStatus . " value='" . $discount['id'] . "' id='" . $discount['id'] . "'>";
                                    echo "<label class='form-check-label' for='" . $discount['id']. "'>";
                                    echo $discount['name'];
                                    echo "</label>";
                                echo "</div>";
                        }?>

                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary mt-3" value="Submit">
                        <a href="products.php" class="btn btn-secondary mt-3 ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>