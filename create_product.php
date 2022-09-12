<?php
// Include config file
require_once "config.php";


$sql2 = "SELECT * FROM discount";

$all_discount_data = mysqli_query($link, $sql2);

$all_discounts = $all_discount_data->fetch_all(MYSQLI_ASSOC);
 
// Define variables and initialize with empty values
$name = $description = $price = "";
$name_err = $description_err = $price_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    if (empty($name_err) && empty($type_err) && empty($value_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO product (name, description, price) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_name, $param_description, $param_price);
            
            // Set parameters
            $param_name = $name;
            $param_description = $description;
            $param_price = $price;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $discount_checked = $_POST['discount_checked'];

                foreach ($discount_checked as $discount_checked){ 
                    $sql4 = "INSERT INTO `product_discount`
                    (`product_id`, `discount_id`, `count`) 
                    VALUES ($stmt->insert_id, $discount_checked, 1)";
                    if(!mysqli_query($link,$sql4)){
                        echo "error updating discounts!";
                        exit();
                    }
                }
                // Records created successfully. Redirect to landing page
                header("location: products.php");
                exit();
            } else{
                $error = mysqli_stmt_error($stmt);
                print("Error : ".$error);
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
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
                                echo "<div>";
                                    echo "<input name ='discount_checked[]' type='checkbox' value='" . $discount['id'] . "' id='" . $discount['id'] . "'>";
                                    echo "<label class='form-check-label ml-2' for='" . $discount['id']. "'>";
                                    echo $discount['name'];
                                    echo "</label>";
                                echo "</div>";
                        }?>

                        <input type="submit" class="btn btn-primary mt-3" value="Submit">
                        <a href="products.php" class="btn btn-secondary mt-3 ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>