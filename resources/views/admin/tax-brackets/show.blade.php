@extends('components.layouts.app')

@section('title')
    View Tax Bracket
@endsection

@section('page_title')
    Tax Bracket Details: {{ $taxBracket->version_name }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0" style="color: #333 !important;">{{ $taxBracket->version_name }}</h4>
                        <div>
                            @if(!$taxBracket->is_active)
                                <form action="{{ route('tax-brackets.activate', $taxBracket) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Activate this tax bracket? All future payroll calculations will use this bracket.')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Activate Bracket
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('tax-brackets.edit', $taxBracket) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('tax-brackets.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="mb-4">
                        @if($taxBracket->is_active)
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-check-circle"></i> ACTIVE - Currently used for all tax calculations
                            </span>
                        @else
                            <span class="badge badge-secondary badge-lg">
                                <i class="fas fa-pause-circle"></i> INACTIVE
                            </span>
                        @endif
                    </div>

                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Version Name:</strong></td>
                                            <td>{{ $taxBracket->version_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Effective Date:</strong></td>
                                            <td>{{ $taxBracket->effective_date->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($taxBracket->is_active)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-muted">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $taxBracket->created_at->format('F d, Y H:i') }}</td>
                                        </tr>
                                        @if($taxBracket->updated_at != $taxBracket->created_at)
                                        <tr>
                                            <td><strong>Last Updated:</strong></td>
                                            <td>{{ $taxBracket->updated_at->format('F d, Y H:i') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Description</h5>
                                </div>
                                <div class="card-body">
                                    @if($taxBracket->description)
                                        <p>{{ $taxBracket->description }}</p>
                                    @else
                                        <p class="text-muted">No description provided.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tax Brackets Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Tax Brackets</h5>
                            <small class="text-muted">Income ranges and corresponding tax rates</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Income Range</th>
                                            <th>Tax Rate</th>
                                            <th>Description</th>
                                            <th>Example Calculation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($taxBracket->tax_brackets)
                                            @foreach($taxBracket->tax_brackets as $index => $bracket)
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            ₦{{ number_format($bracket['min']) }}
                                                            -
                                                            @if(isset($bracket['max']))
                                                                ₦{{ number_format($bracket['max']) }}
                                                            @else
                                                                ∞ (No upper limit)
                                                            @endif
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            {{ $bracket['rate'] }}%
                                                        </span>
                                                    </td>
                                                    <td>{{ $bracket['description'] ?? 'N/A' }}</td>
                                                    <td>
                                                        @php
                                                            $min = $bracket['min'];
                                                            $max = $bracket['max'] ?? 10000000; // Example max for calculation
                                                            $rate = $bracket['rate'];

                                                            // Calculate tax for first ₦100,000 in this bracket
                                                            $exampleIncome = min(100000, $max - $min);
                                                            $exampleTax = $exampleIncome * ($rate / 100);
                                                        @endphp
                                                        <small class="text-muted">
                                                            ₦{{ number_format($exampleIncome) }} × {{ $rate }}% = ₦{{ number_format($exampleTax, 2) }}
                                                        </small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    No tax brackets defined.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tax Reliefs -->
                    @if($taxBracket->reliefs)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Tax Reliefs</h5>
                                <small class="text-muted">Configured tax relief amounts</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($taxBracket->reliefs as $key => $relief)
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h6 class="text-capitalize">{{ str_replace(['_', '.'], ' ', $key) }}</h6>
                                                    @if(isset($relief['fixed']))
                                                        <h4 class="text-success">₦{{ number_format($relief['fixed']) }}</h4>
                                                        <small class="text-muted">Fixed amount</small>
                                                    @elseif(isset($relief['percentage']))
                                                        <h4 class="text-success">{{ $relief['percentage'] }}%</h4>
                                                        <small class="text-muted">
                                                            @if(isset($relief['base']))
                                                                of {{ str_replace(['_', '.'], ' ', $relief['base']) }}
                                                            @else
                                                                percentage
                                                            @endif
                                                        </small>
                                                    @else
                                                        <span class="text-muted">Not configured</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection