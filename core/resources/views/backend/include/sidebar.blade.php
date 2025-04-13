<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">

        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="@if (is_active(Route::currentRouteName(), 'dash')) subdrop active @endif">
                                <i data-feather="grid"></i><span>Dashboard</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">
                                @if (can_view(route('dash.home')))
                                    <li>
                                        <a href="{{ route('dash.home') }}"
                                            class="@if (Route::currentRouteName() == 'dash.home') subdrop active @endif">
                                            Admin Dashboard
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>


                    </ul>
                </li>

                <li class="submenu-open">
                    <h6 class="submenu-hdr">Blog Management</h6>
                    <ul>
                        <!--Blog menu -->
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'blogs')) subdrop active @endif">
                                <i data-feather="box"></i><span>Blogs</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('blogs.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'blogs.index') subdrop active @endif"
                                            href="{{ route('blogs.index') }}">Blogs
                                            List</a>
                                    </li>
                                @endif


                                @if (can_view(route('blogs.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'blogs.create') subdrop active @endif"
                                            href="{{ route('blogs.create') }}">Create blog</a>
                                    </li>
                                @endif

                                {{-- @if (can_view(route('blogs.bundle.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'blogs.bundle.create') subdrop active @endif"
                                            href="{{ route('blogs.bundle.create') }}">Create Bundle Blog</a>
                                    </li>
                                @endif

                                @if (can_view(route('sales.payments')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'sales.payments') subdrop active @endif"
                                            href="{{ route('sales.payments') }}">Sales Report</a>
                                    </li>
                                @endif --}}

                            </ul>
                        </li>

                        <!--Blog menu end-->


                        {{-- <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'products')) subdrop active @endif">
                                <i data-feather="box"></i><span>Product</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('products.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'products.index') subdrop active @endif"
                                            href="{{ route('products.index') }}">Products
                                            List</a>
                                    </li>
                                @endif

                                @if (can_view(route('products.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'products.create') subdrop active @endif"
                                            href="{{ route('products.create') }}">Create Products</a>
                                    </li>
                                @endif

                                @if (can_view(route('products.bundle.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'products.bundle.create') subdrop active @endif"
                                            href="{{ route('products.bundle.create') }}">Create Bundle Products</a>
                                    </li>
                                @endif

                                @if (can_view(route('sales.payments')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'sales.payments') subdrop active @endif"
                                            href="{{ route('sales.payments') }}">Sales Report</a>
                                    </li>
                                @endif

                            </ul>
                        </li> --}}

                        {{-- Category menu --}}
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'categories')) subdrop active @endif">
                                <i data-feather="layers"></i><span>Category</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('categories.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'categories.index') subdrop active @endif"
                                            href="{{ route('categories.index') }}">List</a>
                                    </li>
                                @endif

                                @if (can_view(route('categories.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'categories.create') subdrop active @endif"
                                            href="{{ route('categories.create') }}">Create</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        {{-- tag menu --}}
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'tags')) subdrop active @endif">
                                <i data-feather="layers"></i><span>Tag</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('tags.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'tags.index') subdrop active @endif"
                                            href="{{ route('tags.index') }}">List</a>
                                    </li>
                                @endif

                                @if (can_view(route('tags.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'tags.create') subdrop active @endif"
                                            href="{{ route('tags.create') }}">Create</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        {{-- SubCategory menu --}}

                        {{-- <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'subcategories')) subdrop active @endif">
                                <i data-feather="layers"></i><span>Subcategory</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('subcategories.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'subcategories.index') subdrop active @endif"
                                            href="{{ route('subcategories.index') }}">List</a>
                                    </li>
                                @endif

                                @if (can_view(route('subcategories.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'subcategories.create') subdrop active @endif"
                                            href="{{ route('subcategories.create') }}">Create</a>
                                    </li>
                                @endif
                            </ul>
                        </li> --}}



                        {{-- <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'subjects')) subdrop active @endif">
                                <i data-feather="codepen"></i><span>Subjects</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('subjects.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'subjects.index') subdrop active @endif"
                                            href="{{ route('subjects.index') }}">List</a>
                                    </li>
                                @endif

                                @if (can_view(route('subjects.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'subjects.create') subdrop active @endif"
                                            href="{{ route('subjects.create') }}">Create</a>
                                    </li>
                                @endif
                            </ul>
                        </li> --}}

                        {{-- <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'publishers')) subdrop active @endif">
                                <i data-feather="file-text"></i><span>Publishers</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('publishers.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'publishers.index') subdrop active @endif"
                                            href="{{ route('publishers.index') }}">List</a>
                                    </li>
                                @endif

                                @if (can_view(route('publishers.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'publishers.create') subdrop active @endif"
                                            href="{{ route('publishers.create') }}">Create</a>
                                    </li>
                                @endif
                            </ul>
                        </li> --}}

                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'authors')) subdrop active @endif">
                                <i data-feather="users"></i><span>Authors</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('authors.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'authors.index') subdrop active @endif"
                                            href="{{ route('authors.index') }}">List</a>
                                    </li>
                                @endif

                                @if (can_view(route('authors.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'authors.create') subdrop active @endif"
                                            href="{{ route('authors.create') }}">Create</a>
                                    </li>
                                @endif
                            </ul>
                        </li>




                        {{-- @if (can_view(route('coupons.index')))
                            <li class="@if (Route::currentRouteName() == 'coupons.index') active @endif">
                                <a href="{{ route('coupons.index') }}"><i
                                        data-feather="bookmark"></i><span>Coupons</span></a>
                            </li>
                        @endif --}}



                    </ul>
                </li>

                <!--subscription module  menu start-->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Subscription Management</h6>
                    <ul>
                        <!--subscription package menu start-->
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'subscription-packages')) subdrop active @endif">
                                <i data-feather="box"></i><span>Packages</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('subscription-packages.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'subscription-packages.index') subdrop active @endif"
                                            href="{{ route('subscription-packages.index') }}">Packages
                                            List</a>
                                    </li>
                                @endif


                                @if (can_view(route('subscription-packages.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'subscription-packages.create') subdrop active @endif"
                                            href="{{ route('subscription-packages.create') }}">Create Package</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                        {{-- subscription order menu --}}
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'tags')) subdrop active @endif">
                                <i data-feather="dollar-sign"></i><span>Sales Order</span><span
                                    class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('subscription-orders.index.ajax')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'subscription-orders.index.ajax') subdrop active @endif"
                                            href="{{ route('subscription-orders.index.ajax') }}">All Order
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                        @if (can_view(route('subscriber.list')))
                            <li class="@if (Route::currentRouteName() == 'subscriber.list') active @endif">
                                <a href="{{ route('subscriber.list') }}"><i
                                        data-feather="user-check"></i><span>Subscriber</span></a>
                            </li>
                        @endif

                        @if (can_view(route('allcustomer.list')))
                            <li class="@if (Route::currentRouteName() == 'allcustomer.list') active @endif">
                                <a href="{{ route('allcustomer.list') }}"><i
                                        data-feather="users"></i><span>Customer</span></a>
                            </li>
                        @endif

                    </ul>
                </li>

                {{-- sales management menu  --}}

                {{-- <li class="submenu-open">
                    <h6 class="submenu-hdr">Sale Management</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="@if (is_active(Route::currentRouteName(), 'orders')) subdrop active @endif">
                                <i data-feather="dollar-sign"></i><span>Sales</span><span class="menu-arrow"></span>
                            </a>
                
                            <ul class="bintel-menu">
                                @if (can_view(route('orders.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'orders.index') subdrop active @endif"
                                            href="{{ route('orders.index') }}">All Sales</a>
                                    </li>
                                @endif
                
                                @if (can_view(route('subscription-orders.index.ajax')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'subscription-orders.index.ajax') subdrop active @endif"
                                            href="{{ route('subscription-orders.index.ajax') }}">All Subscription Orders</a>
                                    </li>
                                @endif
                
                                @if (can_view(route('reports.couriers')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'reports.couriers') subdrop active @endif"
                                            href="{{ route('reports.couriers') }}">Courier</a>
                                    </li>
                                @endif
                
                                @if (can_view(route('std.view')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'std.view') subdrop active @endif"
                                            href="{{ route('std.view') }}">Steedfast Auto</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                
                        <li class="submenu">
                            <a href="javascript:void(0);" class="@if (is_active(Route::currentRouteName(), 'pos')) subdrop active @endif">
                                <i data-feather="shopping-cart"></i><span>POS</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">
                                @if (can_view(route('pos.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'pos.index') subdrop active @endif"
                                            href="{{ route('pos.index') }}">Add Sale</a>
                                    </li>
                                @endif
                
                                @if (can_view(route('subscription-orders.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'subscription-orders.index') subdrop active @endif"
                                            href="{{ route('subscription-orders.index') }}">Subscription Order</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                
                        <li class="submenu">
                            <a href="javascript:void(0);" class="@if (is_active(Route::currentRouteName(), 'orderstatuses')) subdrop active @endif">
                                <i data-feather="codepen"></i><span>Order Status</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">
                                @if (can_view(route('orderstatuses.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'orderstatuses.index') subdrop active @endif"
                                            href="{{ route('orderstatuses.index') }}">List</a>
                                    </li>
                                @endif
                
                                @if (can_view(route('orderstatuses.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'orderstatuses.create') subdrop active @endif"
                                            href="{{ route('orderstatuses.create') }}">Create</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                
                        <li class="submenu">
                            <a href="javascript:void(0);" class="@if (is_active(Route::currentRouteName(), 'couriers')) subdrop active @endif">
                                <i data-feather="truck"></i><span>Courier</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">
                                @if (can_view(route('couriers.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'couriers.create') subdrop active @endif"
                                            href="{{ route('couriers.create') }}">Create</a>
                                    </li>
                                @endif
                
                                @if (can_view(route('couriers.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'couriers.index') subdrop active @endif"
                                            href="{{ route('couriers.index') }}">List</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                
                        <li class="submenu">
                            <a href="javascript:void(0);" class="@if (is_active(Route::currentRouteName(), 'paymentmethod')) subdrop active @endif">
                                <i data-feather="credit-card"></i><span>Payment Method</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">
                                @if (can_view(route('paymentmethod.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'paymentmethod.create') subdrop active @endif"
                                            href="{{ route('paymentmethod.create') }}">Create</a>
                                    </li>
                                @endif
                
                                @if (can_view(route('paymentmethod.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'paymentmethod.index') subdrop active @endif"
                                            href="{{ route('paymentmethod.index') }}">List</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </li> --}}


                {{-- <li class="submenu-open">
                    <h6 class="submenu-hdr">Offer Management</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'campaigns')) subdrop active @endif">
                                <i data-feather="gift"></i><span>Campaign</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('campaigns.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'campaigns.index') subdrop active @endif"
                                            href="{{ route('campaigns.index') }}">List</a>
                                    </li>
                                @endif

                                @if (can_view(route('campaigns.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'campaigns.create') subdrop active @endif"
                                            href="{{ route('campaigns.create') }}">Create</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="@if (is_active(Route::currentRouteName(), 'coupons')) subdrop active @endif">
                                <i data-feather="tag"></i><span>Coupons </span><span class="menu-arrow"></span>
                            </a>
                            <ul class="bintel-menu">

                                @if (can_view(route('coupons.index')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'coupons.index') subdrop active @endif"
                                            href="{{ route('coupons.index') }}">List</a>
                                    </li>
                                @endif

                                @if (can_view(route('coupons.create')))
                                    <li>
                                        <a class="@if (Route::currentRouteName() == 'coupons.create') subdrop active @endif"
                                            href="{{ route('coupons.create') }}">Create</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                    </ul>
                </li> --}}

                <!--<li class="submenu-open">-->
                <!--    <h6 class="submenu-hdr">Section Management</h6>-->
                <!--    <ul>-->
                <!--        @if (can_view(route('sliders.index')))
-->
                <!--            <li class="@if (Route::currentRouteName() == 'sliders.index') active @endif">-->
                <!--                <a href="{{ route('sliders.index') }}"><i-->
                <!--                        data-feather="monitor"></i><span>Slider</span></a>-->
                <!--            </li>-->
                <!--
@endif-->
                <!--        @if (can_view(route('sliders.sub-index')))
-->
                <!--            <li class="@if (Route::currentRouteName() == 'sliders.sub-index') active @endif">-->
                <!--                <a href="{{ route('sliders.sub-index') }}"><i data-feather="monitor"></i><span>Sub-->
                <!--                        Slider</span></a>-->
                <!--            </li>-->
                <!--
@endif-->

                        @if (can_view(route('homecart1.index')))

                            <li class="@if (Route::currentRouteName() == 'homecart1.index') active @endif">
                                <a href="{{ route('homecart1.index') }}"><i data-feather="monitor"></i><span>Popup
                                        Banner</span></a>
                            </li>
                
                        @endif



                    @if (can_view(route('homecart1.index2')))

                       <li class="@if (Route::currentRouteName() == 'homecart1.index2') active @endif">
                           <a href="{{ route('homecart1.index2') }}"><i data-feather="monitor"></i><span>Ads Banner</span></a>
                       </li>
              
                    @endif
                    
                <!--        <li class="submenu">-->
                <!--            <a href="javascript:void(0);"-->
                <!--                class="@if (is_active(Route::currentRouteName(), 'sections'))
subdrop active
@endif">-->
                <!--                <i data-feather="book"></i><span>-->
                <!--                    <Section>Product Section</Section>-->
                <!--                </span><span class="menu-arrow"></span>-->
                <!--            </a>-->
                <!--            <ul class="bintel-menu">-->

                <!--                @if (can_view(route('sections.index')))
-->
                <!--                    <li>-->
                <!--                        <a class="@if (Route::currentRouteName() == 'sections.index') subdrop active @endif"-->
                <!--                            href="{{ route('sections.index') }}">All Section</a>-->
                <!--                    </li>-->
                <!--
@endif-->


                <!--            </ul>-->
                <!--        </li>-->

                <!--        @if (can_view(route('home-category.index')))
-->
                <!--            <li class="@if (Route::currentRouteName() == 'home-category.index') active @endif">-->
                <!--                <a href="{{ route('home-category.index') }}"><i-->
                <!--                        data-feather="monitor"></i><span>Static Section</span></a>-->
                <!--            </li>-->
                <!--
@endif-->


                <!--    </ul>-->
                <!--</li>-->




                <!--<li class="submenu-open">-->
                <!--    <h6 class="submenu-hdr">Customer Management</h6>-->
                <!--    <ul>-->
                <!--        @if (can_view(route('users.customer.index')))
-->
                <!--            <li class="@if (Route::currentRouteName() == 'users.customer.index') active @endif">-->
                <!--                <a href="{{ route('users.customer.index') }}"><i-->
                <!--                        data-feather="user"></i><span>Customers</span></a>-->
                <!--            </li>-->
                <!--
@endif-->
                <!--        {{-- @if (can_view(route('users.shop.index')))-->
                <!--            <li class="@if (Route::currentRouteName() == 'users.shop.index') active @endif">-->
                <!--                <a href="{{ route('users.shop.index') }}"><i-->
                <!--                        data-feather="home"></i><span>Stores</span></a>-->
                <!--            </li>-->
                <!--        @endif --}}-->
                <!--    </ul>-->
                <!--</li>-->

                <li class="submenu-open">
                    <h6 class="submenu-hdr">Super Admins</h6>
                    <ul class="bintel-menu">
                        @if (can_view(route('user.list')))
                            <li class="@if (Route::currentRouteName() == 'user.list') active @endif">
                                <a href="{{ route('user.list') }}"><i
                                        data-feather="user-check"></i><span>Users</span></a>
                            </li>
                        @endif
                        @if (can_view(route('role.list')))
                            <li class="@if (Route::currentRouteName() == 'role.list') active @endif">
                                <a href="{{ route('role.list') }}"><i
                                        data-feather="shield"></i><span>Roles</span></a>
                            </li>
                        @endif
                        @if (can_view(route('permission.list')))
                            <li class="@if (Route::currentRouteName() == 'permission.list') active @endif">
                                <a href="{{ route('permission.list') }}"><i
                                        data-feather="shield"></i><span>Permissions</span></a>
                            </li>
                        @endif

                        @if (can_view(route('firewall.list')))
                            <li class="@if (Route::currentRouteName() == 'firewall.list') active @endif">
                                <a href="{{ route('firewall.list') }}"><i
                                        data-feather="shield"></i><span>Firewall</span></a>
                            </li>
                        @endif

                    </ul>
                </li>

                {{-- check list --}}

                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        <!--<li class="submenu">-->

                        <!--    <a href="javascript:void(0);"-->
                        <!--        class="@if (is_active(Route::currentRouteName(), 'orderstatuses') || is_active(Route::currentRouteName(), 'paymentmethod'))
subdrop active
@endif">-->
                        <!--        <i data-feather="settings"></i><span>General-->
                        <!--            Settings</span><span class="menu-arrow"></span>-->
                        <!--    </a>-->


                        <!--    <ul class="bintel-menu">-->

                        <!--        @if (can_view(route('coupons.index')))
-->
                        <!--            <li>-->
                        <!--                <a class="@if (Route::currentRouteName() == 'orderstatuses.index') subdrop active @endif"-->
                        <!--                    href="{{ route('orderstatuses.index') }}">Order status</a>-->
                        <!--            </li>-->

                        <!--            <li>-->
                        <!--                <a class="@if (Route::currentRouteName() == 'paymentmethod.index') subdrop active @endif"-->
                        <!--                    href="{{ route('paymentmethod.index') }}">Payment Methods</a>-->
                        <!--            </li>-->
                        <!--
@endif-->

                        <!--    </ul>-->
                        <!--</li>-->



                        @if (can_view(route('settings.website')))
                            <li class="@if (Route::currentRouteName() == 'settings.website') active @endif">
                                <a href="{{ route('settings.website') }}"><i data-feather="globe"></i><span>Website
                                        Settings</span></a>
                            </li>
                        @endif

                    </ul>
                </li>





                {{-- 
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Section</h6>
                    <ul>
                        @if (can_view(route('home-category.index')))
                            <li class="@if (Route::currentRouteName() == 'home-category.index') active @endif">
                                <a href="{{ route('home-category.index') }}"><i
                                        data-feather="monitor"></i><span>Static Section</span></a>
                            </li>
                        @endif

                    </ul>
                </li> --}}

                <li class="submenu-open">
                    <h6 class="submenu-hdr">Reviews</h6>
                    <ul>
                        {{-- <li class="@if (Route::currentRouteName() == 'returns.index') active @endif">
                            <a href="{{ route('returns.index') }}">
                                <i data-feather="monitor"></i><span>List</span>
                            </a>
                        </li> --}}
                        @if (can_view(route('review.index')))
                            <li class="@if (Route::currentRouteName() == 'review.index') active @endif">
                                <a href="{{ route('review.index') }}">
                                    <i data-feather="message-square"></i><span>Reviews</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <!--<li class="submenu-open">-->
                <!--    <h6 class="submenu-hdr">Menu Management</h6>-->
                <!--    <ul>-->
                <!--        @if (can_view(route('menus.index')))
-->
                <!--            <li class="@if (Route::currentRouteName() == 'menus.index') active @endif">-->
                <!--                <a href="{{ route('menus.index') }}"><i-->
                <!--                        data-feather="monitor"></i><span>List</span></a>-->
                <!--            </li>-->
                <!--
@endif-->
                <!--        @if (can_view(route('menus.create')))
-->
                <!--            <li class="@if (Route::currentRouteName() == 'menus.create') active @endif">-->
                <!--                <a href="{{ route('menus.create') }}"><i-->
                <!--                        data-feather="monitor"></i><span>Create</span></a>-->
                <!--            </li>-->
                <!--
@endif-->





                <!--    </ul>-->
                <!--</li>-->

                <!--<li class="submenu-open">-->
                <!--    <h6 class="submenu-hdr">Geolocation</h6>-->
                <!--    <ul>-->
                <!--        @if (can_view(route('country.index')))
-->
                <!--            <li class="@if (Route::currentRouteName() == 'country.index') active @endif">-->
                <!--                <a href="{{ route('country.index') }}"><i data-feather="list"></i><span>Country-->
                <!--                        List</span></a>-->
                <!--            </li>-->
                <!--            <li class="@if (Route::currentRouteName() == 'country.city') active @endif">-->
                <!--                <a href="{{ route('country.city') }}"><i data-feather="list"></i><span>City-->
                <!--                        List</span></a>-->
                <!--            </li>-->
                <!--            <li class="@if (Route::currentRouteName() == 'country.city.upazila') active @endif">-->
                <!--                <a href="{{ route('country.city.upazila') }}"><i-->
                <!--                        data-feather="list"></i><span>Upazila List</span></a>-->
                <!--            </li>-->
                <!--
@endif-->


                <!--    </ul>-->
                <!--</li>-->

                <li class="submenu-open">
                    <h6 class="submenu-hdr"><span class="text-danger fw-bold">{{ get_option('panel_name') }}</span>
                        v{{ get_option('panel_version') }}</h6>
                </li>

            </ul>
        </div>
    </div>
</div>
