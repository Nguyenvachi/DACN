<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyển hướng - VietCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .redirect-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
        }
        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .countdown {
            font-size: 3rem;
            font-weight: bold;
            color: #10b981;
        }
    </style>
</head>
<body>
    <div class="redirect-card">
        <div class="icon-wrapper">
            <i class="fas fa-hospital-user fa-3x text-white"></i>
        </div>

        <h2 class="mb-3">Đang chuyển hướng...</h2>

        <p class="text-muted mb-4">
            Hệ thống VietCare đã nâng cấp trải nghiệm đăng nhập.<br>
            Bạn sẽ được chuyển về trang chủ trong <span class="countdown" id="countdown">3</span> giây.
        </p>

        <a href="{{ route('homepage') }}" class="btn btn-success btn-lg">
            <i class="fas fa-home me-2"></i>Về Trang Chủ Ngay
        </a>

        <div class="mt-4 text-muted small">
            <i class="fas fa-info-circle me-1"></i>
            Vui lòng sử dụng nút "Đăng Nhập" trên trang chủ
        </div>
    </div>

    <script>
        let seconds = 3;
        const countdownEl = document.getElementById('countdown');

        const timer = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = '{{ route('homepage') }}?login=required';
            }
        }, 1000);
    </script>
</body>
</html>
