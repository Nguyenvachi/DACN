<footer class="vc-footer">
    <div class="container py-5">
        <div class="row gy-4">
            <!-- Column 1: Logo + giới thiệu -->
            <div class="col-lg-4 col-md-6 footer-column">
                <h4 class="vc-footer-brand">
                    <i class="fas fa-heartbeat me-2"></i>VietCare
                </h4>
                <p>
                    Dịch vụ chăm sóc sức khỏe toàn diện với đội ngũ bác sĩ chuyên nghiệp
                    và hệ thống điều trị hiện đại, an toàn và tận tâm.
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" aria-label="facebook"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" aria-label="instagram"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" aria-label="twitter"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" aria-label="youtube"><i class="fab fa-youtube fa-lg"></i></a>
                </div>
            </div>

            <!-- Column 2: Liên kết nhanh -->
            <div class="col-lg-2 col-md-6 footer-column">
                <h5 class="vc-footer-title">Liên Kết</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('homepage') }}#features">Dịch Vụ</a></li>
                    <li><a href="{{ route('homepage') }}#doctors">Bác Sĩ</a></li>
                    <li><a href="{{ route('homepage') }}#services">Chuyên Khoa</a></li>
                    <li><a href="{{ route('public.blog.index') }}">Tin Tức</a></li>
                    @if(Route::has('sitemap'))
                        <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
                    @endif
                </ul>
            </div>

            <!-- Column 3: Dịch vụ chính -->
            <div class="col-lg-3 col-md-6 footer-column">
                <h5 class="vc-footer-title">Dịch Vụ</h5>
                <ul class="list-unstyled">
                    <li>
                        <a href="{{ route('public.bacsi.index') }}">
                            <i class="fas fa-user-md me-2"></i>Đặt Lịch Khám
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public.dichvu.index') }}">
                            <i class="fas fa-procedures me-2"></i>Dịch Vụ Y Tế
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->role === 'patient')
                            <li>
                                <a href="{{ route('patient.shop.index') }}">
                                    <i class="fas fa-pills me-2"></i>Mua Thuốc Online
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('patient.chat.index') }}">
                                    <i class="fas fa-comments me-2"></i>Tư Vấn Trực Tuyến
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>

            <!-- Column 4: Liên hệ -->
            <div class="col-lg-3 col-md-6 footer-column">
                <h5 class="vc-footer-title">Liên Hệ</h5>
                <ul class="list-unstyled">
                    <li>
                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                        123 Đường ABC, Quận XYZ, TP.HCM
                    </li>
                    <li>
                        <i class="fas fa-phone text-success me-2"></i>
                        <a href="tel:0123456789">0123 456 789</a>
                    </li>
                    <li>
                        <i class="fas fa-envelope text-success me-2"></i>
                        <a href="mailto:info@vietcare.vn">info@vietcare.vn</a>
                    </li>
                    <li>
                        <i class="fas fa-clock text-success me-2"></i>
                        24/7 - Luôn sẵn sàng phục vụ
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Line -->
        <div class="vc-footer-bottom mt-4 text-center">
            &copy; {{ date('Y') }} VietCare. All rights reserved.
        </div>
    </div>
</footer>
