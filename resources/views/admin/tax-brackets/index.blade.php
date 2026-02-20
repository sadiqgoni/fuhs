@extends('components.layouts.app')

@section('title')
    Paye Calculation Formula
@endsection
@section('page_title')
    Payroll Settings / Paye Calculation Formula
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0" style="color: #333 !important;">PAYE CALCULATION FORMULA</h4>
                            <a href="{{ route('tax-brackets.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Formula
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($activeBracket)
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle"></i> Currently Active Formula</h5>
                                <strong>{{ $activeBracket->version_name }}</strong> - Effective:
                                {{ $activeBracket->effective_date->format('M d, Y') }}
                                <br><small class="text-muted">All new payroll calculations use this formula</small>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> No Active Formula</h5>
                                <strong>Warning:</strong> No formula is currently active. The system will use default
                                calculations.
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Version Name</th>
                                        <th>Effective Date</th>
                                        <th>Status</th>
                                        <th>Formula Details</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($taxBrackets as $bracket)
                                        <tr>
                                            <td>
                                                <strong>{{ $bracket->version_name }}</strong>
                                                @if($bracket->description)
                                                    <br><small
                                                        class="text-muted">{{ Str::limit($bracket->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $bracket->effective_date->format('M d, Y') }}</td>
                                            <td>
                                                @if($bracket->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($bracket->tax_brackets)
                                                    @foreach($bracket->getBracketSummary() as $summary)
                                                        <small class="d-block">{{ $summary }}</small>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Not configured</span>
                                                @endif
                                            </td>
                                            <td>{{ $bracket->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('tax-brackets.show', $bracket) }}"
                                                        class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('tax-brackets.edit', $bracket) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if(!$bracket->is_active)
                                                        <form action="{{ route('tax-brackets.activate', $bracket) }}" method="POST"
                                                            class="d-inline"
                                                            onsubmit="return confirm('Activate this formula? All future payroll calculations will use this formula.')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" title="Activate">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('tax-brackets.destroy', $bracket) }}" method="POST"
                                                            class="d-inline" onsubmit="return confirm('Delete this formula?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <br>No formulas found.
                                                <br><a href="{{ route('tax-brackets.create') }}">Create your first formula</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $taxBrackets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection