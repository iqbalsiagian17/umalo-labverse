<!-- Sidebar -->
<div class="sidebar" data-background-color="white">
    <div class="sidebar-logo">
      <!-- Logo Header -->
<div class="logo-header" data-background-color="white" data-logo-light="{{ asset('assets/images/logo-nobg.png') }}" data-logo-dark="{{ asset('assets/images/logo-dark.png') }}">
        <a href="{{ route('dashboard') }}" class="logo">
          <img
            src="{{ asset('assets/images/logo-nobg.png') }}"
            alt="navbar brand"
            class="navbar-brand"
            width="200"
          />
        </a>
        
        <div class="nav-toggle">
          <button class="btn btn-toggle toggle-sidebar">
            <i class="gg-menu-right"></i>
          </button>
          <button class="btn btn-toggle sidenav-toggler">
            <i class="gg-menu-left"></i>
          </button>
        </div>
        <button class="topbar-toggler more">
          <i class="gg-more-vertical-alt"></i>
        </button>
      </div>
      <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        <ul class="nav nav-secondary">
          <li class="nav-item">
            <a href="{{ route('dashboard') }}">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
             </a>
          </li>
          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">transaction</h4>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#tables">
              <i class="fas fa-shopping-cart"></i> 
              <p>Transaksi</p>
              @php
                                    // Jumlah pesanan yang belum dilihat
                                    $unviewedOrdersCount = \App\Models\Order::where('is_viewed_by_admin', false)->count();
                                    $unviewedPaymentsCount = \App\Models\Payment::where('is_viewed_by_admin', false)->count();

                                    $unviewedCount = \App\Models\Order::where('is_viewed_by_admin', false)->count() + \App\Models\Payment::where('is_viewed_by_admin', false)->count();


                                @endphp
                                @if ($unviewedCount > 0)
                                <span class="badge bg-danger">{{ $unviewedCount }}</span>
                                @endif
                <span class="caret"></span>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav nav-collapse">
                    <li>
                        <a href="{{ route('admin.orders.index') }}">
                            <span class="sub-item">Orderan
                              @if ($unviewedOrdersCount > 0)
                                    <span class="badge bg-danger">{{ $unviewedOrdersCount }}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.payments.index') }}">
                          <span class="sub-item">Payment
                                @if ($unviewedPaymentsCount > 0)
                                    <span class="badge bg-danger">{{ $unviewedPaymentsCount }}</span>
                                @endif
                          </span>
                      </a>
                  </li>
                </ul>
            </div>
        </li>
        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">Product</h4>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#sidebarLayouts">
            <i class="fas fa-box"></i> 
            <p>Product</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="sidebarLayouts">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{ route('admin.product.index') }}">
                  <span class="sub-item">Product</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">Event</h4>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#forms">
            <i class="fas fa-calendar-alt"></i> <!-- Mengganti ikon menjadi ikon kalender -->
            <p>Promosi</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="forms">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{ route('slider.index') }}">
                  <span class="sub-item">Slider - Landing Page</span>
                </a>
              </li><li>
                <a href="{{-- {{ route('bigsale.index') }} --}}">
                  <span class="sub-item">BigSale Event</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">Costumer Manage</h4>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#customer">
            <i class="fas fa-user"></i> <!-- Mengganti ikon menjadi ikon pengguna (user) -->
            <p>Costumer</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="customer">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{ route('users.index') }}">
                  <span class="sub-item">User</span>
                </a>
            </ul>
          </div>
        </li>
        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">FAQ</h4>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#faq">
            <i class="fas fa-question-circle"></i> <!-- Mengganti ikon menjadi ikon FAQ (question circle) -->
            <p>FAQ</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="faq">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{ route('qas.index') }}">
                  <span class="sub-item">FAQ</span>
                </a>
            </ul>
          </div>
        </li>
        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">Master Data</h4>
        </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#base">
              <i class="fas fa-database"></i> <!-- Mengganti ikon menjadi ikon database -->
              <p>Master Data</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="base">
              <ul class="nav nav-collapse">
                <li>
                  <a href="{{ route('admin.masterdata.Category.index') }}">
                    <span class="sub-item">Category</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.masterdata.subCategory.index') }}">
                    <span class="sub-item">Sub-Category</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.masterdata.ppn.index') }}">
                    <span class="sub-item">PPN</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.masterdata.materai.index') }}">
                    <span class="sub-item">Materai</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.masterdata.shippingservice.index') }}">
                    <span class="sub-item">Shipping Service</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          
          
        </ul>
      </div>
    </div>
  </div>
  <!-- End Sidebar -->