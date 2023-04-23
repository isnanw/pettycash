<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> | <?php echo $site_title; ?></title>
    <link href="<?php echo base_url().'assets/css/shared/iconly.css'?>" rel="stylesheet"/>

    <link href="<?php echo base_url().'assets/css/main/app.css'?>" rel="stylesheet"/>
    <link href="<?php echo base_url().'assets/css/main/app-dark.css'?>" rel="stylesheet"/>
    <link href="<?php echo base_url().'assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css'?>" rel="stylesheet"  type="text/css"/>
    <link href="<?php echo base_url().'assets/css/pages/datatables.css'?>" rel="stylesheet" type="text/css"/>


    <link href="<?php echo base_url().'assets/extensions/toastify-js/src/toastify.css'?>" rel="stylesheet"/>
    <link href="<?php echo base_url().'assets/css/select2.min.css'?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url().'assets/css/select2-bootstrap-5-theme.min.css'?>" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url().'assets/extensions/sweetalert2/sweetalert2.min.css'?>" rel="stylesheet"/>
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/logo/'.$site_favicon)?>" type="image/x-icon">
    <style>
        #hidden_div {
            display: none;
        }
    </style>
</head>

<body>
    <div id="app">
        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="<?php echo site_url('backend/dashboard');?>"><img src="<?php echo base_url().'assets/images/logo/'.$images;?>" alt="Logo" srcset="" style="width:auto; height:150px;"></a>
                        </div>
                        <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path><g transform="translate(-210 -1)"><path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path><circle cx="220.5" cy="11.5" r="4"></circle><path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path></g></g></svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" >
                                <label class="form-check-label" ></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path></svg>
                        </div>
                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class='sidebar-title'>Menu</li>

                        <li class="sidebar-item <?php echo $this->uri->segment(2) == 'dashboard' ? 'active': '' ?>">
                            <a href="<?php echo site_url('backend/dashboard');?>" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- MENU KHUSUS ADMIN -->
                        <?php if($this->session->userdata('access')=='1'):?>
                            <!-- <li class="sidebar-item <?php echo $this->uri->segment(2) == 'log' ? 'active': '' ?>">
                                <a href="<?php echo site_url('backend/log');?>" class='sidebar-link'>
                                    <i class="bi bi-activity"></i>
                                    <span>Log Aktivitas User</span>
                                </a>
                            </li> -->
                        <li class="sidebar-item <?php echo
                                    $this->uri->segment(2) == 'role' ? 'active': '' ||
                                    $this->uri->segment(2) == 'atasan' ? 'active': '' ||
                                    $this->uri->segment(2) == 'bagian' ? 'active': ''

                                    ?> has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Master Data</span>
                            </a>
                            <ul class="submenu <?php echo
                                        $this->uri->segment(2) == 'role' ? 'active': '' ||
                                        $this->uri->segment(2) == 'atasan' ? 'active': '' ||
                                        $this->uri->segment(2) == 'bagian' ? 'active': ''
                                    ?>">
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'role' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/role');?>">Role</a>
                                </li>
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'atasan' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/atasan');?>">Atasan</a>
                                </li>
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'bagian' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/bagian');?>">Bagian</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sidebar-item <?php echo
                                    $this->uri->segment(2) == 'pettycash' ? 'active': '' ||
                                    $this->uri->segment(2) == 'reimburse' ? 'active': '' ||
                                    $this->uri->segment(2) == 'pettycashmerah' ? 'active': ''

                                    ?> has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-cash"></i>
                                <span>Petty Cash</span>
                            </a>
                            <ul class="submenu <?php echo
                                        $this->uri->segment(2) == 'pettycash' ? 'active': '' ||
                                        $this->uri->segment(2) == 'reimburse' ? 'active': '' ||
                                        $this->uri->segment(2) == 'pettycashmerah' ? 'active': ''
                                    ?>">
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'pettycash' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/pettycash');?>">Bon Hijau</a>
                                </li>
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'pettycashmerah' || $this->uri->segment(2) == 'belanja' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/pettycashmerah');?>">Bon Merah</a>
                                </li>
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'reimburse' || $this->uri->segment(2) == 'reimburse' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/reimburse');?>">Reimburse</a>
                                </li>
                            </ul>
                        </li>

                        <!-- <li class="sidebar-item <?php echo $this->uri->segment(2) == 'backupdata' ? 'active': '' ?>">
                            <a href="<?php echo site_url('backend/backupdata');?>" class='sidebar-link'>
                                <i class="bi bi-download"></i>
                                <span>Backup Data</span>
                            </a>
                        </li> -->


                        <li class="sidebar-item <?php echo
                                    $this->uri->segment(2) == 'detail_website' ? 'active': '' ||
                                    $this->uri->segment(2) == 'profil' ? 'active': '' ||
                                    $this->uri->segment(2) == 'users' ? 'active': ''

                                    ?> has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-gear"></i>
                                <span>Settings</span>
                            </a>
                            <ul class="submenu <?php echo
                                    $this->uri->segment(2) == 'detail_website' ? 'active': '' ||
                                    $this->uri->segment(2) == 'profil' ? 'active': '' ||
                                    $this->uri->segment(2) == 'users' ? 'active': ''
                                    ?>">
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'detail_website' ? 'active': '' ?>">
                                    <a  href="<?php echo site_url('backend/detail_website');?>">Detail Website</a>
                                </li>

                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'profil' ? 'active': '' ?>">
                                    <a  href="<?php echo site_url('backend/profil');?>">Profil Setting</a>
                                </li>
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'users' ? 'active': '' ?>">
                                    <a  href="<?php echo site_url('backend/users');?>">Users Karyawan</a>
                                </li>

                            </ul>
                        </li>

                    <!-- END MENU KHUSUS ADMIN -->

                    <!-- MENU KHUSUS VENDOR -->
                    <?php elseif($this->session->userdata('access')=='2'):?>
                        <li class="sidebar-item <?php echo $this->uri->segment(2) == 'vendor' ? 'active': '' ?>">
                            <a href="<?php echo site_url('backend/vendor');?>" class='sidebar-link'>
                                <i class="bi bi-clipboard2-plus-fill"></i>
                                <span>Data Vendor</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php echo $this->uri->segment(2) == 'profil' ? 'active': '' ?>">
                            <a href="<?php echo site_url('backend/profil');?>" class='sidebar-link'>
                                <i class="bi bi-person"></i>
                                <span>Profil</span>
                            </a>
                        </li>
                    <!-- END MENU KHUSUS VENDOR -->

                    <!-- MENU KHUSUS KARYAWAN -->
                    <?php elseif($this->session->userdata('access')=='3'):?>
                        <li class="sidebar-item <?php echo
                                    $this->uri->segment(2) == 'pettycash' ? 'active': '' ||
                                    $this->uri->segment(2) == 'reimburse' ? 'active': '' ||
                                    $this->uri->segment(2) == 'pettycashmerah' ? 'active': ''

                                    ?> has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-cash"></i>
                                <span>Petty Cash</span>
                            </a>
                            <ul class="submenu <?php echo
                                        $this->uri->segment(2) == 'pettycash' ? 'active': '' ||
                                        $this->uri->segment(2) == 'reimburse' ? 'active': '' ||
                                        $this->uri->segment(2) == 'pettycashmerah' ? 'active': ''
                                    ?>">
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'pettycash' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/pettycash');?>">Bon Hijau</a>
                                </li>
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'pettycashmerah' || $this->uri->segment(2) == 'belanja' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/pettycashmerah');?>">Bon Merah</a>
                                </li>
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'reimburse' || $this->uri->segment(2) == 'reimburse' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/reimburse');?>">Reimburse</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item <?php echo $this->uri->segment(2) == 'profil' ? 'active': '' ?>">
                            <a href="<?php echo site_url('backend/profil');?>" class='sidebar-link'>
                                <i class="bi bi-person"></i>
                                <span>Profil</span>
                            </a>
                        </li>

                    <!-- MENU KHUSUS MANAJER -->
                    <?php elseif($this->session->userdata('access')=='4'):?>
                        <!-- <li class="sidebar-item <?php echo $this->uri->segment(2) == 'pettycashmanajer' ? 'active': '' ?>">
                            <a href="<?php echo site_url('backend/pettycashmanajer');?>" class='sidebar-link'>
                                <i class="bi bi-cash"></i>
                                <span>Approve Petty Cash</span>
                            </a>
                        </li> -->
                        <li class="sidebar-item <?php echo
                                    $this->uri->segment(2) == 'pettycashmanajer' ? 'active': '' ||
                                    $this->uri->segment(2) == 'reimburse' ? 'active': '' ||
                                    $this->uri->segment(2) == 'pettycashmerahmanajer' ? 'active': ''

                                    ?> has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-cash"></i>
                                <span>Petty Cash</span>
                            </a>
                            <ul class="submenu <?php echo
                                        $this->uri->segment(2) == 'pettycashmanajer' ? 'active': '' ||
                                        $this->uri->segment(2) == 'reimburse' ? 'active': '' ||
                                        $this->uri->segment(2) == 'pettycashmerahmanajer' ? 'active': ''
                                    ?>">
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'pettycashmanajer' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/pettycashmanajer');?>">Bon Hijau</a>
                                </li>
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'pettycashmerahmanajer' || $this->uri->segment(2) == 'belanja' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/pettycashmerahmanajer');?>">Bon Merah</a>
                                </li>
                                <li class="submenu-item <?php echo $this->uri->segment(2) == 'reimburse' || $this->uri->segment(2) == 'reimburse' ? 'active': '' ?>">
                                    <a href="<?php echo site_url('backend/reimburse');?>">Reimburse</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item <?php echo $this->uri->segment(2) == 'profil' ? 'active': '' ?>">
                            <a href="<?php echo site_url('backend/profil');?>" class='sidebar-link'>
                                <i class="bi bi-person"></i>
                                <span>Profil</span>
                            </a>
                        </li>
                        <!-- END MENU KHUSUS MANAJER -->

                        <!-- MENU KHUSUS DIREKTUR -->
                        <?php elseif($this->session->userdata('access')=='5'):?>
                            <!-- <li class="sidebar-item <?php echo $this->uri->segment(2) == 'pettycashdirektur' ? 'active': '' ?>">
                                <a href="<?php echo site_url('backend/pettycashdirektur');?>" class='sidebar-link'>
                                    <i class="bi bi-cash"></i>
                                    <span>Release Petty Cash</span>
                                </a>
                            </li> -->
                            <li class="sidebar-item <?php echo
                                    $this->uri->segment(2) == 'pettycashdirektur' ? 'active': '' ||
                                    $this->uri->segment(2) == 'reimburse' ? 'active': '' ||
                                    $this->uri->segment(2) == 'pettycashmerahdirektur' ? 'active': ''

                                    ?> has-sub">
                                <a href="#" class='sidebar-link'>
                                    <i class="bi bi-cash"></i>
                                    <span>Petty Cash</span>
                                </a>
                                <ul class="submenu <?php echo
                                            $this->uri->segment(2) == 'pettycashdirektur' ? 'active': '' ||
                                            $this->uri->segment(2) == 'reimburse' ? 'active': '' ||
                                            $this->uri->segment(2) == 'pettycashmerahdirektur' ? 'active': ''
                                        ?>">
                                    <li class="submenu-item <?php echo $this->uri->segment(2) == 'pettycashdirektur' ? 'active': '' ?>">
                                        <a href="<?php echo site_url('backend/pettycashdirektur');?>">Bon Hijau</a>
                                    </li>
                                    <li class="submenu-item <?php echo $this->uri->segment(2) == 'pettycashmerahdirektur' || $this->uri->segment(2) == 'belanja' ? 'active': '' ?>">
                                        <a href="<?php echo site_url('backend/pettycashmerahdirektur');?>">Bon Merah</a>
                                    </li>
                                    <li class="submenu-item <?php echo $this->uri->segment(2) == 'reimburse' || $this->uri->segment(2) == 'reimburse' ? 'active': '' ?>">
                                        <a href="<?php echo site_url('backend/reimburse');?>">Reimburse</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item <?php echo $this->uri->segment(2) == 'profil' ? 'active': '' ?>">
                                <a href="<?php echo site_url('backend/profil');?>" class='sidebar-link'>
                                    <i class="bi bi-person"></i>
                                    <span>Profil</span>
                                </a>
                            </li>
                        <!-- END MENU KHUSUS DIREKTUR -->
                    <?php else:?>

                    <?php endif;?>

                        <?php if($this->session->userdata('level') == "1"){    ?>
                            <li class="sidebar-item">
                                <a href="<?php echo site_url('logout');?>" class='sidebar-link'>
                                    <i class="bi bi-arrow-left"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="sidebar-item">
                                <a href="<?php echo site_url('logout_user');?>" class='sidebar-link'>
                                    <i class="bi bi-arrow-left"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        <?php } ?>


                    </ul>
                </div>
            </div>
        </div>
        <div id="main">
        <!-- NAVBAR -->
    <header class='mb-3'>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class='burger-btn d-block d-xl-none'>
                            <i class='bi bi-justify fs-3'></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">

                            </ul>
                            <div class="dropdown">


                                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="user-menu d-flex">
                                            <div class="user-name text-end me-3">
                                                <h6 class="mb-0 text-gray-600"><?php echo $this->session->userdata('name');?></h6>
                                                <p class="mb-0 text-sm text-gray-600"><?php echo $this->session->userdata('role');?></p>
                                            </div>
                                            <div class="user-img d-flex align-items-center">
                                                <div class="avatar avatar-md">
                                                    <img src="<?php echo base_url().'assets/images/profilusers/'.$this->session->userdata('user_photo');?>">
                                                </div>
                                            </div>
                                        </div>
                                    </a>






                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">

                                    <li><a class="dropdown-item" href="<?php echo site_url('backend/profil');?>"><i class="icon-mid bi bi-person me-2"></i> My Profile</a></li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <?php if($this->session->userdata('level') == "1"){    ?>
                                        <li><a class="dropdown-item" href="<?php echo site_url('logout');?>"><i
                                                class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a>
                                        </li>
                                    <?php } else { ?>
                                        <li><a class="dropdown-item" href="<?php echo site_url('logout_user');?>"><i
                                                class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a>
                                        </li>
                                    <?php } ?>

                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
<!-- NAVBAR -->

