<?php
$base_url = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath(__DIR__ . '/../')) . '/';

echo '<div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Home</div>
                            <a class="nav-link" href="' . $base_url . 'html/dashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>

                            <!-- Human Resources -->
                            <div class="sb-sidenav-menu-heading">HR</div>
                            <a class="nav-link" href="' . $base_url . 'html/employees.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                Employees
                            </a>

                            <a class="nav-link" href="' . $base_url . 'html/departments.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                                Departments
                            </a>

                            <a class="nav-link" href="' . $base_url . 'html/jobs.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                                Jobs
                            </a>

                            <a class="nav-link" href="' . $base_url . 'html/locations.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-location-dot"></i></div>
                                Locations
                            </a>
                            
                            <a class="nav-link" href="' . $base_url . 'html/countries.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-flag"></i></div>
                                Countries
                            </a>
                            
                            <a class="nav-link" href="' . $base_url . 'html/regions.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-globe"></i></div>
                                Regions
                            </a>

                            <!-- Order Entries -->
                            <div class="sb-sidenav-menu-heading">OE</div>

                            <a class="nav-link" href="' . $base_url . 'html/customers.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users-viewfinder"></i></div>
                                Customers
                            </a>

                            <a class="nav-link" href="' . $base_url . 'html/warehouses.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-warehouse"></i></div>
                                Warehouses
                            </a>

                            <a class="nav-link" href="' . $base_url . 'html/categories.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table-list"></i></div>
                                Categories
                            </a>

                            <a class="nav-link" href="' . $base_url . 'html/products.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-boxes-stacked"></i></div>
                                Products
                            </a>

                            <a class="nav-link" href="' . $base_url . 'html/orders.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-cart-arrow-down"></i></div>
                                Orders
                            </a>
                        </div>
                    </div>';
                    echo '<div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>';
                        echo htmlspecialchars($_SESSION['username']);
                    echo '</div>
                </nav>
            </div>';
?>