<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('index') }}">
                    <i class="fa fa-list"></i> <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('job.index') }}">
                    <i class="glyphicon glyphicon-lock"></i> <span>Job</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user-circle"></i> <span>User</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('user')}}"><i class="glyphicon glyphicon-user"></i> <span>List User</span></a></li>
                    <li><a href="{{ route('mailDomain')}}"><i class="fa fa-envelope"></i> <span>Mail Domain</span></a></li>
                </ul>
            </li>
            @if (Auth::guard('admin')->user()->roles == \App\Utils\Constant::USER_ROLE_SUPER_ADMIN)
            <li>
                <a href="{{ route('accounting') }}">
                    <i class="fa fa-usd"></i> <span>Accounting</span>
                </a>
            </li>
            <li>
                <a href="{{ route('userExpense') }}">
                    <i class="fa fa-money"></i> <span>User Expense</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('scene') }}">
                    <i class="fa fa-desktop"></i> <span>Scene List</span>
                </a>
            </li>
            <li>
                <a href="{{ route('swpt') }}">
                    <i class="fa fa-laptop"></i> <span>Machine Type</span>
                </a>
            </li>
            @if (Auth::guard('admin')->user()->roles == \App\Utils\Constant::USER_ROLE_SUPER_ADMIN)
            <li>
                <a href="{{ route('systemEnv') }}">
                    <i class="fa fa-cog"></i> <span>System ENV</span>
                </a>
            </li>
            @endif

            @if (Auth::guard('admin')->user()->roles == \App\Utils\Constant::USER_ROLE_SUPER_ADMIN)
            <li>
                <a href="{{ route('engineVersion') }}">
                    <i class="fa fa-cog"></i> <span>Engine Version</span>
                </a>
            </li>
            @endif
             {{-- <!-- <li>
                <a href="{{ route('utm') }}">
                    <i class="fa fa-google"></i> <span>UTM List</span>
                </a>
            </li>  --> --}}


            <li>
                <a href="{{ route('supported-software.index') }}">
                        <i class="fa fa-snowflake-o"></i><span>Supported Software</span>
                </a>
            </li>

            <li>
                <a href="{{ route('imageServers') }}">
                    <i class="fa fa-server"></i> <span>Image Servers</span>
                </a>
            </li>
            @if (Auth::guard('admin')->user()->roles == \App\Utils\Constant::USER_ROLE_SUPER_ADMIN)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-ticket"></i> <span>Promotions</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('promotion.coupon.index') }}"><i class="fa fa-tags"></i> <span>Coupons</span></a></li>
                    <li><a href="{{ route('promotion.gift.index') }}"><i class="fa fa-gift"></i> <span>Gifts</span></a></li>
                    <li><a href="{{ route('promotion.link_affiliate.index') }}"><i class="fa fa-link"></i> <span>Link Affiliate</span></a></li>
                    <li><a href="{{ route('groupDiscount') }}"><i class="fa fa-tag"></i> <span>Discount</span></a></li>         
                </ul>
            </li>
            <li>
                <a href="{{ route('user.payment') }}">
                    <i class="fa fa-history"></i> <span>Payment History</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
</aside>