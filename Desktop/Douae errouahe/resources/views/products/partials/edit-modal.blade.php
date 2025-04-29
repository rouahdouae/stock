<!-- Modal d'édition -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="product_id">
                
                <div class="modal-body">
                    <!-- Champs du formulaire -->
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price *</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Supplier *</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="picture" class="form-control" accept="image/*">
                    </div>
                    
                    <!-- Messages d'erreur génériques -->
                    <div class="invalid-feedback d-none"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editProductForm');
    const modal = document.getElementById('editProductModal');
    
    // Gestion de la soumission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            });
            
            if (!response.ok) throw await response.json();
            
            modal.hide();
            window.location.reload();
        } catch (error) {
            showFormErrors(error.errors);
        }
    });
    
    // Réinitialisation du modal
    modal.addEventListener('hidden.bs.modal', () => {
        form.reset();
        clearErrors();
    });
    
    // Fonctions utilitaires
    function showFormErrors(errors) {
        clearErrors();
        
        Object.entries(errors).forEach(([field, messages]) => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorDiv = input.nextElementSibling || form.querySelector('.invalid-feedback');
                errorDiv.textContent = messages[0];
                errorDiv.classList.remove('d-none');
            }
        });
    }
    
    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
            el.classList.add('d-none');
        });
    }
    
    // Fonction pour charger les données du produit
    window.loadProductData = (product) => {
        form.action = `/products/${product.id}`;
        form.querySelector('[name="product_id"]').value = product.id;
        form.querySelector('[name="name"]').value = product.name;
        form.querySelector('[name="description"]').value = product.description;
        form.querySelector('[name="price"]').value = product.price;
        form.querySelector('[name="category_id"]').value = product.category_id;
        form.querySelector('[name="supplier_id"]').value = product.supplier_id;
    };
});
</script>
@endpush