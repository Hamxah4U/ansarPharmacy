<!-- <div class="row">
   
  <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Earnings (Daily)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php
                        //   $stmt = $db->query('SELECT COALESCE(SUM(Amount), 0) AS `dailyTotal` FROM `transaction_tbl` WHERE `Status` = "Paid"  AND `TransacDate` = CURRENT_DATE AND `TrasacBy` = "'.$_SESSION['email'].'"');
                        //   $daily = $stmt->fetch(PDO::FETCH_ASSOC);
                        //   echo number_format($daily['dailyTotal'], 2, '.', ',');
                      ?>
                      </div>
                  </div>
                  <div class="col-auto">
                  <i class="fas fa-money-bill fa-2x text-gray-2000"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>

  
  <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Earnings (MOnthly)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php
                        //   $stmt = $db->query('SELECT COALESCE(SUM(`Amount`), 0) AS `monthlyTotal` FROM `transaction_tbl`WHERE `Status` = "Paid" AND `TrasacBy` = "'.$_SESSION['email'].'" AND MONTH(`TransacDate`) = MONTH(CURRENT_DATE) AND YEAR(`TransacDate`) = YEAR(CURRENT_DATE)');
                        //   $monthly = $stmt->fetch(PDO::FETCH_ASSOC);
                        //   echo number_format($monthly['monthlyTotal'], 2, '.', ',');
                      ?>
                      </div>
                  </div>
                  <div class="col-auto">
                      <i class="fas fa-money-bill fa-2x text-gray-2000"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <?php //<!-- Earnings (Monthly) Card Example -->?>
  <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Earnings (Yearly)</div>
                      <div class="row no-gutters align-items-center">
                          <div class="col-auto">
                              <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                              <?php
                                //   $stmt = $db->query('SELECT COALESCE(SUM(`Amount`), 0) AS `yearlyTotal` FROM `transaction_tbl` WHERE `Status` = "Paid" AND `TrasacBy` = "'.$_SESSION['email'].'" AND YEAR(`TransacDate`) = YEAR(CURRENT_DATE)');
                                //   $yearlyTotal = $stmt->fetch(PDO::FETCH_ASSOC);
                                //   echo number_format($yearlyTotal['yearlyTotal'], 2, '.', ',');
                              ?>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-auto">
                      <i class="fas fa-money-bill fa-2x text-gray-2000"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <?php //<!-- Pending Requests Card Example --> ?>
  <div class="col-xl-3 col-md-6 mb-4">
  <div class="card border-left-info shadow h-100 py-2">
      <div class="card-body">
      <div class="row no-gutters align-items-center">
          <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
              <?php
                //   $stmt = $db->query('SELECT COALESCE(SUM(`Amount`), 0) AS `totalTransaction` FROM `transaction_tbl` WHERE `Status` = "Paid" AND `TrasacBy` = "'.$_SESSION['email'].'" ');
                //   $total = $stmt->fetch(PDO::FETCH_ASSOC);
                //   echo number_format($total['totalTransaction'], '2', '.', ',');
              ?>
              </div>
          </div>
          <div class="col-auto">
              <i class="fas fa-money-bill fa-2x text-gray-2000"></i>
          </div>
      </div>
      </div>
  </div>
  </div>
</div> -->






