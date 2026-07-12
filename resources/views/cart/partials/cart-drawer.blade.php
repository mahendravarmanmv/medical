<!-- Ensure these exact class layouts are defined on the parent container wrapper -->
<div class="offcanvas offcanvas-end border-0 shadow-lg z-1050" tabindex="-1" id="cartDrawer" data-bs-scroll="true" aria-labelledby="cartDrawerLabel">
    <div class="offcanvas-header bg-dark text-white p-3">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2" id="cartDrawerLabel">
            <i class="bi bi-basket3-fill"></i> Your Basket
        </h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <!-- Dynamic items context shell handled by your app.js engine -->
    <div class="offcanvas-body d-flex flex-column p-0" id="cartDrawerContent">
        <!-- Rendered HTML items from drawer-items.blade.php inject here smoothly -->
    </div>
</div>