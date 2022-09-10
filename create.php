<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $type = $value = "";
$name_err = $type_err = $value_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $input_name = trim($_POST["name"]);
    
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_type = trim($_POST["type"]);
    if (empty($input_type)) {
        $type_err = "Please enter a type.";     
    } else {
        $type = $input_type;
    }
    
    // Validate salary
    $input_value = trim($_POST["value"]);
    if (empty($input_value)) {
        $value_err = "Please enter the value.";     
    } elseif (!ctype_digit($input_value)) {
        $value_err = "Please enter a positive integer value.";
    } else {
        $value = $input_value;
    }
    
    // Check input errors before inserting in database
    if (empty($name_err) && empty($type_err) && empty($value_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO discount (name, isPercentage, value, isActive) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "siii", $param_name, $param_type, $param_value, $param_active);
            
            // Set parameters
            $param_name = $name;
            $param_type = $type === "Percentage";
            $param_value = $value;
            $param_active = 1;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: admin_index.php");
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
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add discount record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" name="type" class="form-control <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>"><?php echo $type; ?></input>
                            <span class="invalid-feedback"><?php echo $type_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Value</label>
                            <input type="text" name="value" class="form-control <?php echo (!empty($value_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $value; ?>">
                            <span class="invalid-feedback"><?php echo $value_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>