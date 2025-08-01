<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

$staff_id = $_SESSION['staff_id'];  // Use the appropriate session variable for customer ID
$sql = "SELECT staff_name FROM rpos_staff WHERE staff_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $staff_id);
$stmt->execute();
$stmt->bind_result($staff_name);
$stmt->fetch();
$stmt->close();  // Close the statement after fetching

require_once('partials/_head.php');
require_once('partials/_analytics.php');
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
    ?>
    <!-- Header -->
    <div style="background-image: url(../admin/assets/img/theme/21231.avif); background-size: cover;"
      class="header  pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <div class="row justify-content-end">
            <!-- <div class="col-xl-3 col-lg-6" >
              <div class="card card-stats mb-4 mb-xl-0" style="background: linear-gradient(to bottom, #fff3e0, #ffe0b2);">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Customers</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $customers; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0" style="background: linear-gradient(to bottom, #fff3e0, #ffe0b2);">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Products</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $products; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                        <i class="fas fa-tools"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0" style="background: linear-gradient(to bottom, #fff3e0, #ffe0b2);">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Orders</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $orders; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-shopping-cart"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0"
                style="background: linear-gradient(to bottom, #00c6ff, #0072ff); border-radius: 15px; box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease-in-out;">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-white mb-0">Sales</h5>
                      <span class="h2 font-weight-bold mb-0 text-white">₱<?php echo $sales; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-white text-success rounded-circle shadow">
                        <i class="fas fa-coins"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Recent Orders</h3>
                </div>
                <div class="col text-right">
                  <a href="orders_reports.php" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th class="text-success" scope="col">Code</th>
                    <th scope="col">Customer</th>
                    <th class="text-success" scope="col">Product</th>
                    <th scope="col">Unit Price</th>
                    <th class="text-success" scope="col">Qty</th>
                    <th scope="col">Total</th>
                    <th scop="col">Status</th>
                    <th class="text-success" scope="col">Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM  rpos_orders ORDER BY `rpos_orders`.`created_at` DESC LIMIT 7 ";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($order = $res->fetch_object()) {
                    $prod_price = is_numeric($order->prod_price) ? $order->prod_price : 0;
                    $prod_qty = is_numeric($order->prod_qty) ? $order->prod_qty : 0;

                    $total = $prod_price * $prod_qty;
                    ?>
                    <tr>
                      <th class="text-success" scope="row"><?php echo $order->order_code; ?></th>
                      <td><?php echo $order->customer_name; ?></td>
                      <td class="text-success"><?php echo $order->prod_name; ?></td>
                      <td>₱<?php echo $order->prod_price; ?></td>
                      <td class="text-success"><?php echo $order->prod_qty; ?></td>
                      <td>₱ <?php echo $total; ?></td>
                      <td><?php if ($order->order_status == '') {
                        echo "<span class='badge badge-danger'>Not Paid</span>";
                      } else {
                        echo "<span class='badge badge-success'>$order->order_status</span>";
                      } ?></td>
                      <td class="text-success"><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-xl-12">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Recent Payments</h3>
                </div>
                <div class="col text-right">
                  <a href="payments_reports.php" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th class="text-success" scope="col">Code</th>
                    <th scope="col">Amount</th>
                    <th class='text-success' scope="col">Order Code</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM   rpos_payments   ORDER BY `rpos_payments`.`created_at` DESC LIMIT 7 ";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($payment = $res->fetch_object()) {
                    ?>
                    <tr>
                      <th class="text-success" scope="row">
                        <?php echo $payment->pay_code; ?>
                      </th>
                      <td>
                        ₱<?php echo $payment->pay_amt; ?>
                      </td>
                      <td class='text-success'>
                        <?php echo $payment->order_code; ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php require_once('partials/_footer.php'); ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>

  <?php if (isset($staff_name)) { ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.all.min.js"></script>
    <script>
      Swal.fire({
        title: 'Welcome, <?php echo $staff_name; ?>!',
        text: 'You have successfully logged in.',
        icon: 'success',
        showConfirmButton: false, // Remove the confirm button
        timer: 2000, // Auto close the alert after 2 seconds
        timerProgressBar: true // Optional: Shows a progress bar for the timer
      });
    </script>
  <?php } ?>
</body>

</html>

