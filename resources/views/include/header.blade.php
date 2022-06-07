<header class="main-header">
    <a href="#" class="logo">
        <span class="logo-mini"><b></b>3S</span>
        <span class="logo-lg"><b>3S Cloud</b> Admin</span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li id="header-notif-list">
                    <a href="#" data-toggle="tooltip" data-placement="bottom" title="Mark as Read">
                        <i class="fa fa-bell"></i>
                        <span class="label label-danger mark-notif-unread" style="display: none;">&nbsp;</span>
                    </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ secure_asset('/dist/img/avatar5.png') }}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ auth()->guard('admin')->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ secure_asset('/dist/img/avatar5.png') }}" class="img-circle" alt="User Image">

                            <p>
                                {{ auth()->guard('admin')->user()->name }}
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/" class="btn btn-default btn-flat">Setting</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('admin.logout') }}" class="btn btn-default btn-flat">Logout</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>