<!-- cash out -->
<div class="row">
    <!-- Earnings Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cashout (Daily)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $stmt = $db->query('SELECT financecollect_tbl.Givername AS receiver, COALESCE(SUM(financecollect_tbl.Amount), 0 ) AS daily  FROM financecollect_tbl WHERE DATE(Dateissued) = CURRENT_DATE AND Givername  = "'.$_SESSION['email'].'" ');
                            $daily = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo number_format($daily['daily'], 2, '.', '');
                        ?>
                        </div>
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-money-bill-alt fa-2x text-gray-2000" style="transform: rotate(180deg);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cashout (MOnthly)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $stmt = $db->query('SELECT Collectorname, financecollect_tbl.Givername AS receiver, COALESCE(SUM(financecollect_tbl.Amount), 0 ) AS monthly FROM financecollect_tbl WHERE MONTH(Dateissued) = MONTH(CURRENT_DATE) AND YEAR(Dateissued) = YEAR(CURRENT_DATE) AND Givername = "'.$_SESSION['email'].'" ');
                            $monthly = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo number_format($monthly['monthly'], 2, '.', ',');
                        ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-alt fa-2x text-gray-2000" style="transform: rotate(180deg);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cashout (Yearly)</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                <?php
                                    $stmt = $db->query('SELECT Collectorname, financecollect_tbl.Givername AS receiver, COALESCE(SUM(financecollect_tbl.Amount), 0 ) AS yearlyTotal FROM financecollect_tbl WHERE YEAR(Dateissued) = YEAR(CURRENT_DATE) AND Givername = "'.$_SESSION['email'].'"');
                                    $yearlyTotal = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo number_format($yearlyTotal['yearlyTotal'], 2, '.', ',');
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-alt fa-2x text-gray-2000" style="transform: rotate(180deg);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Cashout</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                    $stmt = $db->query('SELECT Collectorname, financecollect_tbl.Givername AS receiver, COALESCE(SUM(financecollect_tbl.Amount), 0 ) AS Totalcashout FROM financecollect_tbl WHERE Givername = "'.$_SESSION['email'].'" ');
                    $total = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo number_format($total['Totalcashout'], '2', '.', ',');
                ?>
                </div>
            </div>
            <div class="col-auto">
                <i class="fas fa-money-bill fa-2x text-gray-300"></i>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<!-- total cash colleted -->
