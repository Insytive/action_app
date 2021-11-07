<div class="main-header">
    <div class="logo">
        <img src="/images/asa_icon.png" alt="Action SA" style="height: 50px; width: 50px;">
    </div>
    <div class="menu-toggle">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="d-flex align-items-center">
        <div class="search-bar">
            <form action="/system/admin/users" class="form-inline" style="width: 230px;">
                <input type="text" placeholder="Member Search" name="q">
                <button class="btn bg-transparent btn-icon p-0"><i class="search-icon text-muted i-Magnifi-Glass1"></i></button>
            </form>
        </div>
    </div>
    <div style="margin: auto"></div>
    <div class="header-part-right">
        <!-- Full screen toggle -->
        <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i>
        <!-- Notificaiton -->
        <div class="dropdown">
            <div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <!-- <span class="badge badge-primary">1</span> -->
                <i class="i-Bell text-muted header-icon"></i>
            </div>
            <!-- Notification dropdown -->
            <?php /*
            <div class="dropdown-menu dropdown-menu-right notification-dropdown rtl-ps-none"
                 aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">
                <div class="dropdown-item d-flex">
                    <div class="notification-icon">
                        <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                    </div>
                    <div class="notification-details flex-grow-1">
                        <p class="m-0 d-flex align-items-center">
                            <span>New message</span>
                            <span class="badge badge-pill badge-primary ml-1 mr-1">new</span>
                            <span class="flex-grow-1"></span>
                            <span class="text-small text-muted ml-auto">10 sec ago</span>
                        </p>
                        <p class="text-small text-muted m-0">James: Hey! are you busy?</p>
                    </div>
                </div>
            </div>
            */ ?>
        </div>
        <!-- Notificaiton End -->
        <!-- User avatar dropdown -->
        <div class="dropdown">
            <div class="user align-self-end">
                <i class="i-Male text-muted header-icon" role="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <div class="dropdown-header">
                        <i class="i-Lock-User mr-1"></i> <?php echo $this->Identity->get('first_name') .' '. $this->Identity->get('last_name'); ?>
                    </div>
                    <?= $this->Html->link(__('My Profile'), ['controller'=>'Users', 'action' => 'view', $this->Identity->get('id')], ['class' => 'dropdown-item']) ?>
                    <a class="dropdown-item" href="/system/admin/users/logout">Sign out</a>
                </div>
            </div>
        </div>
    </div>
</div>
