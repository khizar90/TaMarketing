<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a class="app-brand-link">

            <span class="app-brand-text demo menu-text fw-bold"><img src="/assets/img/App logo.png" alt=""></span>
        </a>

        {{-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a> --}}
    </div>
    <div class="brandborder">

    </div>

    {{-- <div class="menu-inner-shadow"></div> --}}




    <ul class="menu-inner py-1">
        <!-- Dashboards -->




        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Dashboard</span>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-') ? 'active' : '' }}">
            <a href="{{ route('dashboard-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Statistics">Statistics</div>
            </a>
        </li>


        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">User Managements</span>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-users') ? 'active' : '' }}">
            <a href="{{ route('dashboard-users') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="User">Users</div>
            </a>
        </li>

        <li class="menu-item {{ Request::url() == route('dashboard-verify-users') ? 'active' : '' }}">
            <a href="{{ route('dashboard-verify-users') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="User">Verifications Requests</div>
                @if($verify != 0)
                <div class="badge bg-danger rounded-pill ms-auto">{{$verify}}</div>
                @endif
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Order Managements</span>
        </li>

        <li class="menu-item {{ Request::url() == route('dashboard-order-' , 0) ? 'active' : '' }} ">
            <a href="{{ route('dashboard-order-', 0) }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Pending">Pending</div>
                @if($pending != 0)
                <div class="badge bg-danger rounded-pill ms-auto">{{$pending}}</div>
                @endif
            </a>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-order-' , 1) ? 'active' : '' }} ">
            <a href="{{ route('dashboard-order-', 1) }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Pending">Accepted</div>
                @if($accept != 0)
                <div class="badge bg-danger rounded-pill ms-auto">{{$accept}}</div>
                @endif
            </a>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-order-' , 2) ? 'active' : '' }} ">
            <a href="{{ route('dashboard-order-', 2) }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Pending">Started</div>
                @if($start != 0)
                <div class="badge bg-danger rounded-pill ms-auto">{{$start}}</div>
                @endif
            </a>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-order-' , 3) ? 'active' : '' }} ">
            <a href="{{ route('dashboard-order-', 3) }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Pending">Delivered</div>
                @if($delivered != 0)
                <div class="badge bg-danger rounded-pill ms-auto">{{$delivered}}</div>
                @endif
            </a>
        </li>

        <li class="menu-item {{ Request::url() == route('dashboard-order-' , 4) ? 'active' : '' }} ">
            <a href="{{ route('dashboard-order-', 4) }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Pending">Completed</div>

            </a>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-order-' , 5) ? 'active' : '' }} ">
            <a href="{{ route('dashboard-order-', 5) }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Pending">Canceled</div>

            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Order Request Form</span>
        </li>

        <li class="menu-item {{ Request::url() == route('dashboard-question-') ? 'active' : '' }}">
            <a href="{{ route('dashboard-question-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Questions">Questions</div>

            </a>
        </li>

      
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Help & Supports</span>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-faqs-') ? 'active' : '' }}">
            <a href="{{ route('dashboard-faqs-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="FAQ'S">FAQ'S</div>
            </a>
        </li>
    </ul>
</aside>
