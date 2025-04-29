<!-- Modal d'importation -->
<div class="modal fade" id="importProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Import Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="importProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Excel File *</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Upload </small>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styles modernes pour le modal */
    #importProductModal .modal-content {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    #importProductModal .modal-header {
        border-bottom: 1px solid #dee2e6;
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    
    #importProductModal .modal-footer {
        border-top: 1px solid #dee2e6;
        border-radius: 0 0 0.5rem 0.5rem !important;
    }
    
    #importProductModal .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    #importProductModal .invalid-feedback {
        font-size: 0.875rem;
        color: #dc3545;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('importProductForm');
    const modal = bootstrap.Modal.getInstance('#importProductModal');
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            });
            
            if (!response.ok) {
                const error = await response.json();
                throw error;
            }
            
            if (modal) modal.hide();
            window.location.reload();
        } catch (error) {
            // Gestion des erreurs
            const fileInput = form.querySelector('[name="file"]');
            const feedback = fileInput.nextElementSibling;
            
            if (error.errors) {
                fileInput.classList.add('is-invalid');
                feedback.textContent = error.errors.file[0];
            } else {
                fileInput.classList.add('is-invalid');
                feedback.textContent = 'Import failed. Please check your file format.';
            }
        }
    });
    
    // Réinitialiser le formulaire quand le modal se ferme
    document.getElementById('importProductModal').addEventListener('hidden.bs.modal', () => {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    });
});
</script>
@endpush