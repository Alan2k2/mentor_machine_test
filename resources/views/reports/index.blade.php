@extends('layout.app')

@section('content')
<div class="page-header mt-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Reports</h2>
        <p class="text-muted mb-0">Analytics and aggregates.</p>
    </div>
</div>

<div class="card border-0">
    <div class="card-header border-0 fs-5 d-flex align-items-center gap-2">
        <i class="ph-bold ph-chart-bar text-primary"></i> Supplier Spend Report
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Supplier ID</th>
                        <th>Supplier Name</th>
                        <th class="text-end">Total Spend (RECEIVED)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplierSpend as $spend)
                    <tr>
                        <td>#{{ str_pad($spend->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="fw-bold">{{ $spend->name }}</td>
                        <td class="text-end text-success fw-bold">₹{{ number_format($spend->total_spend, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">No spend data available yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
