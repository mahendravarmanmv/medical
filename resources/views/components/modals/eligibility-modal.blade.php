<div class="modal fade" id="eligibilityModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

                <div class="modal-header bg-dark text-white border-0 py-3 justify-content-center position-relative">
                    <div class="d-flex align-items-center gap-2">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff" alt="SleepWell Logo" height="24" class="rounded">
                        <span class="fw-bold tracking-wide fs-5">SleepWell Express</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 text-center">
                    <h3 class="fw-bold text-dark mb-2 fs-4">Check eligibility for 2hrs delivery</h3>
                    <p class="text-muted small mb-4">Enter your area pincode to instantly unlock lightning-fast medical supply delivery near you.</p>

                    <form id="pincodeCheckForm" class="mb-4">
                        <div class="input-group border border-light-subtle rounded-3 p-1 bg-white shadow-sm">
                            <span class="input-group-text bg-transparent border-0 text-secondary"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" maxlength="6" class="form-control bg-transparent border-0 fw-bold text-dark placeholder-muted shadow-none" placeholder="Enter 6-digit pincode" id="deliveryPincodeInput" required>
                            <input type="submit" class="btn btn-primary rounded-2 px-4 fw-semibold" value="Verify">
                        </div>
                        <div id="pincodeFeedback" class="text-danger small mt-2 d-none"></div>
                    </form>

                    

                    <div class="row g-2 pt-1">
                        <div class="col-6">
                            <!-- Directs to auth view, leaving the default Login tab active -->
                            <a href="{{ url('/auth') }}" class="btn btn-outline-primary w-100 py-2 fw-semibold rounded-2 small d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </div>
                        <div class="col-6">
                            <!-- Directs to auth view and passes a custom query parameter for Sign Up -->
                            <a href="{{ url('/auth?tab=signup') }}" class="btn btn-light border border-light-subtle text-dark w-100 py-2 fw-semibold rounded-2 small d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-user-plus"></i> Sign Up
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>