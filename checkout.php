<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="js/cart.js"></script>

    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
        img {
            float: left;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Checkout Details</h2>
                    </div><!-- clearfix -->
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    $sql = "SELECT c.user_id, p.id, p.name, p.price from cart c, product p where p.id = c.product_id and c.user_id = '" . $_SESSION["uid"] . "';";
                    
                    $sql1 = "SELECT d.id, d.name, d.isPercentage, d.value, pd.product_id from discount d, product_discount pd where d.id = pd.discount_id AND d.isActive = 1";

                    $data = mysqli_query($link, $sql1);

                    $discounts = $data->fetch_all(MYSQLI_ASSOC);

                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {

                            $products = $result->fetch_all(MYSQLI_ASSOC);
                            
                            foreach ($products as $row) {
                                echo "<div class='card mb-3'>";
                                    echo "<div class='card-block'>";
                                        echo "<img src='static/2' height='200' width='200' >";
                                        echo "<h3 class=''>" . $row['name'] . "</h3>";
                                        foreach ($discounts as $discount) {
                                            echo "<div class='form-check'>";
                                                echo "<input onClick = applyDiscount(this) class='form-check-input' type='checkbox' value='" . $row['id'] . "_" . $discount['id'] . "' id='" . $discount['id'] . "'>";
                                                echo "<label class='form-check-label' for='" . $discount['id']. "'>";
                                                echo $discount['name'];
                                                echo "</label>";
                                            echo "</div>";
                                        }
                                        
                                        echo "<p>Price: $" . $row['price'] . "</p>";  
                                        echo "<p>Discounted Price: $<span id='p_" . $row['id'] . "'>" . $row['price'] . "</span></p>";    
                                        
                                    echo "</div>";
                                echo "</div>";
                            }

                            echo "<p>Total: $ <span id='total'></span></p>";

                            echo "<script type='text/javascript'>",
                                    "saveProductInfo(" . json_encode($products) . "," . json_encode($discounts) . ");",
                                "</script>";

                        } else {
                            echo '<div class="alert alert-danger"><em>Please add products to checkout.</em></div>';
                        }
                    }
                    ?>
                </div><!-- col-md-12  -->
            </div><!-- row -->
        </div><!-- container-fluid -->
    </div><!-- wrapper -->