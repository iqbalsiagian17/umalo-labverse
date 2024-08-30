 <!-- Footer Section Begin -->
<footer class="footer spad">
    <div class="container">
        <div class="row">
            <!-- Contact Info Section -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="footer__about">
                    <h3 style="font-weight: bold; font-size: 24px;">{{ __('messages.contact_info') }}</h3><br>
                    <div class="item" style="display: flex; align-items: flex-start; margin-bottom: 10px;">
                        <i class="fas fa-home" style="margin-right: 10px; font-size: 16px; margin-top: 2px;"></i>
                        <span class="address info" style="display: inline-block; max-width: calc(100% - 30px); line-height: 1.5; font-size: 14px;">
                            {{ __('messages.address') }}
                        </span>
                    </div>
                    <div class="item" style="display: flex; align-items: flex-start; margin-bottom: 10px;">
                        <i class="fas fa-phone-alt" style="margin-right: 10px; font-size: 16px; margin-top: 2px;"></i>
                        <span class="phone info" style="display: inline-block; max-width: calc(100% - 30px); line-height: 1.5; font-size: 14px;">
                            (021) 2204 3144
                        </span>
                    </div>
                    <div class="item" style="display: flex; align-items: flex-start;">
                        <i class="fas fa-envelope" style="margin-right: 10px; font-size: 16px; margin-top: 2px;"></i>
                        <span class="email info" style="display: inline-block; max-width: calc(100% - 30px); line-height: 1.5; font-size: 14px;">
                            info@labtek.id
                        </span>
                    </div>
                    <div class="item" style="display: flex; align-items: flex-start;">
                        <i class="fas fa-envelope" style="margin-right: 10px; font-size: 16px; margin-top: 2px;"></i>
                        <span class="email info" style="display: inline-block; max-width: calc(100% - 30px); line-height: 1.5; font-size: 14px;">
                            sales@labtek.id
                        </span>
                    </div>
                </div>
            </div>

            <!-- Menu Links Section -->
            <div class="col-lg-5 col-md-6 col-sm-12 mb-4">
                <div class="footer__widget">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <h6>{{ __('messages.help_resources') }}</h6>
                            <ul style="text-decoration: underline;">
                                <li><a href="/shop">{{ __('messages.find_product') }}</a></li>
{{--                                 <li><a href="/sign-up">{{ __('messages.login_member') }}</a></li> --}}
                                <li><a href="/cart">{{ __('messages.shopping_cart') }}</a></li>
                                <li><a href="/">{{ __('messages.home') }}</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <h6>{{ __('messages.about') }}</h6>
                            <ul style="text-decoration: underline;">
{{--                                 <li><a href="/company">{{ __('messages.about_us') }}</a></li>
                                <li><a href="/company">{{ __('messages.our_brand') }}</a></li>
                                <li><a href="/company">{{ __('messages.contact_us') }}</a></li>
 --}}                                <li><a href="/faq">{{ __('messages.qna') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo Images Section -->
            <div class="col-lg-3 col-md-12 col-sm-12 d-flex justify-content-center mb-4">
                <div class="footer__widget">
                    <div class="footer__about__logo d-flex flex-column align-items-center">
                        <a href="/">
                            <img src="{{ asset('assets/images/logo-nobg.png') }}" alt="Logo Perusahaan" style="width: 100%; height: 100px;">
                        </a>
                        <p style="margin: 5px 0; text-align: center;">Bagian dari</p>
                        <a href="/ags.com">
                            <img src="{{ asset('assets/images/AGS-logo.png') }}" alt="Logo AGS" style="width: 100%; height: 100px; margin-bottom: 10px;">
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex justify-content-center">
                <div class="footer__copyright text-center" style="padding: 15px 0;">
                    <div class="footer__copyright__text" style="font-size: 12px;">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script> {{ __('messages.created_by') }} PT Arkamaya Guna Saharsa
                    </div>
                    <div class="footer__copyright__payment mt-2">
                        <img src="img/payment-item.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->



{{-- @if(Auth::check())
   @php
       $user = Auth::user();
       $email = $user->email;
       $no_telepon = $user->userDetail->no_telepone ?? 'N/A'; // Pastikan relasi UserDetail ada
       $perusahaan = $user->userDetail->perusahaan ?? 'N/A'; // Pastikan relasi UserDetail ada
   @endphp

   <!--Start of Tawk.to Script-->
   <script type="text/javascript">
       var Tawk_API = Tawk_API || {};
       Tawk_API.visitor = {
           name : '{{ $user->name }}',
           email : '{{ $email }}',
           phone : '{{ $no_telepon }}',
           job_title: '{{ $perusahaan }}'
       };

       var Tawk_LoadStart = new Date();
       (function(){
           var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
           s1.async = true;
           s1.src = 'https://embed.tawk.to/66b98f50146b7af4a4392fd9/1i52dfl1n';
           s1.charset = 'UTF-8';
           s1.setAttribute('crossorigin', '*');
           s0.parentNode.insertBefore(s1, s0);
       })();
   </script>
   <!--End of Tawk.to Script-->
@endif --}}


@if(Auth::check())
   @php
       $user = Auth::user();
       $email = $user->email;
       $no_telepon = $user->userDetail->no_telepone ?? 'N/A'; // Pastikan relasi UserDetail ada
       $perusahaan = $user->userDetail->perusahaan ?? 'N/A'; // Pastikan relasi UserDetail ada
   @endphp

   <!--Start of Tawk.to Script-->
   <script type="text/javascript">
       var Tawk_API = Tawk_API || {};
       Tawk_API.visitor = {
           name : '{{ $user->name }}',
           email : '{{ $email }}',
           phone : '{{ $no_telepon }}',
           job_title: '{{ $perusahaan }}'
       };

       var Tawk_LoadStart = new Date();
       (function(){
           var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
           s1.async = true;
           s1.src = 'https://embed.tawk.to/66d1440fea492f34bc0b9a5e/1i6gn3nr5';
           s1.charset = 'UTF-8';
           s1.setAttribute('crossorigin', '*');
           s0.parentNode.insertBefore(s1, s0);
       })();
   </script>
   <!--End of Tawk.to Script-->
@endif


    <!-- Js Plugins -->
    <script src="{{ asset('ogani/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('ogani/js/bootstrap.bundle.min.js') }}"></script> <!-- Use bootstrap.bundle.min.js for Bootstrap 5 -->
    <script src="{{ asset('ogani/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('ogani/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('ogani/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('ogani/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('ogani/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('ogani/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('ogani/js/main.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </body>
    </html>
