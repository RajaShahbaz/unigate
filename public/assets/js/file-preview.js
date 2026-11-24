/**
 * File Preview Utility
 * Shows preview of selected images/files in edit modals
 * 
 * Usage:
 * 1. Add data-preview-target attribute to file input: data-preview-target="preview-container-id"
 * 2. Add a preview container element with the matching ID
 * 3. Call initFilePreview() on page load or after modal opens
 */

function initFilePreview() {
    // Find all file inputs with preview targets
    const fileInputs = document.querySelectorAll('input[type="file"][data-preview-target]');
    
    fileInputs.forEach(input => {
        const previewTargetId = input.getAttribute('data-preview-target');
        const previewContainer = document.getElementById(previewTargetId);
        
        if (!previewContainer) {
            console.warn(`Preview container with ID "${previewTargetId}" not found`);
            return;
        }
        
        // Handle file selection
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (!file) {
                previewContainer.innerHTML = '';
                previewContainer.style.display = 'none';
                return;
            }
            
            // Check if it's an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <div class="file-preview-wrapper" style="margin-top: 10px;">
                            <img src="${e.target.result}" 
                                 alt="Preview" 
                                 class="img-fluid" 
                                 style="max-width: 300px; max-height: 300px; border-radius: 8px; border: 2px solid #ddd; padding: 5px;">
                            <div class="mt-2">
                                <small class="text-muted">${file.name} (${formatFileSize(file.size)})</small>
                            </div>
                        </div>
                    `;
                    previewContainer.style.display = 'block';
                };
                
                reader.onerror = function() {
                    previewContainer.innerHTML = '<div class="alert alert-danger">Error loading preview</div>';
                    previewContainer.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            } else {
                // For non-image files, show file info
                previewContainer.innerHTML = `
                    <div class="file-preview-wrapper mt-2">
                        <div class="alert alert-info">
                            <i class="fas fa-file"></i> ${file.name} (${formatFileSize(file.size)})
                        </div>
                    </div>
                `;
                previewContainer.style.display = 'block';
            }
        });
        
        // Clear preview when modal is closed
        const modal = input.closest('.modal');
        if (modal) {
            $(modal).on('hidden.bs.modal', function() {
                previewContainer.innerHTML = '';
                previewContainer.style.display = 'none';
                input.value = '';
            });
        }
    });
}

/**
 * Format file size to human readable format
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Auto-initialize on document ready
$(document).ready(function() {
    initFilePreview();
    
    // Re-initialize when modals are shown (for dynamically loaded modals)
    $('.modal').on('shown.bs.modal', function() {
        initFilePreview();
    });
});
