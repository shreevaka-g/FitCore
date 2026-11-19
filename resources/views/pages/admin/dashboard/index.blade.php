@extends('layouts.app', ['activePage' => 'dashboard', 'activeSection' => 'admin'])

@section('content')

<div class="dashboard-header mb-4">
    <h1 class="h3 fw-bold mb-1">Service Overview</h1>
    <p class="text-muted">Welcome back, Admin! Here's a summary of the gym activities.</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card animate-up" style="animation-delay: 0.1s">
            <div class="stat-icon bg-primary-soft">
                <i class="fas fa-car text-primary"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total customers</span>
                <h2 class="stat-value">452</h2>
                <span class="stat-trend text-success">
                    <i class="fas fa-arrow-up me-1"></i> 5% growth
                </span>
            </div>
        </div>
    </div>
</div>




<style>
    .stat-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        display: block;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
    }

    .stat-trend {
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Soft Backgrounds */
    .bg-primary-soft { background-color: rgba(79, 70, 229, 0.1); }
    .bg-success-soft { background-color: rgba(34, 197, 94, 0.1); }
    .bg-warning-soft { background-color: rgba(245, 158, 11, 0.1); }
    .bg-info-soft { background-color: rgba(6, 182, 212, 0.1); }
    .bg-danger-soft { background-color: rgba(239, 68, 68, 0.1); }

    .btn-icon-sm {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        transition: all 0.2s;
    }

    .btn-icon-sm:hover {
        background: #4f46e5;
        color: #fff;
        border-color: #4f46e5;
    }

    .animate-up {
        animation: slideUp 0.5s ease-out forwards;
        opacity: 0;
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

@endsection

@push('custom_scripts')
@endpush

