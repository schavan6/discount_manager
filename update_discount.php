<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $type = $value = "";
$name_err = $type_err = $value_err = "";
 
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
    
    
    $input_type = trim($_POST["type"]);
    if (empty($input_type)) {
        $type_err = "Please enter a type.";     
    } else {
        $type = $input_type;
    }
    
    
    $input_value = trim($_POST["value"]);
    if (empty($input_value)) {
        $value_err = "Please enter the value.";     
    } elseif (!ctype_digit($input_value)) {
        $value_err = "Please enter a positive integer value.";
    } else {
        $value = $input_value;
    }

    if (isset($_POST['isActive']) && $_POST['isActive'] == '1') {
        $isActive = 1;
    }
    else {
        $isActive = 0;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($type_err) && empty($value_err)){
        // Prepare an update statement
        $sql = "UPDATE discount SET name =?, isPercentage=?, value=?, isActive=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "siiii", $param_name, $param_type, $param_value, $param_active, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_type = $type === "Percentage";
            $param_value = $value;
            $param_active = $isActive;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: discounts.php");
                exit();
            } else{
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
        $sql = "SELECT * FROM discount WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $type = $row["isPercentage"] ? "Percentage" : "Dollars";
                    $value = $row["value"];
                    $isActive = $row['isActive'];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
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
                    <p>Please edit the input values and submit to update the discount record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>

                        
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" id="type" name="type">
                                <option <?= $type == 'Percentage' ? ' selected="selected"' : '';?> value="Percentage">Percentage</option>
                                <option <?= $type == 'Dollars' ? ' selected="selected"' : '';?> value="Dollars">Dollars</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Value</label>
                            <input type="text" name="value" class="form-control <?php echo (!empty($value_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $value; ?>">
                            <span class="invalid-feedback"><?php echo $value_err;?></span>
                        </div>

                        <div>
                            <?php $checkStatus = $isActive == 1 ?  "checked='checked'" : ""; ?>
                            <input name ='isActive' type='checkbox' <?php echo $checkStatus ?> value='1' id='isActive'>
                            <label class='form-check-label' for='isActive'>
                                Activate?
                            </label>
                        </div>
                        <br/>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="discounts.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>