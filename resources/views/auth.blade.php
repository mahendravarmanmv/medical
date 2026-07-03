<x-layout>
    <div class="container my-5 py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 small mb-3 border-0 shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-1 text-success"></i> {{ session('success') }}
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 small mb-3 border-0 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-circle me-1 text-danger"></i> {{ $errors->first() }}
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
                    
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <ul class="nav nav-underline nav-fill bg-light p-1 rounded-2" id="authTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold py-2.5 rounded-2 small" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-panel" type="button" role="tab" aria-controls="login-panel" aria-selected="true">
                                    <i class="fas fa-sign-in-alt me-1"></i> Login
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-2.5 rounded-2 small text-secondary" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-panel" type="button" role="tab" aria-controls="register-panel" aria-selected="false">
                                    <i class="fas fa-user-plus"></i> Sign Up
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content" id="authTabsContent">
                            
                            <div class="tab-pane fade show active" id="login-panel" role="tabpanel" aria-labelledby="login-tab">
                                <div class="text-center mb-4">
                                    <h4 class="fw-bold text-dark">Welcome Back</h4>
                                    <p class="text-muted small">Access your personalized sleep parameters and tracking logs</p>
                                </div>

                                <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="login_email" class="form-label small fw-bold text-dark">Email Address</label>
                                        <div class="input-group border border-light-subtle rounded-3 bg-white px-2 py-1 align-items-center">
                                            <span class="text-secondary ps-1"><i class="far fa-envelope"></i></span>
                                            <input type="email" name="email" id="login_email" class="form-control bg-transparent border-0 shadow-none small" placeholder="name@example.com" required value="{{ old('email') }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label for="login_password" class="form-label small fw-bold text-dark mb-0">Password</label>
                                            <a href="#" class="text-primary small text-decoration-none fw-medium">Forgot Password?</a>
                                        </div>
                                        <div class="input-group border border-light-subtle rounded-3 bg-white px-2 py-1 align-items-center">
                                            <span class="text-secondary ps-1"><i class="fas fa-lock"></i></span>
                                            <input type="password" name="password" id="login_password" class="form-control bg-transparent border-0 shadow-none small" placeholder="••••••••" required>
                                        </div>
                                    </div>

                                    <div class="mb-4 form-check text-start">
                                        <input type="checkbox" name="remember" class="form-check-input border-secondary-subtle cursor-pointer" id="remember_me">
                                        <label class="form-check-label text-muted small user-select-none cursor-pointer" for="remember_me">Remember device session</label>
                                    </div>

                                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3">
                                        Sign In
                                    </button>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="register-panel" role="tabpanel" aria-labelledby="register-tab">
                                <div class="text-center mb-4">
                                    <h4 class="fw-bold text-dark">Create Account</h4>
                                    <p class="text-muted small">Register to coordinate setup guides and rapid equipment logistics</p>
                                </div>

                                <form action="{{ route('register.submit') }}" method="POST" id="registerForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="reg_name" class="form-label small fw-bold text-dark">Full Name</label>
                                        <div class="input-group border border-light-subtle rounded-3 bg-white px-2 py-1 align-items-center">
                                            <span class="text-secondary ps-1"><i class="far fa-user"></i></span>
                                            <input type="text" name="name" id="reg_name" class="form-control bg-transparent border-0 shadow-none small" placeholder="John Doe" required value="{{ old('name') }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="reg_email" class="form-label small fw-bold text-dark">Email Address</label>
                                        <div class="input-group border border-light-subtle rounded-3 bg-white px-2 py-1 align-items-center">
                                            <span class="text-secondary ps-1"><i class="far fa-envelope"></i></span>
                                            <input type="email" name="email" id="reg_email" class="form-control bg-transparent border-0 shadow-none small" placeholder="name@example.com" required value="{{ old('email') }}">
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-sm-6">
                                            <label for="reg_pincode" class="form-label small fw-bold text-dark">PIN Code</label>
                                            <div class="input-group border border-light-subtle rounded-3 bg-white px-2 py-1 align-items-center">
                                                <span class="text-secondary ps-1"><i class="fas fa-map-marker-alt"></i></span>
                                                <input type="text" name="pincode" id="reg_pincode" maxlength="6" class="form-control bg-transparent border-0 shadow-none small" placeholder="500090" required value="{{ old('pincode') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="reg_phone" class="form-label small fw-bold text-dark">Phone Number</label>
                                            <div class="input-group border border-light-subtle rounded-3 bg-white px-2 py-1 align-items-center">
                                                <span class="text-secondary ps-1"><i class="fas fa-phone-alt"></i></span>
                                                <input type="text" name="phone" id="reg_phone" class="form-control bg-transparent border-0 shadow-none small" placeholder="9876543210" required value="{{ old('phone') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="reg_password" class="form-label small fw-bold text-dark">Password</label>
                                        <div class="input-group border border-light-subtle rounded-3 bg-white px-2 py-1 align-items-center">
                                            <span class="text-secondary ps-1"><i class="fas fa-lock"></i></span>
                                            <input type="password" name="password" id="reg_password" class="form-control bg-transparent border-0 shadow-none small" placeholder="Minimum 8 characters" required>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3 text-white border-0">
                                        Register Now
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </div>
</x-layout>