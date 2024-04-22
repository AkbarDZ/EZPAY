<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/') }}" class="nav-link">Transaction</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('logout') }}" class="nav-link">Logout</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <!-- PHP code to fetch total sales count for today -->
            <?php
                $salesCountToday = DB::select('CALL CountSalesToday()');
                $todaySalesCount = $salesCountToday[0]->today_sales_count;
            ?>
            <a href="#" class="nav-link">Total sales today : {{ $todaySalesCount }}</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->

 
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
