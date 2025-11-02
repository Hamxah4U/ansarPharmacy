<?php
		require 'partials/security.php';
    require 'partials/header.php';
    require 'model/Database.php';  

  if(isset($_GET['id'])){
    $id = $_GET['id'];
  }

  $stmt = $db->query("SELECT * FROM customers_tbl c JOIN `transaction_tbl` t ON c.id = t.CID WHERE t.cid = '$id' ORDER BY t.TID DESC ");
  $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql = $db->query("SELECT * FROM `customers_tbl` WHERE id = '$id' ");
  $user = $sql->fetch(PDO::FETCH_ASSOC);


  function generateTransactionCode() {
    return date('ymd') . rand(100000000, 999999999);
  }

  if(!isset($_POST['tcode'])) {
    $tCode = generateTransactionCode();
    unset($_SESSION['customername']);
  } else {
    $tCode = $_POST['tcode'];
  }
?> 



    <!-- Page Wrapper -->
<div id="wrapper">
  <!-- Sidebar -->
  <?php require 'partials/sidebar.php' ?>

    <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">
		<!-- Main Content -->
		<div id="content">
			<!-- Topbar -->
			<?php		require 'partials/nav.php'; ?>

			<!-- Begin Page Content -->
			<div class="container-fluid" style="max-height: 200px;">
        <!-- Page Heading -->

					<!-- Content Row -->
        <form id="addTransaction">
          <input  value="<?= $id; ?>"   type="hidden" name="cid" class="form-control" />          
          <input type="hidden" name="fname" value="<?= $user['Fullname'] ?>">
          <input type="hidden" name="phone" id="" value="<?= $user['phone'] ?>">
          <div class="form-row">              
            <div class="form-group col-md-5">
              <input type="number" name="amount" class="form-control" placeholder="Amount to Pay" />
              <small class="text-danger" id="amount"></small>
            </div>
            <div class="form-group col-md-5">
              <input type="text" id="" name="narration" class="form-control" placeholder="Narration">
              <small id="narration" class="text-danger"></small>
            </div>
            <div class="form-group col-md-2">
              <button type="submit" class="btn btn-primary mb-3"><strong>Save</strong></button>
            </div>
          </div>
        </form>            
       
        <strong><h3 style="color:red"> <?= $user['Fullname'] ?> - Credit Book</h3></strong>
        <br>
        <div class="table-responsive"> 
          <table class="table table-striped" id="creditors" style="width: 100%;">
            <thead>
            <tr style="white-space: nowrap;">
              <th>#</th>
              <th>Store</th>
              <th>Product</th>
              <th>qty</th>
              <th>Narration</th>
              <th>Date</th>
              <th>Time</th>
              <th>Credit (&#8358;)</th>
              <th>Amount Paid (&#8358;)</th>
              <th>Bal.(&#8358;)</th>
              <!-- <th>Receipt</th>  -->
              <th>Serviceby</th>
              
              </tr>
            </thead>
            <tbody>
              <?php
                $cr = 0;
                $dr = 0;
                foreach($customers as $index => $customer):
                $bal = $customer['Amount'] - $customer['Credit'];

                $cr += $customer['Credit'];
                $dr += $customer['Amount'];
                ?>
                <tr style="white-space: nowrap;">
                  <td><?= $index + 1 ?></td>
                  <td>
                    <?php
                      $sqlstore = $db->query("SELECT * FROM `department_tbl` WHERE deptID = '".$customer['tDepartment']."' ");
                      $row = $sqlstore->fetch(PDO::FETCH_ASSOC);
                      if($row == null){
                        echo '--';
                      }else{
                        echo $row['Department']  ;
                      }
                      
                      // echo $customer['tDepartment']                  
                    ?>
                  </td>
                  <td>
                    <?php
                      $sqlproduct = $db->query("SELECT * FROM `supply_tbl` WHERE `SupplyID` = '".$customer['Product']."' ");
                      $rowpro = $sqlproduct->fetch(PDO::FETCH_ASSOC);
                      if($rowpro == null){
                        echo '--';
                      }else{
                        echo $rowpro['ProductName'];
                      }
                      
                    ?>
                  </td>
                  <td><?= $customer['qty'] ?></td>
                  <td><?= $customer['narration'] ?></td>                
                  <td><?= $customer['TransacDate'] ?></td>
                  <td><?= $customer['TransacTime'] ?></td>
                  <td class="text-warning"><?= number_format($customer['Credit'], 2) ?></td>
                  <td class="text-info"><?= number_format($customer['Amount'], 2) ?? '--' ?></td>
                  <!-- <td>
                    <?php
                      //if($customer['Amount'] != '' && $customer['Credit'] == null): 
                    ?>
                      <a href="#" class="btn btn-primary">Receipt</a> 
                    <?php // endif ?>
                  </td> -->
                  <td></td>
                  <td><?= $customer['TrasacBy'] ?></td>

                </tr>
              <?php endforeach ?>
              <tr>
                <td colspan="7"></td>
                <td class="text-danger"><?= number_format($cr, 2) ?></td>
                <td class="text-success"><?= number_format($dr, 2) ?></td>
                <td colspan="8">
                  <?php
                  $netBal = $dr - $cr;
                    if($dr > $cr): 
                  ?>
                  <button type="button" class="btn btn-success"><strong>(&#8358;) <?= $netBal ?></strong></button>
                  <?php else: ?>
                  <button type="button" class="btn btn-danger"><strong>(&#8358;) <?= number_format($netBal, 2) ?></strong></button>
                  <?php endif ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

			</div>
		</div>
		<!-- End of Main Content -->
<?php  //require 'partials/footer.php'; ?>

<script>
  $(document).ready(function(){
    $('#addTransaction').on('submit', function(e){
      e.preventDefault();
      $('.text-danger').text('');
      $.ajax({
        url: 'model/fundaccount.php',
        dataType: 'JSON',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response){
          if(response.status){
            const Toast = Swal.mixin({
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 2000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
              }
            });
            Toast.fire({
              icon: "success",
              title: response.success.message
            });
            setTimeout(function(){
              location.reload();
            }, 2500);
          }else{
            $('#amount').text(response.errors.amount || '');
            $("#narration").text(response.errors.narration || '');
          }
        },
        error: function(xhr, status, error){
          console.log(xhr.responseText); 
          alert("Error: " + xhr.responseText);
        }
      });
    });
  });
</script>

<script>
  $(document).ready(function(){
    $('#creditors').dataTable();
  })
</script>