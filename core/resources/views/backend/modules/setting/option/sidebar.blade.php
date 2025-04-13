<div class="sidebars settings-sidebar theiaStickySidebar" id="sidebar2">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu5" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <ul>
                        <li class="submenu">
                            {{-- <a href="javascript:void(0);"  class="@if (is_active(Route::currentRouteName(), 'settings.website') || is_active(Route::currentRouteName(), 'settings.social') || is_active(Route::currentRouteName(), 'settings.core')) subdrop active @endif">
                                <i data-feather="airplay"></i>
                                <span>Website Settings</span>
                                <span class="menu-arrow"></span>
                            </a> --}}
                            <a href="javascript:void(0);"
                                class="{{ is_active(Route::currentRouteName(), 'settings.website') ||
                                is_active(Route::currentRouteName(), 'settings.social') ||
                                is_active(Route::currentRouteName(), 'settings.core')
                                    ? 'subdrop active'
                                    : '' }}">
                                <i data-feather="airplay"></i>
                                <span>Website Settings</span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul>
                                <li class="{{ Route::currentRouteName() == 'settings.website' ? 'active' : '' }}">
                                    <a href="{{ route('settings.website') }}">Company Settings</a>
                                </li>
                                <li class="{{ Route::currentRouteName() == 'settings.social' ? 'active' : '' }}">
                                    <a href="{{ route('settings.social') }}">Social Links</a>
                                </li>
                                <li class="{{ Route::currentRouteName() == 'settings.core' ? 'active' : '' }}">
                                    <a href="{{ route('settings.core') }}">Core</a>
                                </li>
                            </ul>
                        </li>


                        <!--<li class="submenu">-->
                        <!--    <a href="javascript:void(0);"><i data-feather="server"></i><span>Order</span><span-->
                        <!--            class="menu-arrow"></span></a>-->
                        <!--    <ul>-->
                        <!--        <li class="{{ Route::currentRouteName() == 'settings.order' ? 'active' : '' }}"><a-->
                        <!--                href="{{ route('settings.order') }}">Order</a></li>-->

                        <!--    </ul>-->
                        <!--</li>-->

                        <!--<li class="submenu">-->
                        <!--    <a href="javascript:void(0);"><i data-feather="server"></i><span>Email Settings</span><span-->
                        <!--            class="menu-arrow"></span></a>-->
                        <!--    <ul>-->
                        <!--        <li class="{{ Route::currentRouteName() == 'settings.email' ? 'active' : '' }}"><a-->
                        <!--                href="{{ route('settings.email') }}">Email</a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->


                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
