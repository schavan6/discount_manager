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
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child {
            width: 120px;
        }
        img {
            float: left;
        }
    </style>
    <script>
        $(document).ready(function(){
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
                        <h2 class="pull-left">Our Newest Cereals</h2>
                        <button  onClick='checkout()' name ='checkout' type='button' class='pull-right btn btn-success'><i class="fa fa-shopping-cart"></i> Checkout</button>
                    </div><!-- clearfix -->
                    <?php
                    // Include config file
                    require_once "config.php";
                    $sql = "SELECT * FROM product";
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_array($result)) {
                                echo "<div class='card mb-3'>";
                                    echo "<div class='card-block'>";
                                        echo "<img src='static/2' height='300' width='300' >";
                                        echo "<h3>" . $row['name'] . "</h3>";
                                        echo "<p>" . $row['description'] . "</p>";
                                        echo "<p>Price: $" . $row['price'] . "</p>";
                                        echo "<button  onClick='addToCart(" . json_encode($row) . ")' name ='add-to-cart' type='button' class='add-to-cart btn btn-success'>Add to Cart</button>";
                                    echo "</div>";
                                echo "</div>";
                            }

                        } else {
                            echo '<div class="alert alert-danger"><em>Nothing in the store!</em></div>';
                        }
                    }
                    else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    // Close connection
                    mysqli_close($link);

                    ?>

                </div><!-- col-md-12 -->
            </div><!-- row -->
        </div><!-- container-fluid -->
    </div><!-- wrapper -->
</body>