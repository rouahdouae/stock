<!-- Modal Structure -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="createProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Nom -->
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Prix -->
                    <div class="mb-3">
                        <label class="form-label">Price *</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Catégorie -->
                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Fournisseur -->
                    <div class="mb-3">
                        <label class="form-label">Supplier *</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Image -->
                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="picture" class="form-control" accept="image/*">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createProductForm');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            });
            
            if (!response.ok) throw await response.json();
            
            window.location.reload();
        } catch (error) {
            if (error.errors) {
                Object.entries(error.errors).forEach(([field, messages]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    const feedback = input.nextElementSibling;
                    
                    input.classList.add('is-invalid');
                    feedback.textContent = messages[0];
                });
            }
        }
    });

    // Reset form when modal closes
    document.getElementById('createProductModal').addEventListener('hidden.bs.modal', function() {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    });
});
</script>
@endpush