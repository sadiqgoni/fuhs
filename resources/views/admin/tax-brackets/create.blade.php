@extends('components.layouts.app')

@section('title')
    Create Tax Bracket
@endsection

@section('page_title')
    Create New PAYE Tax Bracket
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0" style="color: #333 !important;">Create Tax Bracket</h4>
                    <small class="text-muted">Define tax brackets for PAYE calculations</small>
                </div>

                <form action="{{ route('tax-brackets.store') }}" method="POST" id="taxBracketForm">
                    @csrf

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="version_name" class="required">Version Name</label>
                                    <input type="text" class="form-control @error('version_name') is-invalid @enderror"
                                           id="version_name" name="version_name" value="{{ old('version_name') }}"
                                           placeholder="e.g., PAYE 2026 Structure" required>
                                    @error('version_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="effective_date" class="required">Effective Date</label>
                                    <input type="date" class="form-control @error('effective_date') is-invalid @enderror"
                                           id="effective_date" name="effective_date" value="{{ old('effective_date', date('Y-m-d')) }}" required>
                                    @error('effective_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="2"
                                      placeholder="Describe this tax bracket version">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                       {{ old('is_active') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Make this the active tax bracket</strong>
                                </label>
                                <br><small class="text-muted">When checked, all payroll calculations will use this tax bracket</small>
                            </div>
                        </div>

                        <!-- Tax Brackets Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Tax Brackets</h5>
                                <small class="text-muted">Define income ranges and their corresponding tax rates</small>
                            </div>
                            <div class="card-body">
                                <div id="brackets-container">
                                    <!-- Dynamic brackets will be added here -->
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-bracket">
                                    <i class="fas fa-plus"></i> Add Tax Bracket
                                </button>
                            </div>
                        </div>

                        <!-- Tax Reliefs Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Tax Reliefs (Optional)</h5>
                                <small class="text-muted">Configure tax relief amounts</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="consolidated_rent_relief">Consolidated Rent Relief (₦)</label>
                                            <input type="number" class="form-control" id="consolidated_rent_relief"
                                                   name="reliefs[consolidated_rent_relief][fixed]" value="200000"
                                                   placeholder="200000">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pension_rate">Pension Rate (%)</label>
                                            <input type="number" class="form-control" id="pension_rate"
                                                   name="reliefs[pension_contribution][percentage]" value="8" step="0.01"
                                                   placeholder="8">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nhf_rate">NHF Rate (%)</label>
                                            <input type="number" class="form-control" id="nhf_rate"
                                                   name="reliefs[nhf_contribution][percentage]" value="2.5" step="0.01"
                                                   placeholder="2.5">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nhis_rate">NHIS Rate (%)</label>
                                            <input type="number" class="form-control" id="nhis_rate"
                                                   name="reliefs[nhis_contribution][percentage]" value="0.5" step="0.01"
                                                   placeholder="0.5">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('tax-brackets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Tax Bracket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Tax bracket form initializing...');

    let bracketCount = 0;

    // Get container
    const container = document.getElementById('brackets-container');
    if (!container) {
        console.error('Brackets container not found!');
        return;
    }

    // Clear and add initial bracket
    container.innerHTML = '';
    addBracket();

    // Add bracket button
    const addButton = document.getElementById('add-bracket');
    if (addButton) {
        addButton.onclick = function() {
            console.log('Add bracket clicked');
            addBracket();
        };
    }

    function addBracket(min, max, rate, description) {
        min = min || '';
        max = max || '';
        rate = rate || '';
        description = description || '';

        const bracketDiv = document.createElement('div');
        bracketDiv.className = 'bracket-item card mb-3';
        bracketDiv.dataset.index = bracketCount;
        bracketDiv.innerHTML = `
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="required">Min Income (₦)</label>
                        <input type="number" class="form-control" name="tax_brackets[${bracketCount}][min]"
                               value="${min}" placeholder="0" required>
                    </div>
                    <div class="col-md-3">
                        <label>Max Income (₦)</label>
                        <input type="number" class="form-control" name="tax_brackets[${bracketCount}][max]"
                               value="${max}" placeholder="Leave empty for no limit">
                    </div>
                    <div class="col-md-2">
                        <label class="required">Tax Rate (%)</label>
                        <input type="number" class="form-control" name="tax_brackets[${bracketCount}][rate]"
                               value="${rate}" step="0.01" min="0" max="100" placeholder="15.00" required>
                    </div>
                    <div class="col-md-3">
                        <label class="required">Description</label>
                        <input type="text" class="form-control" name="tax_brackets[${bracketCount}][description]"
                               value="${description}" placeholder="e.g., Next ₦2,200,000" required>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label><br>
                        <button type="button" class="btn btn-danger btn-sm remove-bracket-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(bracketDiv);
        bracketCount++;

        // Add remove functionality
        const removeBtn = bracketDiv.querySelector('.remove-bracket-btn');
        if (removeBtn) {
            removeBtn.onclick = function() {
                bracketDiv.remove();
                reindexBrackets();
            };
        }
    }

    function reindexBrackets() {
        const brackets = document.querySelectorAll('.bracket-item');
        brackets.forEach(function(bracket, index) {
            bracket.dataset.index = index;
            const inputs = bracket.querySelectorAll('input');
            inputs.forEach(function(input) {
                const name = input.name.replace(/\[\d+\]/, '[' + index + ']');
                input.name = name;
            });
        });
        bracketCount = brackets.length;
    }

    // Form validation
    const form = document.getElementById('taxBracketForm');
    if (form) {
        form.onsubmit = function(e) {
            const brackets = document.querySelectorAll('.bracket-item');
            if (brackets.length === 0) {
                e.preventDefault();
                alert('Please add at least one tax bracket.');
                return false;
            }
            return true;
        };
    }

    console.log('Tax bracket form initialized successfully');
});
</script>
@endpush