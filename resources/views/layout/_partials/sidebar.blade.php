<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <ul>
        <li>
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title">
                    Dashboard 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{ route('dashboard') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Dashboard </div>
                    </a>
                </li>
                <li>
                    <a href="simple-menu-light-dashboard-overview-1.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Simple Menu </div>
                    </a>
                </li>
                <li>
                    <a href="top-menu-light-dashboard-overview-1.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Top Menu </div>
                    </a>
                </li>
            </ul>
        </li>
        
        <li>
            <a href="{{ route('reserve-vehicle') }}" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="car"></i> </div>
                <div class="side-menu__title"> Reserve Vehicle </div>
            </a>
        </li>
        <li>
            <a href="{{ route('my-reservation') }}" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="user-check"></i> </div>
                <div class="side-menu__title"> My Reservation </div>
            </a>
        </li>
        <li>
            <a href="{{ route('drivers-calendar') }}" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="calendar"></i> </div>
                <div class="side-menu__title"> Drivers Calendar </div>
            </a>
        </li>
        <li>
            <a href="{{ route('chat') }}" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="message-square"></i> </div>
                <div class="side-menu__title"> Chat </div>
            </a>
        </li>
        <li>
            <a href="{{ route('post') }}" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                <div class="side-menu__title"> Post </div>
            </a>
        </li>
        <li class="side-nav__devider my-6"></li>
        <li>
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                <div class="side-menu__title">
                    Position Management 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{ route('position-management') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Create Position </div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                <div class="side-menu__title">
                    Department Management 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{ route('department-management') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Create Department </div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                <div class="side-menu__title">
                    Vehicle Management 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{ route('vehicle-management') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Create Vehicle </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('list-request-reserve') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> List Request Reserve </div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                <div class="side-menu__title">
                    Reservation Type 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{ route('reservation-type') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Create Reservation Type </div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="qr-code"></i> </div>
                <div class="side-menu__title">
                    QR Code Management
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{ route('scan-qrcode') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Scan QR Code </div>
                    </a>
                </li>
                <li>
                    <a href="side-menu-light-add-product.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Add Product </div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            Products 
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="side-menu-light-product-list.html" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">Product List</div>
                            </a>
                        </li>
                        <li>
                            <a href="side-menu-light-product-grid.html" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">Product Grid</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            Transactions 
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="side-menu-light-transaction-list.html" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">Transaction List</div>
                            </a>
                        </li>
                        <li>
                            <a href="side-menu-light-transaction-detail.html" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">Transaction Detail</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            Sellers 
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="side-menu-light-seller-list.html" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">Seller List</div>
                            </a>
                        </li>
                        <li>
                            <a href="side-menu-light-seller-detail.html" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">Seller Detail</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="side-menu-light-reviews.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Reviews </div>
                    </a>
                </li>
            </ul>
        </li>
        
        <li>
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                <div class="side-menu__title">
                    Users Management
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="side-menu-light-users-layout-1.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Layout 1 </div>
                    </a>
                </li>
                <li>
                    <a href="side-menu-light-users-layout-2.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Layout 2 </div>
                    </a>
                </li>
                <li>
                    <a href="side-menu-light-users-layout-3.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Layout 3 </div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="side-nav__devider my-6"></li>
        <li>
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="settings"></i> </div>
                <div class="side-menu__title">
                    Settings 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="side-menu-light-chart.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Chart </div>
                    </a>
                </li>
                <li>
                    <a href="side-menu-light-slider.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Slider </div>
                    </a>
                </li>
                <li>
                    <a href="side-menu-light-image-zoom.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Image Zoom </div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- END: Side Menu -->