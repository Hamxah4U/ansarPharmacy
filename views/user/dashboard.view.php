<?php
  session_start();
  require_once './model/Database.php';
  

  function generateTransactionCode() {
    return date('ymd').rand(100000000, 999999999);
  }

if(!isset($_POST['tcode'])) {
  $tCode = generateTransactionCode();
  unset($_SESSION['customername']);
  //unset($_SESSION['dpt']);
  //$selected = ""; 
}else {
  $tCode = $_POST['tcode'];
}

if (isset($_POST['customername'])) {
  $_SESSION['customername'] = $_POST['customername'];
}

if(isset($_POST['dpt'])){
  $_SESSION['dpt'] = $_POST['dpt'];
}

$dpt = isset($_SESSION['dpt']) ? $_SESSION['dpt'] : "";
$cname = isset($_SESSION['customername']) ? $_SESSION['customername'] : "";

//$department = isset($_SESSION['dpt']) ? $_SESSION['dpt'] : "--choose--";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Record Management System</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Animate.css for animations -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }

    /* Navbar Styling */
    .navbar {
      background: linear-gradient(45deg, #07294d, #00bfa5);
      color: #fff;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand, .navbar-nav .nav-link {
      color: #fff !important;
      font-size: 1.2rem;
    }

    .navbar-nav .nav-link:hover {
      color: #ffdf5d !important;
      transition: color 0.3s ease;
    }

    /* Sidebar Styling */
    .sidebar {
      background: #07294d;
      min-height: 100vh;
      padding-top: 20px;
      transition: all 0.3s ease-in-out;
    }

    .sidebar h5 {
      color: #fff;
      text-align: center;
    }

    .sidebar a {
      color: #fff;
      text-decoration: none;
      padding: 15px;
      display: block;
      font-size: 1.1rem;
      margin: 10px 0;
      /* transition: background 0.3s, color 0.3s; */
    }

    .sidebar a:hover {
      background: #00bfa5;
      color: #fff;
    }

    .sidebar a.active {
      background: #00bfa5;
      color: #fff;
    }

    /* Main Content Panel */
    .panel {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin: 20px 0;
    }

    .panel-heading {
      background: linear-gradient(135deg, #07294d, #00bfa5);
      padding: 15px;
      border-radius: 10px 10px 0 0;
      color: #fff;
      font-size: 1.4rem;
      text-align: center;
    }

    /* Table Styling */
    .table {
      margin-top: 20px;
    }

    .table th {
      background-color: #07294d;
      color: #fff;
    }

    .table td, .table th {
      text-align: center;
    }

    /* Form Styling */
    .form-control {
      border-radius: 5px;
      border: 1px solid #ccc;
      transition: border 0.3s ease;
    }

    .form-control:focus {
      border-color: #00bfa5;
      box-shadow: none;
    }

    /* Animations */
    /* .fadeIn {
      animation: fadeIn 1.5s ease-in-out;
    } */

    /* .slideInLeft {
      animation: slideInLeft 0.5s ease-in-out;
    } */

    .zoomIn {
      animation: zoomIn 0.7s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes slideInLeft {
      from {
        transform: translateX(-100%);
      }
      to {
        transform: translateX(0);
      }
    }

    @keyframes zoomIn {
      from {
        transform: scale(0.5);
      }
      to {
        transform: scale(1);
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">SMART BILLING</a>
      <div class="ml-auto">
        <span class="navbar-text"><?= $_SESSION['fname'] ?></span>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      
      <div class="col-md-3 sidebar slideInLeft">
        <h5 class="animate__animated animate__fadeInLeft">Dashboard</h5>
        <a href="/billing" class="active animate__animated  animate__delay-1s">
          <i class="fas fa-money-bill"></i> Billing
        </a>
        <a href="#" onclick="alert('Coming Soon!')" class="animate__animated  animate__delay-2s">
          <i class="fas fa-file-invoice"></i> NHIS
        </a>
        <a href="#" onclick="alert('Coming Soon!')" class="animate__animated  animate__delay-3s">
          <i class="fas fa-chart-line"></i> Finance
        </a>
        <a href="#" class="animate__animated  animate__delay-4s">
          <i class="fas fa-chart-pie"></i> Report
        </a>
        <a href="#" class="text-danger animate__animated  animate__delay-5s">
          <i class="fas fa-sign-out-alt"></i> Log Out
        </a>
      </div>

      <div class="col-md-9">
        <div class="panel fadeIn">
          <div class="panel-heading">
            <strong>Sunnah Hospital Joas Plateau State</strong>
          </div>
          <div class="panel-body p-4">
           
            <!-- Billing Form -->
            <form id="addTransaction">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label><strong>Billing Code:</strong></label>
                  <!-- <input type="text" class="form-control" value="<?= $tCode ?>" readonly name="tcode"> -->
                  <input value="<?= $tCode; ?>" readonly type="text" name="tcode" class="form-control" />
                </div>
                <div class="form-group col-md-6">
                  <label><strong>Customer's Name:</strong></label>
                  <!-- <input type="text" class="form-control" name="customername"> -->
                  <input name="customername" value="<?= $cname; ?>" type="text" class="form-control" />
                  <small class="text-danger" id="errorName"></small>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label><strong>Department:</strong></label>
                  <select name="dpt" class="form-control" onchange="fetchServices(this.value)" id="unitdd">
                    <option value="--choose--">--choose--</option>
                    <?php
                        $stmt = $db->query('SELECT * FROM `department_tbl`');
                        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($units as $unit):
                          $selected = ($unit['deptID'] == $dpt) ? 'selected' : '';
                        ?>
                        <option value="<?= $unit['deptID'] ?>"  $selected >  <?= $unit['Department'] ?> </option>
                    <?php endforeach ?>
                  </select>
                  <small id="errorDpt" class="text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                  <label><strong>Service:</strong></label>
                  <select name="product" id="cproduct" class="form-control" onchange="fetchProductDetails(this.value)"> </select>
                  <small class="text-danger" id="errorService"></small>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label><strong>Price:</strong></label>
                  <input type="text" id="price" name="cprice" class="form-control" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label><strong>Qty:</strong></label>
                  <input type="number" id="qty" name="qty" class="form-control" readonly>
                </div>
              </div>

              <button type="submit" class="btn btn-primary">Add</button>
            </form>

    <table style="width:100%; color:black" class="table table-striped" id="">
      <form id="deleteForm" action="" method="post">
        <?php if (!empty($products)): ?>
          <tr>
            <th>#</th>
            <th>Service</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Amount(&#x20A6)</th>
            <th></th>
          </tr>
          <?php $totalAmount = 0;
            ?>
          <?php foreach ($products as $i => $row): ?>
                <tr style="text-align: center;">
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo $row['Productname']; ?></td>
                    <td><?php echo $row['Price']; ?></td>
                    <td><?php echo $row['qty']; ?></td>
                    <td><?php echo $row['Amount']; ?></td>
                    <?php $status = $row[11]; ?>
                    <td>
                        <?php
                            if($row[11] == 'Not-Paid'){?>
                                <button type="button" onclick="deleteProduct(<?php echo $row['TID']; ?>)" class="btn btn-warning">Delete</button>
                                <input type="hidden" name="tid" value="<?php echo $row['TID']; ?>">
                                <!-- Add hidden input field to store tCode -->
                                <input type="hidden" name="tcode" value="<?php echo $row['tCode']; ?>">
                            <?php }else{
                                echo $row[11];
                            }
                        ?>
                    </td>
                </tr>
                <?php $totalAmount += $row['Amount']; ?>
        <?php endforeach; endif?>

      </form>
    </table>
  



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<script>
  $(document).ready(function(){
    $('#addTransaction').on('submit', function(e){
      e.preventDefault();
      $.ajax({
        url: 'model/product.transac.php',
        dataType: 'JSON',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response){
          if(response.status){
            //alert('yes');
          }else{
            $('#errorName').text(response.errors.customer || '');
            $("#errorDpt").text(response.errors.unit || '');
            $('#errorService').text(response.errors.product || '');
          }
        },
        error: function(xhr, status, error){
          alert('error:' + xhr + status + error);
        }
      });
    });
  });
</script>
<script>
  function fetchServices(deptID) {
      if (deptID !== "") {
          $.ajax({
              url: "model/product.ajax.php",
              type: "POST",
              data: { department_id: deptID },
              success: function(response) {
                  $("#cproduct").html(response);
              }
          });
      } else {
          $("#cproduct").html('<option value="">--select service--</option>');
      }
  }

  function fetchProductDetails(productID) {
    if (productID !== "") {
      $.ajax({
          url: "model/price.ajax.php",
          type: "POST",
          data: { product_id: productID },
          success: function(response) {
              const data = JSON.parse(response);
              $("#price").val(data.price);
              $("#qty").val(data.quantity);
          }
      });
    }else {
      $("#price").val('');
      $("#qty").val('');
    }
  }
</script>