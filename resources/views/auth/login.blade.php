<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Estate Flow</title>
    @vite(['resources/css/app.css'])
</head>
<body class="auth-page-body flex items-center justify-center min-h-screen">

    <div class="auth-card-container">
        <h2 class="auth-main-title">Welcome Back</h2>
        <p class="auth-subtitle">Sign in to your account to continue</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-field-group">
                <label class="form-field-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-field-input" required>
                @error('email') <p class="validation-error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="form-field-group">
                <label class="form-field-label">Password</label>
                <input type="password" name="password" class="form-field-input" required>
            </div>

            <div class="form-checkbox-wrapper">
                <input type="checkbox" name="remember" id="remember" class="form-checkbox-input">
                <label for="remember" class="form-checkbox-label">Remember me</label>
            </div>

            <button type="submit" class="auth-submit-btn">Log In</button>
        </form>

        <p class="auth-footer-text">
            Don't have an account? <a href="{{ route('register') }}" class="auth-footer-link">Sign Up</a>
        </p>
    </div>

</body>
</html>