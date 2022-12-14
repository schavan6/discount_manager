<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Discount</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
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
                        <h2 class="pull-left">Discount Details</h2>
                        <a href="create_discount.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Discount</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    $sql = "SELECT * FROM discount";

                    if ($result = mysqli_query($link, $sql)) {

                        if (mysqli_num_rows($result) > 0) {

                            echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>Name</th>";
                                            echo "<th>isPercent</th>";
                                            echo "<th>Value</th>";
                                            echo "<th>Active?</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)){
                                        $type =  $row['isPercentage'] ? "Percentage" : "Dollar Off";
                                        $active = $row['isActive'] ? 'Yes' : 'No';
                                        echo "<tr>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $type . "</td>";
                                            echo "<td>" . $row['value'] . "</td>";
                                            echo "<td>" . $active . "</td>";
                                            echo "<td>";
                                                echo '<a href="update_discount.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                                echo '<a href="delete_discount.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                echo '<a href="admin_index.php" class="btn btn-danger mt-5"> <i class="fa fa-left-arrow"></i> Back</a>';
                                // Free result set
                                mysqli_free_result($result);

                        } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }

                    } else {
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