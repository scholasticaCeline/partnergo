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
        <div class="auth-container">
            <div class="form-side">
                <div class="form-box">
                    <h2>Create Account</h2>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <!-- Name Field -->
                        <input type="text" name="name" placeholder="Name" required value="{{ old('name') }}">
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <!-- Email Field -->
                        <input type="email" name="email" placeholder="Gmail" required value="{{ old('email') }}">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <!-- Phone Field -->
                        <input type="number" name="phoneNumber" placeholder="Phone Number" required value="{{ old('phoneNumber') }}">
                        @error('phoneNumber')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <!-- Password Field -->
                        <input type="password" name="password" placeholder="Password" required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <!-- Confirm Password Field -->
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <!-- Terms & Conditions Checkbox -->
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            <span>Agree to Our Terms & Conditions and Privacy Policy</span>
                        </label>

                        <button type="submit" class="primary-btn">Sign Up</button>
                    </form>

                    <p class="switch-link">
                        Already have an account?
                        <a href="{{ route('login') }}">Sign in</a>
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
    </div>
</body>
</html>
