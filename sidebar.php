
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-success elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="assets/logo.jpg" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo $_SESSION["user_type"] == 'Superadmin' ? 'Administrator' : 'School Portal';?></span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar  flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo $title == 'Dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard</p>
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a href="school_year.php" class="nav-link <?php echo $title == 'School Year' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p> School Year</p>
                    </a>
                </li> -->

                
                <?php if ($_SESSION["user_type"] == "Superadmin") {?>
                    <li class="nav-item">
                        <a href="sections.php" class="nav-link <?php echo $title == 'Sections' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-th-large"></i>
                            <p> Sections</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="instructors.php" class="nav-link <?php echo $title == 'Instructors' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p> Instructors</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="students.php" class="nav-link <?php echo $title == 'Students' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p> Students</p>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item menu-open">
                    <a href="#" class="nav-link <?php echo $title == 'Rooms' || $title == 'Lab Reports' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Laboratory
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="rooms.php" class="nav-link <?php echo $title == 'Rooms' ? 'active' : '' ?>">
                                <i class="fa fa-store-alt nav-icon"></i>
                                <p>Rooms</p>
                            </a>
                        </li>
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link <?php echo 
                                $title == 'Pending' || 
                                $title == 'Ongoing' || 
                                $title == 'Not Verified' || 
                                $title == 'Solved'|| 
                                $title == 'Archived' ? 'active' : '' ?>">
                                <i class="nav-icon far fa-comments"></i>
                                <p>
                                    Reports
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="pending.php" class="nav-link <?php echo $title == 'Pending' ? 'active' : '' ?>">
                                        <i class="fa fa-info-circle nav-icon"></i>
                                        <p>Pending</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="ongoing.php" class="nav-link <?php echo $title == 'Ongoing' ? 'active' : '' ?>">
                                        <i class="fa fa-history nav-icon"></i>
                                        <p>Ongoing</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="not_verified.php" class="nav-link <?php echo $title == 'Not Verified' ? 'active' : '' ?>">
                                        <i class="fa fa-times-circle nav-icon"></i>
                                        <p>Not Verified</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="solved.php" class="nav-link <?php echo $title == 'Solved' ? 'active' : '' ?>">
                                        <i class="fa fa-check-circle nav-icon"></i>
                                        <p>Solved</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="archived.php" class="nav-link <?php echo $title == 'Archived' ? 'active' : '' ?>">
                                        <i class="fa fa-archive nav-icon"></i>
                                        <p>Archived</p>
                                    </a>
                                </li>
                            </ul>
                        </li> 
                    </ul>
                </li> 

                <li class="nav-item menu-open">
                    <a href="#" class="nav-link <?php echo $title == 'Reports Category' || $title == 'Components' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if ($_SESSION["user_type"] == "Superadmin") {?>

                            <li class="nav-item">
                                <a href="reports_category.php" class="nav-link <?php echo $title == 'Reports Category' ? 'active' : '' ?>">
                                    <i class="fa fa-th-list nav-icon"></i>
                                    <p>Reports Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="components.php" class="nav-link <?php echo $title == 'Components' ? 'active' : '' ?>">
                                    <i class="fa fa-desktop nav-icon"></i>
                                    <p>PC Components</p>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link " data-toggle="modal" data-target="#profileModal">
                                <i class="fa fa-user-circle nav-icon"></i>
                                <p>Change Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt "></i>
                                <p> Logout</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>

    </div>

</aside>