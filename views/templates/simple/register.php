<div class="py-2">
    <div class="text-center mb-5">
        <h3>Register Account</h3>
        <p class="text-muted">Get your free Mesigo account now.</p>
    </div>

    <form action="/" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" required>
            <div class="invalid-feedback">
                Please Enter Email
            </div>
        </div>

        <div class="mb-3">
            <label for="login" class="form-label">Username</label>
            <input type="text" name="login" id="login" class="form-control" placeholder="Enter username" required>
            <div class="invalid-feedback">
                Please Enter Username
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
            <div class="invalid-feedback">
                Please Enter Password
            </div>
        </div>

        <div class="mb-4">
            <p class="mb-0">By registering you agree to the Mesigo <a href="#" class="text-primary">Terms of Use</a></p>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary w-100 waves-effect waves-light">Register</button>
        </div>
    </form>

    <div class="mt-5 text-center text-muted">
        <p>Already have an account? <a href="/" class="fw-medium text-decoration-underline">Login</a></p>
    </div>
</div>
