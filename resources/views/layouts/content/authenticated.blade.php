<div id="wrapper">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Topbar -->
    @include('layouts.partials.topbar')

    <!-- Content -->
    <div id="page-content-wrapper">
        @yield('content')
    </div>

</div>