@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Item Master Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('item.update', $item->id) }}">
                         @csrf
                         @method('PUT')
                        
                        <div class="mb-3">
                            <label for="item_code" class="form-label">Item Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('item_code') is-invalid @enderror" 
                                   id="item_code" 
                                   name="item_code" 
                                   value="{{ old('item_code')??$item->item_code }}" 
                                   placeholder="Enter unique item code (e.g., item001)"
                                   maxlength="50"
                                   required>
                            @error('item_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 50 characters. Must be unique.</div>
                        </div>

                        <div class="mb-3">
                            <label for="item_name" class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('item_name') is-invalid @enderror" 
                                   id="item_name" 
                                   name="item_name" 
                                   value="{{ old('item_name')??$item->item_name }}" 
                                   placeholder="Enter item name"
                                   maxlength="255"
                                   required>
                            @error('item_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1" {{ (old('status')??$item->status) === 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('status')??$item->status) === 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update item Master
                            </button>
                            <a href="{{ route('item.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto-generate item code based on item name (optional)
    document.getElementById('item_name').addEventListener('input', function() {
        const itemName = this.value;
        const itemCodeField = document.getElementById('item_code');
        
        if (itemCodeField.value === '') {
            // Generate code from first 3 letters + random number
            const code = itemName.substring(0, 3).toUpperCase() + 
                         Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            itemCodeField.value = code;
        }
    });
</script>
@endpush