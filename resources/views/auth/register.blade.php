<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Estate Flow</title>
    @vite(['resources/css/app.css'])
</head>
<body class="auth-page-body flex items-center justify-center min-h-screen">

    <div class="auth-card-container">
        <h2 class="auth-main-title">Create Account</h2>
        <p class="auth-subtitle">Join Estate Flow and manage your listings</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-field-group">
                <label class="form-field-label">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-field-input" required>
                @error('name') <p class="validation-error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="form-field-group">
                <label class="form-field-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-field-input" required>
                @error('email') <p class="validation-error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="form-field-group">
                <label class="form-field-label">Password</label>
                <input type="password" name="password" class="form-field-input" required>
                @error('password') <p class="validation-error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="form-field-group">
                <label class="form-field-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-field-input" required>
            </div>

            <button type="submit" class="auth-submit-btn">Sign Up</button>
        </form>

        <p class="auth-footer-text">
            Already have an account? <a href="{{ route('login') }}" class="auth-footer-link">Log In</a>
        </p>
    </div>

</body>
</html>