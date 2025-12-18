<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'لوحة التحكم') - مجموعة الأسكلة</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 0.5rem;
            margin: 0.25rem 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .navbar-brand {
            font-weight: bold;
            color: #667eea !important;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        .btn-group .btn {
            margin-right: 0.25rem;
        }
        .badge {
            font-size: 0.75em;
        }
        .progress {
            height: 0.5rem;
        }
        .text-primary {
            color: #667eea !important;
        }
        .bg-primary {
            background-color: #667eea !important;
        }
        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
        }
        .btn-primary:hover {
            background-color: #5a6fd8;
            border-color: #5a6fd8;
        }
        .btn-outline-primary {
            color: #667eea;
            border-color: #667eea;
        }
        .btn-outline-primary:hover {
            background-color: #667eea;
            border-color: #667eea;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/images/logo/light.png') }}" alt="الأسكلة" class="img-fluid" style="max-height: 60px;">
                        <h5 class="text-white mt-2">لوحة التحكم</h5>
                    </div>
                    
                    <!-- Navigation -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" 
                               href="{{ route('dashboard.index') }}">
                                <i class="fas fa-tachometer-alt ml-2"></i>
                                الرئيسية
                            </a>
                        </li>
                        
                        <!-- Shipments -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.shipments.*') ? 'active' : '' }}" 
                               href="{{ route('dashboard.shipments.index') }}">
                                <i class="fas fa-shipping-fast ml-2"></i>
                                الشحنات
                            </a>
                        </li>
                        
                        <!-- Flights -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.flights.*') ? 'active' : '' }}" 
                               href="{{ route('dashboard.flights.index') }}">
                                <i class="fas fa-plane ml-2"></i>
                                الرحلات
                            </a>
                        </li>
                        
                        <!-- Bookings -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.bookings.*') ? 'active' : '' }}" 
                               href="{{ route('dashboard.bookings.index') }}">
                                <i class="fas fa-ticket-alt ml-2"></i>
                                الحجوزات
                            </a>
                        </li>
                        
                        <!-- Customers -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.customers.*') ? 'active' : '' }}" 
                               href="{{ route('dashboard.customers.index') }}">
                                <i class="fas fa-users ml-2"></i>
                                العملاء
                            </a>
                        </li>
                        
                        <!-- Branches -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.branches.*') ? 'active' : '' }}" 
                               href="{{ route('dashboard.branches.index') }}">
                                <i class="fas fa-building ml-2"></i>
                                الفروع
                            </a>
                        </li>
                        
                        <!-- Users -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.users.*') ? 'active' : '' }}" 
                               href="{{ route('dashboard.users.index') }}">
                                <i class="fas fa-user-cog ml-2"></i>
                                المستخدمين
                            </a>
                        </li>
                        
                        <!-- Reports -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.reports.*') ? 'active' : '' }}" 
                               href="{{ route('dashboard.reports.index') }}">
                                <i class="fas fa-chart-bar ml-2"></i>
                                التقارير
                            </a>
                        </li>
                        
                        <!-- Settings -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.settings.*') ? 'active' : '' }}" 
                               href="{{ route('dashboard.settings.index') }}">
                                <i class="fas fa-cog ml-2"></i>
                                الإعدادات
                            </a>
                        </li>
                    </ul>
                    
                    <!-- User Info -->
                    <div class="mt-auto pt-3">
                        <div class="card bg-transparent border-0">
                            <div class="card-body text-white">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-circle fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                        <small class="text-white-50">{{ auth()->user()->email }}</small>
                                    </div>
                                </div>
                                <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
                                <div class="d-grid">
                                    <a href="{{ route('dashboard.profile') }}" class="btn btn-outline-light btn-sm mb-2">
                                        <i class="fas fa-user ml-2"></i>
                                        الملف الشخصي
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="fas fa-sign-out-alt ml-2"></i>
                                            تسجيل الخروج
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top Navigation -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2">@yield('title', 'لوحة التحكم')</h1>
                        <p class="text-muted mb-0">@yield('subtitle', 'مرحباً بك في لوحة تحكم مجموعة الأسكلة')</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download ml-2"></i>
                                تصدير
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-print ml-2"></i>
                                طباعة
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus ml-2"></i>
                            إضافة جديد
                        </button>
                    </div>
                </div>
                
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle ml-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle ml-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle ml-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle ml-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Confirm delete actions
        function confirmDelete(message = 'هل أنت متأكد من الحذف؟') {
            return confirm(message);
        }
        
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }
    </script>
    
    @stack('scripts')
</body>
</html>
