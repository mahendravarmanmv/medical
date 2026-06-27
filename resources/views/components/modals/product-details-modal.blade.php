<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">

            <div class="modal-header bg-white border-0 pt-3 pe-3 pb-0 justify-content-end position-relative" style="z-index: 1060;">
                <button type="button" class="btn-close pe-auto" data-bs-dismiss="modal" aria-label="Close" style="cursor: pointer; position: relative; z-index: 1065;"></button>
            </div>

            <div class="modal-body p-4 pt-0">
                <div class="row g-5">

                    <!-- Product Media Gallery Column Layout Component Tracking Trays -->
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center justify-content-center bg-white rounded-3 border border-light-subtle mb-3" style="height: 380px;">
                            <img src="" id="modalProductImage" class="img-fluid object-fit-contain h-100 p-3" alt="Active Frame View">
                        </div>

                        <!-- High Performance Thumbnail Render Track Slot -->
                        <div class="d-flex gap-2 overflow-x-auto pb-2 scrollbar-thin" id="modalThumbnailsTray" style="white-space: nowrap;">
                            <!-- Dynamic loop content will load cleanly here -->
                        </div>
                    </div>

                    <!-- Core Variant Descriptions & Action Attributes Column Layout Structure -->
                    <div class="col-lg-6 d-flex flex-column justify-content-between">
                        <div>
                            <h2 class="fw-bold text-dark lh-sm fs-3 mb-2" id="modalProductTitle">Loading Data...</h2>

                            <div class="d-flex align-items-center gap-2 mb-4 small">
                                <div class="text-warning">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                                </div>
                                <span class="fw-bold text-dark">4.2</span>
                                <span class="text-muted border-start ps-2">5 Ratings</span>
                            </div>

                            <div class="mb-2">
                                <span class="fs-2 fw-bold text-dark me-2" id="modalProductPrice">₹0</span>
                                <span class="text-muted text-decoration-line-through fs-5 me-2" id="modalProductOriginalPrice"></span>
                                <span class="text-primary fw-bold fs-6" id="modalProductDiscount"></span>
                            </div>
                            <p class="text-muted small mb-4">MRP (Inclusive of all taxes)</p>

                            <div class="mb-4">
                                <h6 class="fw-bold mb-2">Product Description</h6>
                                <p class="text-muted small mb-0" id="modalProductDescription">
                                    Fetching data securely...
                                </p>
                            </div>

                            <!-- DYNAMIC SECTION WRAPPER: Handles hiding the entire block area when no packages exist -->
                            <div class="js-package-section">
                                <hr class="my-4 text-muted opacity-25">

                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Choose Package</h6>
                                    <div class="row g-3" id="modalPackageContainer">
                                        <!-- Kept completely empty so JS handles layout mapping exclusively without duplicates -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Operational Increment and Checkout Action Footers -->
                        <div class="row g-2 align-items-center pt-3 mt-auto">
                            <div class="col-sm-4 col-5">
                                <div class="d-flex align-items-center justify-content-between border border-dark rounded-pill bg-white px-2 py-1">
                                    <button class="btn btn-white border-0 p-1 lh-1" type="button" id="btn-qty-minus">
                                        <i class="fas fa-minus fs-7 text-secondary"></i>
                                    </button>
                                    <input type="text" class="form-control text-center bg-transparent border-0 fw-bold text-dark p-0" value="1" id="modal-qty" readonly style="max-width: 35px; box-shadow: none;">
                                    <button class="btn btn-white border-0 p-1 lh-1" type="button" id="btn-qty-plus">
                                        <i class="fas fa-plus fs-7 text-secondary"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-sm-8 col-7">
                                <button class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold add-to-cart-btn shadow-sm" id="modalAddToCartBtn" data-id="">
                                    Add To Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Relational Dealers Accordion Tray Panels Layout -->
                <div class="border-top border-light-subtle pt-4 mt-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fw-bold text-dark fs-6">Which other dealers are available for this product?</span>
                        <button class="btn btn-outline-primary rounded-pill px-3 fw-semibold text-decoration-none d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="collapse" data-bs-target="#dealersCollapse" aria-expanded="false" aria-controls="dealersCollapse">
                            View Dealers <i class="fas fa-chevron-down small"></i>
                        </button>
                    </div>

                    <div class="collapse" id="dealersCollapse">
                        <div id="dealerListContainer" class="pt-3">
                            <!-- Dynamic Dealer Pricing Rows Hydrate Here -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>