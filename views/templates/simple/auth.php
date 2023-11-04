<div class="py-md-5 py-4">
    <div class="text-center mb-5">
        <h3>Welcome Back!</h3>
        <p class="text-muted">Sign in to continue to Doot.</p>
    </div>

    <form action="/auth/login/" method="post">
        <div class="mb-3">
            <label for="login" class="form-label">Username</label>
            <input type="text" name="login" id="login" class="form-control" placeholder="Enter username">
        </div>

        <div class="mb-3">
            <div class="float-end">
                <a href="/" class="text-muted">Forgot password?</a>
            </div>

            <label for="password" class="form-label">Password</label>
            <div class="position-relative auth-pass-inputgroup mb-3">
                <input type="password" name="password" id="password" class="form-control pe-5" placeholder="Enter Password">
                <button type="button" id="password-addon" class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted">
                    <i class="ri-eye-fill align-middle"></i>
                </button>
            </div>
        </div>

        <div class="form-check form-check-info font-size-16">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label font-size-14">
                Remember me
            </label>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </div>
    </form>

    <div class="mt-5 text-center text-muted">
        <p>Don't have an account? <a href="/" class="fw-medium text-decoration-underline"> Register</a></p>
    </div>
</div>
