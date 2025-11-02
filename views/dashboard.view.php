<?php
require 'partials/security.php';
require 'partials/header.php';
require 'model/Database.php';


$dailysql = $db->query("SELECT 
        SUM(Amount) AS ttamount, 
        COALESCE(SUM(CAST(cash AS DECIMAL(10,2))), 0) AS dcash, 
        COALESCE(SUM(CAST(pos AS DECIMAL(10,2))), 0) AS dpos, 
        COALESCE(SUM(CAST(transfer AS DECIMAL(10,2))), 0) AS dtransfer 
    FROM transaction_tbl 
    WHERE `Status` = 'Paid' 
    AND DATE(TransacDate) = CURRENT_DATE() 
    AND TID IN (
        SELECT MIN(TID) 
        FROM transaction_tbl 
        WHERE DATE(TransacDate) = CURRENT_DATE() 
        GROUP BY tCode
    )"
);
$dailyRow = $dailysql->fetch(PDO::FETCH_ASSOC);

$monthlysql = $db->query("SELECT SUM(Amount) AS ttamount, COALESCE(SUM(CAST(cash AS DECIMAL(10,2))), 0) AS mcash, COALESCE(SUM(CAST(pos AS DECIMAL(10,2))), 0) AS mpos, COALESCE(SUM(CAST(`transfer` AS DECIMAL(10,2))), 0) AS mtransfer FROM transaction_tbl WHERE `Status` = 'Paid' AND MONTH(TransacDate) = MONTH(CURRENT_DATE()) AND YEAR(TransacDate) = YEAR(CURRENT_DATE()) AND TID IN ( SELECT MIN(TID) FROM transaction_tbl WHERE MONTH(TransacDate) = MONTH(CURRENT_DATE()) AND YEAR(TransacDate) = YEAR(CURRENT_DATE()) GROUP BY tCode, TransacDate )");
$monthlyRow = $monthlysql->fetch(PDO::FETCH_ASSOC);

$yearlysql = $db->query("SELECT SUM(Amount) AS ttamount, COALESCE(SUM(CAST(cash AS DECIMAL(10,2))), 0) AS ycash, COALESCE(SUM(CAST(pos AS DECIMAL(10,2))), 0) AS ypos, COALESCE(SUM(CAST(`transfer` AS DECIMAL(10,2))), 0) AS ytransfer FROM transaction_tbl WHERE `Status` = 'Paid' AND YEAR(TransacDate) = YEAR(CURRENT_DATE()) AND TID IN ( SELECT MIN(TID) FROM transaction_tbl WHERE YEAR(TransacDate) = YEAR(CURRENT_DATE()) GROUP BY tCode, TransacDate )");
$yearlyrow = $yearlysql->fetch(PDO::FETCH_ASSOC);

