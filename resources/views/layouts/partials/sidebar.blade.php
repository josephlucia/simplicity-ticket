<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="{{ url('/') }}"><i class="fa fa-support"></i></a>
        </li>
        <li>
            <a href="{{ url('/home') }}" data-toggle="tooltip" data-placement="right" title="Home">
                <i class="fa fa-home"></i>
            </a>
        </li>
        @if(auth()->user()->role != 'user')
        <li>
            <a href="{{ url('/tickets') }}" data-toggle="tooltip" data-placement="right" title="Tickets">
                <i class="fa fa-arrow-circle-o-up"></i>
            </a>
        </li>
        @endif
        @if(auth()->user()->role == 'admin')
        <li>
            <a href="{{ url('/settings') }}" data-toggle="tooltip" data-placement="right" title="Settings">
                <i class="fa fa-cogs"></i>
            </a>
        </li>
        @endif
    </ul>
</div>
