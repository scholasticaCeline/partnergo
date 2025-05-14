<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <title>PartnerGO</title>
</head>
<body>
    <div class="auth-container">
        <div class="form-side">
            <div class="form-box">
                <h2>Login</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="email" name="email" placeholder="Gmail" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <input type="password" name="password" placeholder="Password" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <label class="checkbox-container">
                        <input type="checkbox" name="remember">
                        <span>Agree to Our Terms & Conditions and Privacy Policy</span>
                    </label>

                    <button type="submit" class="primary-btn">Sign Up</button>
                </form>

                <p class="switch-link">
                    Already have an account?
                    <a href="{{ route('register') }}">Sign up</a>
                </p>

                <div class="or-divider">
                    <span></span>
                    <p>Or with</p>
                    <span></span>
                </div>

                <button class="social-btn facebook">Sign up with Facebook</button>
                <button class="social-btn google">Sign up with Google</button>
            </div>
        </div>
    </div>
</body>
</html>