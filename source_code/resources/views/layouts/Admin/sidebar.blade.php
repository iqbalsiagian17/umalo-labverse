<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
      <!-- Logo Header -->
      <div class="logo-header" data-background-color="dark">
        <a href="{{ route('dashboard') }}" class="logo">
          <img
            src="{{ asset('umalo/umalo-labverse/source_code/public/assets/images/logo-nobg.png') }}"
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
          @php
          $userId = Auth::id();
          $unseenCount = \App\Models\Order::whereDoesntHave('seen_by_users', function($query) use ($userId) {
              $query->where('user_id', $userId);
          })->count();

          $unseenUserCount = \App\Models\User::where('role', 0) // Assuming role 0 is for customers
          ->whereDoesntHave('seenByAdmins', function($query) use ($userId) {
              $query->where('admin_id', $userId);
          })
          ->count();
      @endphp
      
      
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#tables">
              <i class="fas fa-shopping-cart"></i> 
              <p>Transaksi</p>
                @if($unseenCount > 0)
                    <span class="badge badge-success">{{ $unseenCount }}</span>
                @endif
                <span class="caret"></span>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav nav-collapse">
                    <li>
                        <a href="{{ route('transaksi.index') }}">
                            <span class="sub-item">Transaksi</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">Produk</h4>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#sidebarLayouts">
            <i class="fas fa-box"></i> 
            <p>Produk</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="sidebarLayouts">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{ route('produk.index') }}">
                  <span class="sub-item">Produk</span>
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
                <a href="{{ route('bigsale.index') }}">
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
            @if($unseenUserCount > 0)
                            <span class="badge badge-success">{{ $unseenUserCount }}</span>
                        @endif
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
                  <a href="{{ route('admin.masterdata.komoditas.index') }}">
                    <span class="sub-item">Komoditas</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.masterdata.kategori.index') }}">
                    <span class="sub-item">Kategori</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.masterdata.subkategori.index') }}">
                    <span class="sub-item">Sub-Kategori</span>
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
              </ul>
            </div>
          </li>
          
          
        </ul>
      </div>
    </div>
  </div>
  <!-- End Sidebar -->