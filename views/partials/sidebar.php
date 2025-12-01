<style>
    strong{
        font-size: 12pt;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
</style>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-win"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            <!-- Smart billing -->
            <img src="../../img/ansar.png" alt="" style="width: 30%;">
        </div>
    </a>  

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <strong>Dashboard</strong></a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
     <?php  if($_SESSION['role'] == 'Admin'):?>        
                <li class="nav-item active">
                    <a class="nav-link" href="/users">
                        <i class="fas fa-fw fa-user"></i>
                        <strong>Manage Users </strong></a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="/billing">
                        <i class="fas fa-file-invoice-dollar"></i>
                       <strong>Retails</strong></a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="/servicebilling">
                        <i class="fas fa-file-invoice-dollar"></i>
                       <strong>Wholesales</strong></a>
                </li>


                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-hand-holding-usd"></i><strong>Creditors</strong>
                        <span></span>
                    </a>
                    

                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                          
                            <a class="collapse-item" href="/viewcreditors"><strong>View Creditors</strong></a>
                        </div>
                    </div>
                </li>

                <!-- <li class="nav-item active">
                    <a class="nav-link" href="/creditbilling">
                        <i class="fas fa-hand-holding-usd"></i>
                       Creditors Bill</a>
                </li> -->

                <li class="nav-item active">
                    <a class="nav-link" href="/finance">
                        <i class="fas fa-coins"></i>
                         <strong>Expenses</strong>
                    </a>
                </li>


                <!-- <li class="nav-item active">
                    <a class="nav-link" href="/unit">
                    <i class="fas fa-boxes"></i>
                    <strong>Manage Store</strong></a>
                </li> -->

                <!-- <li class="nav-item active">
                    <a class="nav-link" href="/product">
                    <i class="fas fa-shopping-cart"></i>
                    Manage Product</a>
                </li> -->
                
                <li class="nav-item active">
                    <a class="nav-link" href="/supply">
                    <i class="fas fa-truck"></i>
                    <strong>Manage Supply</strong></a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="/inventoryreport">
                    <i class="fas fa-search"></i>
                    <strong>Inventory Report</strong></a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="report">
                    <i class="fas fa-chart-pie"></i>
                    <strong>Sales Statement</strong></a>
                </li>
            <?php else: ?>
                <li class="nav-item active">
                    <a class="nav-link" href="/billing">
                        <i class="fas fa-money-bill"></i>
                       <strong>Retailes</strong></a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="/servicebilling">
                        <i class="fas fa-file-invoice-dollar"></i>
                       <strong>Wholesales</strong></a>
                </li>

                <!-- <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-hand-holding-usd"></i><strong>Creditors</strong>
                        <span></span>
                    </a>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="/viewcreditors"><strong>View Creditors</strong></a>
                        </div>
                    </div>
                </li> -->

                <!-- <li class="nav-item active">
                    <a class="nav-link" href="/nhisbilling">
                        <i class="fas fa-file-invoice"></i> NHIS</a>
                </li> -->

                <li class="nav-item active">
                    <a class="nav-link" href="/finance">
                        <i class="fas fa-money-bill"></i>
                         <strong>Expenses</strong>
                    </a>
                </li>

                <!-- <li class="nav-item active">
                    <a class="nav-link" href="/billing">
                        <i class="fas fa-chart-pie"></i> 
                        Report
                    </a>
                </li> -->

            <?php endif; ?>

            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="/logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout</a>
            </li>


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

            <!-- Sidebar Message -->
</ul>