$totalsql = $db->query("SELECT SUM(Amount) AS ttamount, COALESCE(SUM(CAST(cash AS DECIMAL(10,2))), 0) AS tcash, COALESCE(SUM(CAST(pos AS DECIMAL(10,2))), 0) AS tpos, COALESCE(SUM(CAST(`transfer` AS DECIMAL(10,2))), 0) AS ttransfer FROM transaction_tbl WHERE `Status` = 'Paid' AND TID IN ( SELECT MIN(TID) FROM transaction_tbl GROUP BY tCode, TransacDate )");
$totalrow = $totalsql->fetch(PDO::FETCH_ASSOC);

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
            <?php require 'partials/nav.php'; ?>            
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid" style="max-height: 200px;">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <?php if($_SESSION['role'] == 'Admin'):?>
                        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
                        <a href="/reportsummery" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> <strong>Generate Report</strong>
                        </a>
                    <?php else: ?>
                        <h1 class="h3 mb-0 text-gray-800">User Dashboard</h1>
                        <a href="/sellerreportsummery" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> <strong>Generate Report</strong>
                        </a>   
                    <?php endif ?>
                </div>

                <!-- admin report dashboard -->
                <?php if($_SESSION['role'] == 'Admin'): ?>
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Earnings (Daily)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $stmt = $db->query('SELECT COALESCE(SUM(`Amount`))  AS `dailyTotal` FROM `transaction_tbl` WHERE `Status` = "Paid" AND DATE(`TransacDate`) = CURRENT_DATE');
                                                $daily = $stmt->fetch(PDO::FETCH_ASSOC);
                                                $amount = $daily['dailyTotal'] ?? '0';
                                                echo number_format($amount, 2, '.', ',');
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

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Earnings (Monthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $stmt = $db->query('SELECT COALESCE(SUM(`Amount`), 0) AS `monthlyTotal` FROM `transaction_tbl`WHERE `Status` = "Paid" AND MONTH(`TransacDate`) = MONTH(CURRENT_DATE) AND YEAR(`TransacDate`) = YEAR(CURRENT_DATE)');
                                                $monthly = $stmt->fetch(PDO::FETCH_ASSOC);
                                                echo number_format($monthly['monthlyTotal'], 2, '.', ',');
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

                        <!-- Earnings (Monthly) Card Example -->
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
                                                        $stmt = $db->query('SELECT COALESCE(SUM(`Amount`), 0) AS `yearlyTotal` FROM `transaction_tbl` WHERE `Status` = "Paid" AND YEAR(`TransacDate`) = YEAR(CURRENT_DATE)');
                                                        $yearlyTotal = $stmt->fetch(PDO::FETCH_ASSOC);
                                                        echo number_format($yearlyTotal['yearlyTotal'], 2, '.', ',');
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

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                        $stmt = $db->query('SELECT COALESCE(SUM(`Amount`), 0) AS `totalTransaction` FROM `transaction_tbl` WHERE `Status` = "Paid"');
                                        $total = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo number_format($total['totalTransaction'], '2', '.', ',');
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
                    </div>
                    <br/>

                    <strong>Gross Profit</strong>
                    <div class="row">
                        <!-- Earnings Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Profit (Daily)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $stmtd = $db->query("SELECT SUM(COALESCE(`profit`, 0)) AS dprofit 
                                                FROM `transaction_tbl` 
                                                WHERE `pprice` IS NOT NULL AND `pprice_amount` IS NOT NULL AND (`Status` = 'Paid' OR `Status` = 'Credit') 
                                                AND DATE(`TransacDate`) = CURRENT_DATE()
                                            ");
                                                $dprofit = $stmtd->fetch(PDO::FETCH_ASSOC);
                                                echo number_format($dprofit['dprofit'], 2);
                                            ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-800"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Profit (Monthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $stmtm = $db->query("SELECT SUM(COALESCE(`profit`, 0)) AS mprofit 
                                                FROM `transaction_tbl` 
                                                WHERE `pprice` IS NOT NULL AND `pprice_amount` IS NOT NULL AND (`Status` = 'Paid' OR `Status` = 'Credit') 
                                                AND MONTH(`TransacDate`) = MONTH(CURRENT_DATE()) 
                                                AND YEAR(`TransacDate`) = YEAR(CURRENT_DATE())
                                            ");
                                                $mprofit = $stmtm->fetch(PDO::FETCH_ASSOC);
                                                echo number_format($mprofit['mprofit'], 2);
                                            ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-800"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Profit (Yearly)</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    <?php
                                                        $stmty = $db->query("SELECT SUM(COALESCE(`profit`, 0)) AS yprofit 
                                                        FROM `transaction_tbl` 
                                                        WHERE `pprice` IS NOT NULL AND `pprice_amount` IS NOT NULL AND (`Status` = 'Paid' OR `Status` = 'Credit') 
                                                        AND YEAR(`TransacDate`) = YEAR(CURRENT_DATE())
                                                    ");
                                                        $yprofit = $stmty->fetch(PDO::FETCH_ASSOC);
                                                        echo number_format($yprofit['yprofit'], 2);
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-800"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Profit</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                        $stmttt = $db->query(" SELECT SUM(COALESCE(`profit`, 0)) AS ttprofit 
                                        FROM `transaction_tbl` 
                                        WHERE `pprice` IS NOT NULL AND `pprice_amount` IS NOT NULL AND (`Status` = 'Paid' OR `Status` = 'Credit')
                                    ");
                                        $ttprofit = $stmttt->fetch(PDO::FETCH_ASSOC);
                                        echo number_format($ttprofit['ttprofit'], 2);
                                    ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-800"></i>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <strong>Total Expenses</strong>
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
                                                $stmt = $db->query('SELECT financecollect_tbl.Givername AS receiver, COALESCE(SUM(financecollect_tbl.Amount), 0 ) AS daily  FROM financecollect_tbl WHERE DATE(Dateissued) = CURRENT_DATE ');
                                                $daily = $stmt->fetch(PDO::FETCH_ASSOC);
                                                echo number_format($daily['daily'], 2, '.', ',');
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
                                                $stmt = $db->query('SELECT Collectorname, financecollect_tbl.Givername AS receiver, COALESCE(SUM(financecollect_tbl.Amount), 0 ) AS monthly FROM financecollect_tbl WHERE MONTH(Dateissued) = MONTH(CURRENT_DATE) AND YEAR(Dateissued) = YEAR(CURRENT_DATE)  ');
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
                                                        $stmt = $db->query('SELECT Collectorname, financecollect_tbl.Givername AS receiver, COALESCE(SUM(financecollect_tbl.Amount), 0 ) AS yearlyTotal FROM financecollect_tbl WHERE YEAR(Dateissued) = YEAR(CURRENT_DATE) ');
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
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Cashout</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                        $stmt = $db->query('SELECT Collectorname, financecollect_tbl.Givername AS receiver, COALESCE(SUM(financecollect_tbl.Amount), 0 ) AS Totalcashout FROM financecollect_tbl ');
                                        $total = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo number_format($total['Totalcashout'], '2', '.', ',');
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
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cash-in (Daily)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?=  number_format($dailyRow['dcash'], 2); ?>
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
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cash-in (MOnthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= number_format($monthlyRow['mcash'], 2); ?>
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
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cash-in (Yearly)</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    <?= number_format($yearlyrow['ycash'], 2); ?>
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
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Cash-in</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($totalrow['tcash'], 2); ?>
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
                                            <?=  number_format($dailyRow['dtransfer'], 2); ?>
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
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Transfer (Monthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= number_format($monthlyRow['mtransfer'], 2); ?>
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
                                                        <?= number_format($yearlyrow['ytransfer'], 2); ?>
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
                                        <?= number_format($totalrow['ttransfer'], 2); ?>
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
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">POS (Daily)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?=  number_format($dailyRow['dpos'], 2);?>
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
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">POS (Monthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                               <?= number_format($monthlyRow['mpos'], 2); ?>
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
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">POS (Yearly)</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    <?= number_format($yearlyrow['ypos'], 2); ?>
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
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Total POS</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($totalrow['tpos'], 2); ?>
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

                <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <!-- <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">___Profit (Daily)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                          <?php
                                            $stmt = $db->query('SELECT DATE(t.TransacDate) AS ttdate, SUM((t.Price - s.Pprice) * t.qty) AS daily_profit FROM transaction_tbl t JOIN supply_tbl s ON t.Product = s.SupplyID WHERE t.TransacDate = CURRENT_DATE GROUP BY DATE(t.TransacDate)');
                                            $daily = $stmt->fetch(PDO::FETCH_ASSOC);
                                            echo number_format($daily['daily_profit'], 2, '.');
                                          ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                      <i class="fas fa-money-bill fa-2x text-gray-2000"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Earnings (Monthly) Card Example -->
                    <!-- <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Profit (MOnthly)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                          <?php
                                              $stmt = $db->query('SELECT YEAR(t.TransacDate) AS year, MONTH(t.TransacDate) AS month, SUM((t.Price - s.Pprice) * t.qty) AS monthly_profit FROM transaction_tbl t JOIN supply_tbl s ON t.Product = s.SupplyID WHERE YEAR(t.TransacDate) = YEAR(CURRENT_DATE) AND MONTH(t.TransacDate) = MONTH(CURRENT_DATE) GROUP BY YEAR(t.TransacDate), MONTH(t.TransacDate);');
                                              $monthly = $stmt->fetch(PDO::FETCH_ASSOC);
                                              echo number_format($monthly['monthly_profit'], 2, '.');
                                          ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill fa-2x text-gray-2000"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Earnings (Monthly) Card Example -->
                    <!-- <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Profit (Yearly)</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                  <?php
                                                    $stmt = $db->query('SELECT COALESCE(SUM(`Amount`), 0) AS `yearlyTotal` FROM `transaction_tbl` WHERE YEAR(`TransacDate`) = YEAR(CURRENT_DATE)');
                                                    $yearlyTotal = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    echo number_format($yearlyTotal['yearlyTotal'], 2, '.');
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
                    </div> -->

                    <!-- Pending Requests Card Example -->
                    <!-- <div class="col-xl-3 col-md-6 mb-4">
                      <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Profit</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                  <?php
                                    $stmt = $db->query('SELECT COALESCE(SUM(`Amount`), 0) AS `totalTransaction` FROM `transaction_tbl`');
                                    $total = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo number_format($total['totalTransaction'], '2', '.');
                                  ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill fa-2x text-gray-2000"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> -->
                </div>

                <!-- user dahsboard report -->
                <?php else: ?>
                <?php require 'seller.report.php' ?>                
                <?php endif ?>                
                <!-- Content Row -->
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->
<?php
    require 'partials/footer.php';
?>


