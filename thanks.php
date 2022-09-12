
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
    </style>
</head>
<body>
    <?php
    session_start();
    echo "<script type='text/javascript'>",
            "deleteCart('" . session_id() . "');",
        "</script>";
        session_regenerate_id(true);
    ?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center mt-5 mb-3">
                    <h2>Items purchased Successfully</h2>
                    <div class="clearfix mt-5">
                        <a href="logout.php" class="btn btn-success">Logout</a>
                        <a href="customer_index.php" class="btn btn-success">Keep Shopping</a>
                    </div>
                </div><!-- col-md-12 -->
            </div><!-- row -->
        </div><!-- container-fluid -->
    </div><!-- wrapper -->
</body>