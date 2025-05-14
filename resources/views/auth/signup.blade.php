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
    <div class="wrapper">
        <h1>Sign Up</h1>
        <div class="auth-container">
            <div class="form-side">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Gmail" required>
                    <input type="number" name="phone" placeholder="Phone Number" required>
                    <input type="password" name="password" placeholder="Password" required>

                    <label class="checkbox-container">
                        <input type="checkbox" name="remember">
                        <span>Agree to Our Terms & Conditions and Privacy Policy</span>
                    </label>
                </form>
            </div>

            <div class="action-side">
                <button type="submit" form="loginForm" class="primary-btn">Sign In</button>
                <p class="switch-link">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign up</a>
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