<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
//Add Customer
if (isset($_POST['updateCustomer'])) {
  // Prevent Posting Blank Values
  if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email']) || empty($_POST['customer_address'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $customer_name = $_POST['customer_name'];
    $customer_phoneno = $_POST['customer_phoneno'];
    $customer_email = $_POST['customer_email'];
    $customer_address = $_POST['customer_address'];
    $update = $_GET['update'];

    // Update customer details including address in the database
    $postQuery = "UPDATE rpos_customers 
                    SET customer_name = ?, customer_phoneno = ?, customer_email = ?, customer_address = ? 
                    WHERE customer_id = ?";
    $postStmt = $mysqli->prepare($postQuery);

    // Bind the parameters (added customer_address)
    $rc = $postStmt->bind_param('sssss', $customer_name, $customer_phoneno, $customer_email, $customer_address, $update);
    $postStmt->execute();

    // Check if the update was successful
    if ($postStmt) {
      $success = "Customer Updated Successfully" && header("refresh:1; url=customes.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}
require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php
  require_once('partials/_sidebar.php');
  ?>
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php
    require_once('partials/_topnav.php');
    $update = $_GET['update'];
    $ret = "SELECT * FROM  rpos_customers WHERE customer_id = '$update' ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($cust = $res->fetch_object()) {
      ?>
      <!-- Header -->
      <div style="background-image: url(../admin/assets/img/theme/21231.avif); background-size: cover;"
        class="header  pb-8 pt-5 pt-md-8">
        <span class="mask bg-gradient-dark opacity-8"></span>
        <div class="container-fluid">
          <div class="header-body">
          </div>
        </div>
      </div>
      <!-- Page content -->
      <div class="container-fluid mt--8">
        <!-- Table -->
        <div class="row">
          <div class="col">
            <div class="card shadow">
              <div class="card-header border-0">
                <h3>Please Fill All Fields</h3>
              </div>
              <div class="card-body">
                <form method="POST">
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Customer Name</label>
                      <input type="text" name="customer_name" value="<?php echo $cust->customer_name; ?>"
                        class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Customer Phone Number</label>
                      <input type="text" name="customer_phoneno" value="<?php echo $cust->customer_phoneno; ?>"
                        class="form-control" value="">
                    </div>
                  </div>
                  <hr>
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Customer Email</label>
                      <input type="email" name="customer_email" value="<?php echo $cust->customer_email; ?>"
                        class="form-control" value="">
                    </div>
                    <div class="col-md-6">
                      <label>Customer Address</label>
                      <input type="text" name="customer_address" value="<?php echo $cust->customer_address; ?>"
                        class="form-control" value="">
                    </div>
                  </div>
                  <br>
                  <div class="form-row">
                    <div class="col-md-6">
                      <input type="submit" name="updateCustomer" value="Update Customer" class="btn btn-success" value="">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Footer -->
        <?php
        require_once('partials/_footer.php');
    }
    ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>