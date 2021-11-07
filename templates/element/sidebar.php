<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">
        <ul class="navigation-left">
            <li class="nav-item">
                <a class="nav-item-hold" href="/system/admin/" >
                    <i class="nav-icon i-Bar-Chart"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <div class="triangle"></div>
            </li>
            <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3, 6])): ?>
            <li class="nav-item" data-item="users">
                <a class="nav-item-hold" href="javascript:">
                    <i class="nav-icon i-Administrator"></i>
                    <span class="nav-text">Members</span>
                </a>
                <div class="triangle"></div>
            </li>
            <?php endif; ?>

            <?php if (5 === $this->Identity->get('role_id') || (4 === $this->Identity->get('role_id') && 2 & $this->Identity->get('user_status'))): ?>
            <li class="nav-item">
                <a class="nav-item-hold" href="/system/admin/users">
                    <i class="nav-icon i-Administrator"></i>
                    <span class="nav-text">Members</span>
                </a>
                <div class="triangle"></div>
            </li>
            <?php endif; ?>

            <?php if (in_array($this->Identity->get('role_id'), [1, 2, 6])): ?>
            <li class="nav-item" data-item="statistics">
                <a class="nav-item-hold" href="javascript:">
                    <i class="nav-icon i-Bar-Chart-2"></i>
                    <span class="nav-text">Statistics</span>
                </a>
                <div class="triangle"></div>
            </li>
            <?php endif; ?>

            <?php if (in_array($this->Identity->get('role_id'), [1, 2])): ?>
            <li class="nav-item" data-item="charts">
                <a class="nav-item-hold" href="javascript:">
                    <i class="nav-icon i-Gear"></i>
                    <span class="nav-text">Options</span>
                </a>
                <div class="triangle"></div>
            </li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="sidebar-left-secondary rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">
        <ul class="childNav" data-parent="users">
            <?php if (in_array($this->Identity->get('role_id'), [1, 2])): ?>
            <li class="nav-item">
                <a href="/system/admin/badges">
                    <i class="nav-icon i-Medal"></i>
                    <span class="item-name">Badges</span>
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
                <a href="/system/admin/users">
                    <i class="nav-icon i-Medal"></i>
                    <span class="item-name">All Members</span>
                </a>
            </li>
            <?php if (in_array($this->Identity->get('role_id'), [1, 2])): ?>
            <li class="nav-item dropdown-sidemenu">
                <a href="javascript:">
                    <i class="nav-icon i-Edit-Map"></i>
                    <span class="item-name">Members by role</span>
                    <i class="dd-arrow i-Arrow-Down"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="/system/admin/users?role=4">
                            <i class="nav-icon i-Spot"></i>
                            <span class="item-name">Members/Volunteers</span>
                        </a>
                    </li>
                    <li>
                        <a href="/system/admin/users?role=1">
                            <i class="nav-icon i-Spot"></i>
                            <span class="item-name">Super Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="/system/admin/users?role=2">
                            <i class="nav-icon i-Spot"></i>
                            <span class="item-name">Administrators</span>
                        </a>
                    </li>
                    <li>
                        <a href="/system/admin/users?role=3">
                            <i class="nav-icon i-Spot"></i>
                            <span class="item-name">Data Capturer</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>

        <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
        <ul class="childNav" data-parent="charts">
            <li class="nav-item">
                <a href="/system/admin/branches">
                    <i class="nav-icon i-File-Clipboard-Text--Image"></i>
                    <span class="item-name">Constituencies</span>
                </a>
            </li>
            <li class="nav-item dropdown-sidemenu">
                <a href="javascript:">
                    <i class="nav-icon i-Edit-Map"></i>
                    <span class="item-name">Locations</span>
                    <i class="dd-arrow i-Arrow-Down"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="/system/admin/voting-stations">
                            <i class="nav-icon i-Spot"></i>
                            <span class="item-name">Voting Stations</span>
                        </a>
                    </li>
                    <li>
                        <a href="/system/admin/wards">
                            <i class="nav-icon i-Spot"></i>
                            <span class="item-name">Wards</span>
                        </a>
                    </li>
                    <li>
                        <a href="/system/admin/areas">
                            <i class="nav-icon i-Spot"></i>
                            <span class="item-name">Regions</span>
                        </a>
                    </li>
                    <li>
                        <a href="/system/admin/municipalities">
                            <i class="nav-icon i-Spot"></i>
                            <span class="item-name">Municipalities</span>
                        </a>
                    </li>
                    <li>
                        <a href="/system/admin/provinces">
                            <i class="nav-icon i-S"></i>
                            <span class="item-name">Provinces</span>
                        </a>
                    </li>
                </ul>
            </li>

            <?php if (in_array($this->Identity->get('role_id'), [1, 2])): ?>
            <li class="nav-item dropdown-sidemenu">
                <a href="">
                    <i class="nav-icon i-Gear"></i>
                    <span class="item-name">System</span>
                    <i class="dd-arrow i-Arrow-Down"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="/system/admin/roles">
                            <i class="nav-icon i-Double-Tap"></i>
                            <span class="item-name">Roles</span>
                        </a>
                    </li>

                    <li>
                        <a href="/system/admin/genders">
                            <i class="nav-icon i-MaleFemale"></i>
                            <span class="item-name">Genders</span>
                        </a>
                    </li>

                    <li>
                        <a href="/system/admin/settings">
                            <i class="nav-icon i-Gears-2"></i>
                            <span class="item-name">Settings</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
        <?php endif; ?>

        <?php if (in_array($this->Identity->get('role_id'), [1, 2, 6])): ?>
        <ul class="childNav" data-parent="statistics">
            <li class="nav-item">
                <a href="/system/admin/statistics/members/">
                    <i class="nav-icon i-Checked-User"></i>
                    <span class="item-name">Members</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="javascript:">
                    <i class="nav-icon i-Medal-2"></i>
                    <span class="item-name">Volunteers</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="javascript:">
                    <i class="nav-icon i-Data-Download"></i>
                    <span class="item-name">Export</span>
                </a>
            </li>

        </ul>
        <?php endif; ?>
    </div>
    <div class="sidebar-overlay"></div>
</div>
