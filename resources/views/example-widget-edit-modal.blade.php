{{-- Example: Edit Modal with File Previews for Artwork, NFT, and Wallet --}}
{{-- This is a template you can adapt to your actual widget settings page --}}

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Widget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Artwork File Input with Preview --}}
                    <div class="form-group">
                        <label for="edit_artwork">{{ __('Artwork') }}</label>
                        <input type="file" 
                               name="artwork" 
                               id="edit_artwork" 
                               class="file-upload-default" 
                               accept="image/*"
                               data-preview-target="artwork-preview">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('upload_file') }}" />
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                            </span>
                        </div>
                        {{-- Preview Container for Artwork --}}
                        <div id="artwork-preview" style="display: none;"></div>
                        {{-- Show existing artwork if available --}}
                        <div id="existing-artwork" class="mt-2" style="display: none;">
                            <small class="text-muted">Current artwork:</small>
                            <img id="existing-artwork-img" src="" alt="Current artwork" class="img-fluid" style="max-width: 200px; border-radius: 4px;">
                        </div>
                    </div>

                    {{-- NFT File Input with Preview --}}
                    <div class="form-group">
                        <label for="edit_nft">{{ __('NFT') }}</label>
                        <input type="file" 
                               name="nft" 
                               id="edit_nft" 
                               class="file-upload-default" 
                               accept="image/*"
                               data-preview-target="nft-preview">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('upload_file') }}" />
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                            </span>
                        </div>
                        {{-- Preview Container for NFT --}}
                        <div id="nft-preview" style="display: none;"></div>
                        {{-- Show existing NFT if available --}}
                        <div id="existing-nft" class="mt-2" style="display: none;">
                            <small class="text-muted">Current NFT:</small>
                            <img id="existing-nft-img" src="" alt="Current NFT" class="img-fluid" style="max-width: 200px; border-radius: 4px;">
                        </div>
                    </div>

                    {{-- Wallet File Input with Preview --}}
                    <div class="form-group">
                        <label for="edit_wallet">{{ __('Wallet') }}</label>
                        <input type="file" 
                               name="wallet" 
                               id="edit_wallet" 
                               class="file-upload-default" 
                               accept="image/*"
                               data-preview-target="wallet-preview">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('upload_file') }}" />
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                            </span>
                        </div>
                        {{-- Preview Container for Wallet --}}
                        <div id="wallet-preview" style="display: none;"></div>
                        {{-- Show existing wallet if available --}}
                        <div id="existing-wallet" class="mt-2" style="display: none;">
                            <small class="text-muted">Current wallet:</small>
                            <img id="existing-wallet-img" src="" alt="Current wallet" class="img-fluid" style="max-width: 200px; border-radius: 4px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-theme">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Include the file preview script --}}
<script src="{{ asset('assets/js/file-preview.js') }}"></script>

<script>
// Example: Populate edit modal with existing data
function openEditModal(data) {
    // Populate form fields
    // ...
    
    // Show existing images if available
    if (data.artwork) {
        document.getElementById('existing-artwork-img').src = data.artwork;
        document.getElementById('existing-artwork').style.display = 'block';
    }
    if (data.nft) {
        document.getElementById('existing-nft-img').src = data.nft;
        document.getElementById('existing-nft').style.display = 'block';
    }
    if (data.wallet) {
        document.getElementById('existing-wallet-img').src = data.wallet;
        document.getElementById('existing-wallet').style.display = 'block';
    }
    
    // Open modal
    $('#editModal').modal('show');
}

// Hide existing images when new file is selected
$(document).on('change', '#edit_artwork, #edit_nft, #edit_wallet', function() {
    const fieldName = $(this).attr('id').replace('edit_', '');
    $('#existing-' + fieldName).hide();
});
</script>
