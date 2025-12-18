<!DOCTYPE html>
<html lang="en">

<head>

  @include('dashboard.layout.shared/title-meta', ['title' => $title])

  @include('dashboard.layout.shared/head-css')

</head>

<body class="bg-primary d-flex justify-content-center align-items-center min-vh-100 p-5">

 @yield('content')

 @include('dashboard.layout.shared/footer-scripts')

</body>

</html>
