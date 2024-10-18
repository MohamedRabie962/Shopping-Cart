<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../index.php");
    exit; // Always exit after redirecting
}

include("config.php"); // Ensure this file contains your PDO connection setup
extract($_SESSION);

// Fetch user details
$stmt_edit = $DB_con->prepare('SELECT * FROM users WHERE user_email = :user_email');
$stmt_edit->execute(array(':user_email' => $user_email));
$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
extract($edit_row);

// Fetch total order amount
$stmt_total = $DB_con->prepare("SELECT SUM(order_total) AS total FROM orderdetails WHERE user_id = :user_id AND order_status = 'Ordered'");
$stmt_total->execute(array(':user_id' => $user_id));
$total_row = $stmt_total->fetch(PDO::FETCH_ASSOC);
$total = $total_row['total'] ?? 0; // Use null coalescing operator to avoid undefined index

// Pagination setup
$start = 0;
$limit = 8;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast to int for security
    $start = ($id - 1) * $limit;
}

// Fetch items
$stmt_items = $DB_con->prepare("SELECT * FROM items LIMIT :start, :limit");
$stmt_items->bindValue(':start', $start, PDO::PARAM_INT);
$stmt_items->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt_items->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDGE Skateshop</title>
    <link rel="shortcut icon" href="../assets/img/logo.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="jquery.fancybox.js?v=2.1.5"></script>
    <link rel="stylesheet" type="text/css" href="jquery.fancybox.css?v=2.1.5" media="screen" />
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">EDGE Skateshop</a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li><a href="index.php"> &nbsp; <span class='glyphicon glyphicon-home'></span> Home</a></li>
                <li class="active"><a href="shop.php?id=1"> &nbsp; <span class='glyphicon glyphicon-shopping-cart'></span> Shop Now</a></li>
                <li><a href="cart_items.php"> &nbsp; <span class='fa fa-cart-plus'></span> Shopping Cart Lists</a></li>
                <li><a href="orders.php"> &nbsp; <span class='glyphicon glyphicon-list-alt'></span> My Ordered Items</a></li>
                <li><a href="view_purchased.php"> &nbsp; <span class='glyphicon glyphicon-eye-open'></span> Previous Items Ordered</a></li>
                <li><a data-toggle="modal" data-target="#setAccount"> &nbsp; <span class='fa fa-gear'></span> Account Settings</a></li>
                <li><a href="logout.php"> &nbsp; <span class='glyphicon glyphicon-off'></span> Logout</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right navbar-user">
                <li class="dropdown messages-dropdown">
                    <a href="#"><i class="fa fa-calendar"></i> <?php echo date('l, F d, Y'); ?></a>
                </li>
                <li class="dropdown user-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class='glyphicon glyphicon-shopping-cart'></span> Total Price Ordered: &#8369; <?php echo $total; ?> </b></a>
                </li>
                <li class="dropdown user-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $user_email; ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="modal" data-target="#setAccount"><i class="fa fa-gear"></i> Settings</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-power-off"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div id="page-wrapper">
        <div class="alert alert-default" style="color:white;background-color:#008CBA">
            <center><h3> <span class="glyphicon glyphicon-shopping-cart"></span> This is our skateboard stocks, Shop now!</h3></center>
        </div>

        <br />

        <?php while ($query2 = $stmt_items->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="col-sm-3">
                <div class="panel panel-default" style="border-color:#008CBA;">
                    <div class="panel-heading" style="color:white;background-color : #033c73;">
                        <center>
                            <textarea style="text-align:center;background-color: white;" class="form-control" rows="1" disabled><?php echo $query2['item_name']; ?></textarea>
                        </center>
                    </div>
                    <div class="panel-body">
                        <a class="fancybox-buttons" href="../Admin/item_images/<?php echo $query2['item_image']; ?>" data-fancybox-group="button" title="Page <?php echo $id; ?> - <?php echo $query2['item_name']; ?>">
                            <img src="../Admin/item_images/<?php echo $query2['item_image']; ?>" class="img img-thumbnail" style="width:350px;height:150px;" />
                        </a>
                        <center><h4> Price: &#8369; <?php echo $query2['item_price']; ?> </h4></center>
                        <a class="btn btn-block btn-danger" href="add_to_cart.php?cart=<?php echo $query2['item_id']; ?>"><span class="glyphicon glyphicon-shopping-cart"></span> Add to cart</a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="container">
        </div>

        <?php
        $rows = $DB_con->query("SELECT COUNT(*) FROM items")->fetchColumn();
        $total = ceil($rows / $limit);
        ?>

        <br />
        <ul class="pager">
            <?php if ($id > 1) { ?>
                <li><a style="color:white;background-color : #033c73;" href="?id=<?php echo ($id - 1); ?>">Previous Page</a></li>
            <?php } ?>
            <?php if ($id != $total) { ?>
                <li><a style="color:white;background-color : #033c73;" href="?id=<?php echo ($id + 1); ?>" class="pager">Next Page</a></li>
            <?php } ?>
        </ul>

        <center><ul class="pagination pagination-lg">
                <?php for ($i = 1; $i <= $total; $i++) { ?>
                    <?php if ($i == $id) { ?>
                        <li class="pagination active"><a style="color:white;background-color : #033c73;"><?php echo $i; ?></a></li>
                    <?php } else { ?>
                        <li><a href="?id=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php } ?>
                <?php } ?>
            </ul></center>

        <br />

        <div class="alert alert-default" style="background-color:#033c73;">
            <p style="color:white;text-align:center;">
                &copy 2016 EDGE Skateshop| All Rights Reserved |  Design by : EDGE Team
            </p>
        </div>

    </div>
</div>

<!-- Mediul Modal -->
<div class="modal fade" id="setAccount" tabindex="-1" role="dialog" aria-labelledby="myMediulModalLabel">
    <div class="modal-dialog modal-sm">
        <div style="color:white;background-color:#008CBA" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 style="color:white" class="modal-title" id="myModalLabel">Account Settings</h2>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" method="post" action="settings.php">
                    <fieldset>
                        <p>Firstname:</p>
                        <div class="form-group">
                            <input class="form-control" placeholder="Firstname" name="user_firstname" type="text" value="<?php echo $user_firstname; ?>" required>
                        </div>

                        <p>Lastname:</p>
                        <div class="form-group">
                            <input class="form-control" placeholder="Lastname" name="user_lastname" type="text" value="<?php echo $user_lastname; ?>" required>
                        </div>

                        <p>Address:</p>
                        <div class="form-group">
                            <input class="form-control" placeholder="Address" name="user_address" type="text" value="<?php echo $user_address; ?>" required>
                        </div>

                        <p>Password:</p>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="user_password" type="password" value="<?php echo $user_password; ?>" required>
                        </div>

                        <div class="form-group">
                            <input class="form-control hide" name="user_id" type="text" value="<?php echo $user_id; ?>" required>
                        </div>

                        <button class="btn btn-block btn-success btn-md" name="user_save">Save</button>
                        <button type="button" class="btn btn-block btn-danger btn-md" data-dismiss="modal">Cancel</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#priceinput').keypress(function(event) {
            return isNumber(event, this)
        });
    });

    function isNumber(evt, element) {
        var charCode = (evt.which) ? evt.which : event.keyCode

        if (
            (charCode != 45 || $(element).val().indexOf('-') != -1) &&
            (charCode != 46 || $(element).val().indexOf('.') != -1) &&
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>
</body>
</html>