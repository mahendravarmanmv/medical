<!-- Offcanvas Sidebar Cart Grid Frame -->
<div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="cartDrawer" aria-labelledby="cartDrawerLabel" style="width: 420px;">
    <div class="offcanvas-header bg-dark text-white py-3">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2" id="cartDrawerLabel">
            <i class="bi bi-basket3-fill"></i> Your Basket
        </h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <!-- Dynamic Cart Container Content Shell loaded asynchronously via AJAX -->
    <div class="offcanvas-body d-flex flex-column p-0" id="cartDrawerContent">
        <!-- JS will render dynamic items or empty message here -->
    </div>
</div>