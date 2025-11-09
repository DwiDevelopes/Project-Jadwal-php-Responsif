<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Toko Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" width="200px" height="200px"
                style="border-radius: 50%; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h1>Selamat Datang Kembali</h1>
            <p>Login Untuk Memasuki Akun Anda Silahkan Login</p>
        </div>

        <form action="{{ route('proses_login') }}" method="POST" id="loginForm">
            @csrf

            <div class="form-group">
                <label for="email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}"
                    placeholder="Enter your email address" required>
                <i class="fas fa-envelope input-icon"></i>

                @error('email')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password"><i class="fa-solid fa-user-lock"></i> Password</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Enter your password" required>
                <i class="fa-solid fa-shop-lock input-icon"></i>

                @error('password')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>

            @error('login')
            <div class="error-message" style="margin-bottom: 20px;">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
            </div>
            @enderror

            @error('status')
            <div class="error-message" style="margin-bottom: 20px;">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
            </div>
            @enderror

            <div class="check-box" style="margin-bottom: 20px">
                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember
                    me</label>
            </div>


            <button type="submit" class="btn-submit">
                Sign In
            </button>
            <p>silahkan masukan password dan username anda untuk memasuki menu login</p>
            <style>
                p {
                    text-align: center;
                    margin-top: 20px;
                    color: #555;
                    font-size: 14px;
                }
            </style>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.form-control');

            inputs.forEach(input => {

                input.addEventListener('focus', function () {
                    this.parentElement.classList.add('focused');
                });


                input.addEventListener('blur', function () {
                    if (this.value === '') {
                        this.parentElement.classList.remove('focused');
                    }
                });


                if (input.value !== '') {
                    input.parentElement.classList.add('focused');
                }
            });

            const submitButton = document.querySelector('.btn-submit');
            submitButton.addEventListener('click', function (e) {

            });


            const container = document.querySelector('.login-container');
            container.style.transform = 'scale(0.95)';
            setTimeout(() => {
                container.style.transition = 'transform 0.4s ease-out';
                container.style.transform = 'scale(1)';
            }, 100);
        });
    </script>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }
        </style>
</body>

</html>