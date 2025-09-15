<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="JobReels admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, JobReels admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>JobReels</title>
    <link rel="apple-touch-icon" href="{{url('/')}}/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/logo.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('/app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/vendors/css/extensions/dragula.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/pages/widgets.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/pages/dashboard-analytics.css">
    <!-- END: Page CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/style.css">
    <!-- END: Custom CSS-->
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css"
          rel="stylesheet"
    >
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/pages/dashboard-ecommerce.css">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.tutorialjinni.com/toastr.js/2.1.4/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.tutorialjinni.com/toastr.js/2.1.4/toastr.css">
    <!-- ApexCharts CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.29.0/dist/apexcharts.min.css">

<!-- ApexCharts JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.29.0/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <script src="https://cdn.tutorialjinni.com/toastr.js/2.1.4/toastr.min.js"></script>

    <style>
    .badge-circle
    {
    display: inline-flex !important;
        margin-left: 5px;
    }
    .singlecard
    {
    border-radius: 0.267rem;
    border: 1px solid #DFE3E7;
    background-color: #fafbfb;
    transition: none;
    }
    .singlehome{
        color: #5A8DEE !important;
    }
    .notification-icon {
        position: relative;
    }

    .notification-count {
        position: absolute;
        top: -10px; /* Adjust this value to control the vertical position */
        right: -8px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 4px 8px;
        font-size: 8px;
    }
    </style>
    @livewireStyles

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- BEGIN: Header-->
    <div class="header-navbar-shadow"></div>
    <nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="float-left mr-auto bookmark-wrapper d-flex align-items-center">
                        
                    </div>
                    <ul class="float-right nav navbar-nav">
                        <li class="dropdown dropdown-user nav-item">
                            <a class=" nav-link " href="{{url('/')}}/notifications"  style="padding: 0px;">
                                <div class="user-nav d-sm-flex">
                                    <span class="notification-icon">
                                        <i class="bx bx-bell" style="font-size:20px;"></i>
                                        <div class="notification-count"><livewire:notification-count />
                                        </div>
                                    </span>
                                </div>
                            </a>
                            {{-- <div class="pb-0 dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{url('/logout')}}"><i class="bx bx-power-off mr-50"></i> new</a>
                            </div> --}}
                        </li>
                    </ul>
                    
                    
                    <ul class="float-right nav navbar-nav">

                        <livewire:user-nav-menu />
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="flex-row nav navbar-nav">
                <li class="mr-auto nav-item"><a class="navbar-brand" href="{{url('/')}}/dashboard">
                        <div class="brand-logo">
                            <img src="{{url('/logo.png')}}" style='height:35px;'/>

                        </div>
                        <h2 class="mb-0 brand-text">Welcome</h2>
                    </a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            {{-- <ul class=" navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="singlecard d-flex align-items-center"><a href="{{url('/annoucement')}}"><i class="bx bx-bell singlehome"></i><span class="menu-title text-truncate" data-i18n="Dashboard">What to announce?</span></a>
                </li>
            </ul> --}}
            <ul class="mt-1 navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="singlecard d-flex align-items-center"><a href="{{url('dashboard')}}"><i class="bx bx-home-alt singlehome"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span></a>
                </li>
            </ul>
            <ul class="mt-1 navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="nav-item has-sub sidebar-group-active open"><a href="#"><i class="bx bx-desktop"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Job Seekers</span></a>
                    <ul class="menu-content">
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/freelancers/list"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Applicants List</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/freelancers/approval"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Approved</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/freelancers/pending-approval"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Pending</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="mt-1 navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="nav-item has-sub sidebar-group-active open"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Hirers</span></a>
                    <ul class="menu-content">
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/hirers/list"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Approved</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/hirers/subscribed"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Subscribed</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/hirers/unsubscribed"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Unsubscribed</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="mt-1 navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="nav-item has-sub sidebar-group-active open"><a href="#"><i class="bx bx-file"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Posts</span></a>
                    <ul class="menu-content">
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/list"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">All Posts</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/remote-worker"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Remote Workers Posts</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/admin"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Admin Posts</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/priority"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Priority Posts</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/flagged"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Flagged Posts</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
            {{-- <ul class="mt-1 navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="nav-item has-sub sidebar-group-active open"><a href=""><i class="bx bx-home-alt"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Adoption</span></a>
                    <ul class="menu-content">
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/adoptions/list"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Adoption Requests</span></a>
                        </li>
                    </ul>
                </li>
            </ul> --}}
            {{-- <ul class="mt-1 navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="nav-item has-sub sidebar-group-active open"><a href=""><i class="bx bx-home-alt"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Complaints</span></a>
                    <ul class="menu-content">
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/complaints/list"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">List</span></a>
                        </li>
                    </ul>
                </li>
            </ul> --}}
            <ul class="mt-1 navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="nav-item has-sub sidebar-group-active open"><a href="#"><i class="bx bx-lock"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Privacy and Security Settings</span></a>
                    <ul class="menu-content">
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/flagged"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">ALL Reports</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/solve/flagged"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Solved Reports</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/unsolve/flagged"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Unsolved Reports</span></a>
                        </li>
                        {{-- <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/flagged"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Blocked Hirers</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/flagged"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Blocked Remote Workers</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/posts/flagged"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">For Appeal</span></a>
                        </li> --}}
                    </ul>
                </li>
            </ul>
            {{-- <ul class="mt-1 navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="nav-item has-sub sidebar-group-active open"><a href="#"><i class="bx bx-badge-check"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Subscriptions</span></a>
                    <ul class="menu-content">
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/packages/list"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Plans and Packages</span></a></li>
                    </ul>
                </li>
            </ul> --}}
            {{-- <ul class="mt-1 navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
                <li class="nav-item has-sub sidebar-group-active open"><a href=""><i class="bx bx-home-alt"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Settings</span></a>
                    <ul class="menu-content">
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/breeds/list"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Breeds</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/colors/list"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Colors</span></a>
                        </li>
                        <li class=""><a class="d-flex align-items-center" href="{{url('/')}}/states/list"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">States</span></a>
                        </li>
                    </ul>
                </li>
            </ul> --}}
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    {{$slot}}
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        {{-- <p class="clearfix mb-0"><span class="float-left d-inline-block">2021 &copy; PIXINVENT</span><span class="float-right d-sm-inline-block d-none">Crafted with<i class="bx bxs-heart pink mx-50 font-small-3"></i>by<a class="text-uppercase" href="https://1.envato.market/pixinvent_portfolio" target="_blank">Pixinvent</a></span>
            <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="bx bx-up-arrow-alt"></i></button>
        </p> --}}
    </footer>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{url('/')}}/app-assets/vendors/js/vendors.min.js"></script>
    <script src="{{url('/')}}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="{{url('/')}}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="{{url('/')}}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{url('/')}}/app-assets/vendors/js/extensions/dragula.min.js"></script>
    <script src="{{url('/')}}/app-assets/vendors/js/extensions/swiper.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{url('/')}}/app-assets/js/core/app-menu.js"></script>
    <script src="{{url('/')}}/app-assets/js/core/app.js"></script>
    <script src="{{url('/')}}/app-assets/js/scripts/components.js"></script>
    <script src="{{url('/')}}/app-assets/js/scripts/footer.js"></script>
    <!-- END: Theme JS-->
      <!-- charts-->

      <!-- End charts-->
    <!-- BEGIN: Page JS-->
    <script src="{{url('/')}}/app-assets/js/scripts/pages/dashboard-analytics.js"></script>
    <!-- END: Page JS-->

    
<script type="text/javascript">

	var Toast = Swal.mixin({
		toast: true,
		position: 'top',
		showConfirmButton: false,
		timer: 3000
	});

	function toast_error(message){
		toastr.error(message)
	}

	function toast_success(message){
		toastr.success(message)
	}

	function toast_info(message){
		toastr.info(message)
	}
  
    </script>
  
    @livewireScripts
    <script src="{{url('/')}}/app-assets/js/notification-count.js"></script>
</body>
<!-- END: Body-->

</html>