<strong>Total Cash Collect</strong>
<div class="row">
  <!-- Earnings Card Example -->                         
  <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cashout (Daily)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php
                          $stmt = $db->query("SELECT COALESCE(SUM(`cash`), 0) AS dailyCash FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND DATE(`TransacDate`) = CURRENT_DATE() AND `TrasacBy` = '".$_SESSION['email']."' ");
                          $daily = $stmt->fetch(PDO::FETCH_ASSOC);
                          echo number_format($daily['dailyCash'], 2, '.', ',');
                      ?>
                      </div>
                  </div>
                  <div class="col-auto">
                 <i class="icofont-cash-on-delivery fa-4x text-gray-2000"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Earnings (Monthly) Card Example -->
  <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cashout (MOnthly)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php
                          $stmt = $db->query("SELECT COALESCE(SUM(`cash`), 0) AS monthlyCash FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND MONTH(`TransacDate`) = MONTH(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."'  ");
                          $monthly = $stmt->fetch(PDO::FETCH_ASSOC);
                          echo number_format($monthly['monthlyCash'], 2, '.', ',');
                      ?>
                      </div>
                  </div>
                  <div class="col-auto">
                      <i class="icofont-cash-on-delivery fa-4x text-gray-2000"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Earnings (Monthly) Card Example -->
  <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cashout (Yearly)</div>
                      <div class="row no-gutters align-items-center">
                          <div class="col-auto">
                              <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                              <?php
                                  $stmt = $db->query("SELECT COALESCE(SUM(`cash`), 0) AS yearlyCash FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND YEAR(`TransacDate`) = YEAR(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."' ");
                                  $yearlyTotal = $stmt->fetch(PDO::FETCH_ASSOC);
                                  echo number_format($yearlyTotal['yearlyCash'], 2, '.', ',');
                              ?>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-auto">
                      <i class="icofont-cash-on-delivery fa-4x text-gray-2000"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Pending Requests Card Example -->
  <div class="col-xl-3 col-md-6 mb-4">
  <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
      <div class="row no-gutters align-items-center">
          <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Cash Collect</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
              <?php
                  $stmt = $db->query("SELECT COALESCE(SUM(`cash`), 0) AS yearlyCash FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND `TrasacBy` = '".$_SESSION['email']."' ");
                  $total = $stmt->fetch(PDO::FETCH_ASSOC);
                  echo number_format($total['yearlyCash'], '2', '.', ',');
              ?>
              </div>
          </div>
          <div class="col-auto">
              <i class="icofont-cash-on-delivery fa-4x text-gray-2000"></i>
          </div>
      </div>
      </div>
  </div>
  </div>
</div>

<!-- total transfer recieved -->
<strong>Total Transfer Receive</strong>
<div class="row">
    <!-- Earnings Card Example -->                         
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Transfer (Daily)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $stmt = $db->query("SELECT COALESCE(SUM(`transfer`), 0) AS dailytransfer FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND DATE(`TransacDate`) = DATE(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."'  ");
                            $daily = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo number_format($daily['dailytransfer'], 2, '.', ',');
                        ?>
                        </div>
                    </div>
                    <div class="col-auto">
                    <i class="icofont-bank-transfer fa-3x text-gray-2000"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Transfer (MOnthly)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $stmt = $db->query("SELECT COALESCE(SUM(`transfer`), 0) AS monthlytransfer FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND MONTH(`TransacDate`) = MONTH(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."'  ");
                            $monthly = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo number_format($monthly['monthlytransfer'], 2, '.', ',');
                        ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="icofont-bank-transfer fa-3x text-gray-2000"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Transfer (Yearly)</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                <?php
                                    $stmt = $db->query("SELECT COALESCE(SUM(`transfer`), 0) AS yearlytransfer FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND YEAR(`TransacDate`) = YEAR(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."' ");
                                    $yearlyTotal = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo number_format($yearlyTotal['yearlytransfer'], 2, '.', ',');
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="icofont-bank-transfer fa-3x text-gray-2000"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Transfer</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                    $stmt = $db->query("SELECT COALESCE(SUM(`transfer`), 0) AS totaltransfer FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND `TrasacBy` = '".$_SESSION['email']."' ");
                    $total = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo number_format($total['totaltransfer'], '2', '.', ',');
                ?>
                </div>
            </div>
            <div class="col-auto">
                <i class="icofont-bank-transfer fa-3x text-gray-2000"></i>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<!-- total pos recieve -->
<strong>Total POS Receive</strong>
<div class="row">
    <!-- Earnings Card Example -->                         
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-left-dark text-uppercase mb-1">POS (Daily)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $stmt = $db->query("SELECT COALESCE(SUM(`pos`), 0) AS dailyPos FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND DATE(`TransacDate`) = DATE(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."' ");
                            $daily = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo number_format($daily['dailyPos'], 2, '.', ',');
                        ?>
                        </div>
                    </div>
                    <div class="col-auto">
                    <i class="icofont-visa-alt fa-3x text-gray-2000"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-left-dark text-uppercase mb-1">POS (MOnthly)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $stmt = $db->query("SELECT COALESCE(SUM(`pos`), 0) AS monthlyPos FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND MONTH(`TransacDate`) = MONTH(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."'  ");
                            $monthly = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo number_format($monthly['monthlyPos'], 2, '.', ',');
                        ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="icofont-visa-alt fa-3x text-gray-2000"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-left-dark text-uppercase mb-1">POS (Yearly)</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                <?php
                                    $stmt = $db->query("SELECT COALESCE(SUM(`pos`), 0) AS yearlyPos FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND YEAR(`TransacDate`) = YEAR(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."' ");
                                    $yearlyTotal = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo number_format($yearlyTotal['yearlyPos'], 2, '.', ',');
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="icofont-visa-alt fa-3x text-gray-2000"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-left-dark text-uppercase mb-1">Total POS</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                    $stmt = $db->query("SELECT COALESCE(SUM(`pos`), 0) AS yearlyPos FROM `transaction_tbl` WHERE transaction_tbl.Status = 'Paid' AND `TrasacBy` = '".$_SESSION['email']."' ");
                    $total = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo number_format($total['yearlyPos'], '2', '.', ',');
                ?>
                </div>
            </div>
            <div class="col-auto">
                <i class="icofont-visa-alt fa-3x text-gray-2000"></i>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>