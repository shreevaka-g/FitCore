@extends('layouts.app', ['activePage' => 'membership-plans', 'activeSection' => 'admin'])

@section('content')

@php
    // Safe fallback handling for membership plans data:
    // If backend hasn't passed $membershipPlans or $plans yet, provide sample plans so UI is fully testable!
    $rawPlanList = $membershipPlans ?? $plans ?? null;
    $hasPaginator = isset($rawPlanList) && method_exists($rawPlanList, 'total');

    if (empty($rawPlanList) || (method_exists($rawPlanList, 'count') && $rawPlanList->count() == 0)) {
        // High quality demo membership plans matching MembershipPlan model
        $displayPlans = collect([
            (object)[
                'id' => 1,
                'name' => 'Annual VIP All-Access',
                'description' => 'Unlimited 24/7 gym floor access, all group classes, personal locker, sauna & steam room, 2 complimentary personal training sessions.',
                'price' => 299.00,
                'duration_value' => 12,
                'duration_unit' => 'months',
                'color_code' => 'vip',
                'sort_order' => 1,
                'is_active' => 1,
                'image_path' => null,
                'created_at' => now()->subMonths(6),
            ],
            (object)[
                'id' => 2,
                'name' => 'Quarterly Fitness Pro',
                'description' => 'Full gym floor & cardio area access, weekend group workouts, locker room access, monthly fitness assessment.',
                'price' => 120.00,
                'duration_value' => 3,
                'duration_unit' => 'months',
                'color_code' => 'platinum',
                'sort_order' => 2,
                'is_active' => 1,
                'image_path' => null,
                'created_at' => now()->subMonths(4),
            ],
            (object)[
                'id' => 3,
                'name' => 'Monthly Standard',
                'description' => 'Standard access to strength and cardio equipment during regular gym hours, locker room amenities.',
                'price' => 45.00,
                'duration_value' => 1,
                'duration_unit' => 'months',
                'color_code' => 'gold',
                'sort_order' => 3,
                'is_active' => 1,
                'image_path' => null,
                'created_at' => now()->subMonths(2),
            ],
            (object)[
                'id' => 4,
                'name' => 'Basic Off-Peak',
                'description' => 'Access during off-peak hours (10:00 AM - 4:00 PM), standard equipment access, free locker.',
                'price' => 25.00,
                'duration_value' => 1,
                'duration_unit' => 'months',
                'color_code' => 'silver',
                'sort_order' => 4,
                'is_active' => 1,
                'image_path' => null,
                'created_at' => now()->subMonths(1),
            ],
            (object)[
                'id' => 5,
                'name' => 'Single Day Pass',
                'description' => 'Single day full access pass to all gym facilities and one group class of choice.',
                'price' => 10.00,
                'duration_value' => 1,
                'duration_unit' => 'days',
                'color_code' => 'silver',
                'sort_order' => 5,
                'is_active' => 0,
                'image_path' => null,
                'created_at' => now()->subWeeks(2),
            ],
        ]);
        $totalCount = 5;
        $activeCount = 4;
        $inactiveCount = 1;
        $vipCount = 2;
    } else {
        $displayPlans = $rawPlanList;
        $totalCount = $hasPaginator ? $rawPlanList->total() : $rawPlanList->count();
        $activeCount = $displayPlans->where('is_active', 1)->count();
        $inactiveCount = $totalCount - $activeCount;
        $vipCount = $displayPlans->filter(function($p) {
            return stripos($p->color_code ?? '', 'vip') !== false || stripos($p->name ?? '', 'vip') !== false;
        })->count();
    }
@endphp

<!-- Page Header & Action Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3 animate-up">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h1 class="h3 fw-bold mb-0 text-dark">Membership Plans</h1>
            <span class="badge bg-indigo rounded-pill px-3 py-1 small fw-semibold" id="totalPlansBadge">
                {{ $totalCount }} Plans
            </span>
        </div>
        <p class="text-muted mb-0">Manage gym packages, pricing tiers, billing durations, and package perks.</p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-outline-custom" onclick="window.location.reload();" title="Refresh list">
            <i class="fas fa-sync-alt me-1"></i> Refresh
        </button>
        <button type="button" class="btn btn-indigo shadow-sm" data-bs-toggle="modal" data-bs-target="#createPlanModal">
            <i class="fas fa-plus-circle me-2"></i> Create New Plan
        </button>
    </div>
</div>

<!-- Quick Statistics Row (Clean Unified White Theme with Accent Border) -->
<div class="row g-3 mb-4 animate-up" style="animation-delay: 0.1s">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper">
                <i class="fas fa-id-card"></i>
            </div>
            <div>
                <span class="stat-label-modern">Total Plans</span>
                <h3 class="stat-value-modern" id="statTotalPlans">{{ $totalCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <span class="stat-label-modern">Active Plans</span>
                <h3 class="stat-value-modern" id="statActivePlans">{{ $activeCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div>
                <span class="stat-label-modern">Deactive / Hidden</span>
                <h3 class="stat-value-modern" id="statInactivePlans">{{ $inactiveCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper">
                <i class="fas fa-crown"></i>
            </div>
            <div>
                <span class="stat-label-modern">VIP / Featured</span>
                <h3 class="stat-value-modern" id="statVipPlans">{{ $vipCount }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4 animate-up" style="animation-delay: 0.15s">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.membership-plans.index') }}" class="row g-2 align-items-center">
            <!-- Search Keyword Input -->
            <div class="col-12 col-md-5 col-lg-5">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="sr" class="form-control form-control-modern" 
                           placeholder="Search plans by name, perks, or pricing..." 
                           value="{{ request('sr') }}">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="col-6 col-md-3 col-lg-3">
                <select name="status" class="form-select form-select-modern">
                    <option value="">All Statuses</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active Only</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Deactive Only</option>
                </select>
            </div>

            <!-- Duration Filter -->
            <div class="col-6 col-md-2 col-lg-2">
                <select name="duration" class="form-select form-select-modern">
                    <option value="">All Durations</option>
                    <option value="month" {{ request('duration') === 'month' ? 'selected' : '' }}>Monthly</option>
                    <option value="quarter" {{ request('duration') === 'quarter' ? 'selected' : '' }}>Quarterly (3 Mo)</option>
                    <option value="year" {{ request('duration') === 'year' ? 'selected' : '' }}>Annual (12 Mo)</option>
                    <option value="day" {{ request('duration') === 'day' ? 'selected' : '' }}>Daily / Trial</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-12 col-md-2 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-indigo flex-grow-1">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                @if(request('sr') || request('status') !== null || request('duration'))
                    <a href="{{ route('admin.membership-plans.index') }}" class="btn btn-outline-custom" title="Reset Filters">
                        <i class="fas fa-redo"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Membership Plans Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden animate-up" style="animation-delay: 0.2s">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-header-modern">
                    <tr>
                        <th class="ps-4 py-3">Plan Details</th>
                        <th class="py-3">Price & Billing</th>
                        <th class="py-3">Duration</th>
                        <th class="py-3">Features & Description</th>
                        <th class="py-3 text-center" style="width: 100px">Order</th>
                        <th class="py-3 text-center" style="width: 120px">Status</th>
                        <th class="pe-4 py-3 text-center" style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($displayPlans as $plan)
                        @php
                            $durationText = $plan->duration_value . ' ' . ucfirst($plan->duration_unit ?? 'months');
                            $colorCode = strtolower($plan->color_code ?? 'gold');
                            $priceFormatted = number_format($plan->price ?? 0, 2);
                        @endphp
                        <tr id="plan-row-{{ $plan->id }}" class="table-row-modern">
                            <!-- Plan Name & Tier Badge -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon-wrapper" style="width: 42px; height: 42px; border-radius: 10px; background: rgba(79, 70, 229, 0.08); color: var(--gym-primary); font-size: 1.15rem;">
                                        @if(stripos($colorCode, 'vip') !== false)
                                            <i class="fas fa-crown text-warning"></i>
                                        @elseif(stripos($colorCode, 'platinum') !== false)
                                            <i class="fas fa-medal text-primary"></i>
                                        @elseif(stripos($colorCode, 'gold') !== false)
                                            <i class="fas fa-fire text-amber"></i>
                                        @else
                                            <i class="fas fa-dumbbell text-secondary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0">{{ $plan->name }}</div>
                                        @if(stripos($colorCode, 'vip') !== false)
                                            <span class="badge badge-plan plan-vip">VIP Package</span>
                                        @elseif(stripos($colorCode, 'platinum') !== false)
                                            <span class="badge badge-plan plan-platinum">Pro Tier</span>
                                        @elseif(stripos($colorCode, 'gold') !== false)
                                            <span class="badge badge-plan plan-gold">Popular</span>
                                        @else
                                            <span class="badge badge-plan plan-silver">Standard</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Price -->
                            <td>
                                <div class="fw-bold text-dark fs-6">
                                    ${{ $priceFormatted }}
                                </div>
                                <div class="small text-muted">
                                    / {{ rtrim($plan->duration_unit ?? 'period', 's') }}
                                </div>
                            </td>

                            <!-- Duration -->
                            <td>
                                <div class="small text-dark fw-medium">
                                    <i class="far fa-clock text-muted me-1"></i> {{ $durationText }}
                                </div>
                            </td>

                            <!-- Description -->
                            <td style="max-width: 320px; white-space: normal;">
                                <div class="small text-muted text-truncate-2" title="{{ $plan->description }}">
                                    {{ Str::limit($plan->description, 75, '...') }}
                                </div>
                            </td>

                            <!-- Sort Order -->
                            <td class="text-center">
                                <span class="badge bg-light text-secondary border font-monospace px-2 py-1">
                                    #{{ $plan->sort_order ?? 1 }}
                                </span>
                            </td>

                            <!-- Active / Inactive Status Switch (Only Toggle, No text name) -->
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <label class="switch-ios" title="{{ $plan->is_active ? 'Active (Click to deactivate)' : 'Deactive (Click to activate)' }}">
                                        <input type="checkbox" 
                                               class="status-toggle-checkbox"
                                               data-id="{{ $plan->id }}"
                                               data-name="{{ $plan->name }}"
                                               {{ $plan->is_active ? 'checked' : '' }}>
                                        <span class="slider-ios"></span>
                                    </label>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="pe-4 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- View Action -->
                                    <button type="button" 
                                            class="btn-action btn-action-view view-plan-btn"
                                            data-id="{{ $plan->id }}"
                                            data-name="{{ $plan->name }}"
                                            data-price="{{ $priceFormatted }}"
                                            data-duration-value="{{ $plan->duration_value }}"
                                            data-duration-unit="{{ $plan->duration_unit }}"
                                            data-color-code="{{ $plan->color_code }}"
                                            data-sort-order="{{ $plan->sort_order }}"
                                            data-description="{{ $plan->description }}"
                                            data-active="{{ $plan->is_active ? 1 : 0 }}"
                                            title="View Plan Details">
                                        <i class="far fa-eye"></i>
                                    </button>

                                    <!-- Edit Action -->
                                    <button type="button" 
                                            class="btn-action btn-action-edit edit-plan-btn"
                                            data-id="{{ $plan->id }}"
                                            data-name="{{ $plan->name }}"
                                            data-price="{{ $plan->price }}"
                                            data-duration-value="{{ $plan->duration_value }}"
                                            data-duration-unit="{{ $plan->duration_unit }}"
                                            data-color-code="{{ $plan->color_code }}"
                                            data-sort-order="{{ $plan->sort_order }}"
                                            data-description="{{ $plan->description }}"
                                            data-active="{{ $plan->is_active ? 1 : 0 }}"
                                            title="Edit Plan">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <!-- Delete Action -->
                                    <button type="button" 
                                            class="btn-action btn-action-delete delete-plan-btn"
                                            data-id="{{ $plan->id }}"
                                            data-name="{{ $plan->name }}"
                                            title="Delete Plan">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <div class="mb-3 text-muted">
                                        <i class="fas fa-id-card fa-3x" style="opacity: 0.35;"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">No Membership Plans Found</h5>
                                    <p class="text-muted small mb-3">Create your first gym membership plan with pricing and perks.</p>
                                    <button type="button" class="btn btn-indigo btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#createPlanModal">
                                        <i class="fas fa-plus-circle me-1"></i> Create First Plan
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($hasPaginator && $rawPlanList->hasPages())
            {{ $rawPlanList->withQueryString()->links('components.paginations') }}
        @endif
    </div>
</div>


<!-- ========================================== -->
<!-- 1. CREATE NEW MEMBERSHIP PLAN MODAL         -->
<!-- ========================================== -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient border-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white text-primary p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="createPlanModalLabel">Create Membership Plan</h5>
                        <p class="small text-white-50 mb-0">Define pricing, duration, perks, and availability.</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="createPlanForm" method="POST" action="{{ route('admin.membership-plans.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <!-- Basic Plan Info -->
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-tag text-primary me-2"></i>Package Identity</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label class="form-label small fw-bold text-dark">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-modern" placeholder="e.g. Annual VIP All-Access" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-dark">Tier Style / Badge</label>
                            <select name="color_code" class="form-select form-select-modern">
                                <option value="vip">VIP Package (Crown / Premium)</option>
                                <option value="platinum">Platinum Tier (Pro / Medal)</option>
                                <option value="gold" selected>Gold Tier (Popular / Fire)</option>
                                <option value="silver">Silver Tier (Standard)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pricing & Duration Row -->
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-dollar-sign text-primary me-2"></i>Pricing & Duration Structure</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Price ($ USD) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 border" style="border-radius: 10px 0 0 10px;">$</span>
                                <input type="number" step="0.01" min="0" name="price" class="form-control form-control-modern" style="border-radius: 0 10px 10px 0;" placeholder="49.00" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Duration Value <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="duration_value" class="form-control form-control-modern" value="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Duration Unit <span class="text-danger">*</span></label>
                            <select name="duration_unit" class="form-select form-select-modern" required>
                                <option value="months" selected>Months</option>
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="years">Years</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sort Order & Status -->
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-sliders-h text-primary me-2"></i>Display Settings</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Sort / Display Order</label>
                            <input type="number" min="1" name="sort_order" class="form-control form-control-modern" value="1" placeholder="e.g. 1">
                            <small class="text-muted" style="font-size: 0.72rem;">Lower numbers appear first on registration forms</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Initial Status <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-select form-select-modern" required>
                                <option value="1" selected>Active (Available for purchase)</option>
                                <option value="0">Deactive (Hidden / Archived)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description & Perks -->
                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-list-check text-primary me-2"></i>Perks & Description</h6>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Included Features (Shown to gym members)</label>
                        <textarea name="description" rows="3" class="form-control form-control-modern" 
                                  placeholder="e.g. 24/7 gym access, sauna and steam room, locker room amenities, 1 free consultation with fitness trainer."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top bg-light p-3">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-indigo shadow-sm">
                        <i class="fas fa-check-circle me-1"></i> Save Membership Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ========================================== -->
<!-- 2. EDIT MEMBERSHIP PLAN MODAL               -->
<!-- ========================================== -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient border-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white text-primary p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="editPlanModalLabel">Edit Membership Plan</h5>
                        <p class="small text-white-50 mb-0">Update pricing, perks, and plan configuration.</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editPlanForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_plan_id" name="id">

                <div class="modal-body p-4">
                    <!-- Basic Plan Info -->
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-tag text-primary me-2"></i>Package Identity</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label class="form-label small fw-bold text-dark">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" id="edit_name" name="name" class="form-control form-control-modern" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-dark">Tier Style / Badge</label>
                            <select id="edit_color_code" name="color_code" class="form-select form-select-modern">
                                <option value="vip">VIP Package (Crown / Premium)</option>
                                <option value="platinum">Platinum Tier (Pro / Medal)</option>
                                <option value="gold">Gold Tier (Popular / Fire)</option>
                                <option value="silver">Silver Tier (Standard)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pricing & Duration Row -->
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-dollar-sign text-primary me-2"></i>Pricing & Duration Structure</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Price ($ USD) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 border" style="border-radius: 10px 0 0 10px;">$</span>
                                <input type="number" step="0.01" min="0" id="edit_price" name="price" class="form-control form-control-modern" style="border-radius: 0 10px 10px 0;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Duration Value <span class="text-danger">*</span></label>
                            <input type="number" min="1" id="edit_duration_value" name="duration_value" class="form-control form-control-modern" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Duration Unit <span class="text-danger">*</span></label>
                            <select id="edit_duration_unit" name="duration_unit" class="form-select form-select-modern" required>
                                <option value="months">Months</option>
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="years">Years</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sort Order & Status -->
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-sliders-h text-primary me-2"></i>Display Settings</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Sort / Display Order</label>
                            <input type="number" min="1" id="edit_sort_order" name="sort_order" class="form-control form-control-modern">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Status <span class="text-danger">*</span></label>
                            <select id="edit_is_active" name="is_active" class="form-select form-select-modern" required>
                                <option value="1">Active (Available for purchase)</option>
                                <option value="0">Deactive (Hidden / Archived)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description & Perks -->
                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-list-check text-primary me-2"></i>Perks & Description</h6>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Included Features</label>
                        <textarea id="edit_description" name="description" rows="3" class="form-control form-control-modern"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top bg-light p-3">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-indigo shadow-sm">
                        <i class="fas fa-save me-1"></i> Update Plan Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ========================================== -->
<!-- 3. VIEW MEMBERSHIP PLAN DETAILS MODAL       -->
<!-- ========================================== -->
<div class="modal fade" id="viewPlanModal" tabindex="-1" aria-labelledby="viewPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient border-0 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-id-badge text-warning fs-5"></i>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="viewPlanModalLabel">Membership Package Preview</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Package Hero Card Preview -->
                <div class="membership-card-preview mb-4 text-center">
                    <div class="d-inline-flex align-items-center gap-2 mb-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 text-white small">
                        <i class="fas fa-crown text-warning"></i>
                        <span id="view_tier" class="fw-semibold">VIP Tier</span>
                    </div>

                    <h3 class="fw-bold mb-1 text-white" id="view_name">Plan Name</h3>
                    <div class="display-6 fw-bold text-white mb-1" id="view_price_display">$299.00</div>
                    <div class="small text-white-50" id="view_duration_display">Duration: 12 Months</div>
                </div>

                <!-- Plan Info Tiles -->
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="info-tile">
                            <div class="info-tile-label"><i class="far fa-clock me-1"></i> Billing Cycle</div>
                            <div class="info-tile-value small" id="view_billing">12 Months</div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="info-tile">
                            <div class="info-tile-label"><i class="fas fa-sort-numeric-down me-1"></i> Sort Priority</div>
                            <div class="info-tile-value small" id="view_order">Priority #1</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-tile">
                            <div class="info-tile-label"><i class="fas fa-check-circle me-1"></i> Included Perks & Access</div>
                            <div class="info-tile-value small fw-normal text-muted" id="view_description" style="line-height: 1.5;">
                                Full access to all gym facilities.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top bg-light p-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-indigo" id="viewModalEditBtn">
                    <i class="fas fa-pencil-alt me-1"></i> Edit This Plan
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ========================================== -->
<!-- 4. DELETE MEMBERSHIP PLAN CONFIRMATION MODAL-->
<!-- ========================================== -->
<div class="modal fade" id="deletePlanModal" tabindex="-1" aria-labelledby="deletePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4 text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; background: rgba(15, 23, 42, 0.08); color: #0f172a;">
                    <i class="fas fa-trash-alt fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Delete Membership Plan?</h5>
                <p class="text-muted small mb-3">
                    Are you sure you want to delete <strong id="delete_plan_name" class="text-dark">Plan Name</strong>?
                    Members currently enrolled in this plan will keep their active period, but no new members will be able to select it.
                </p>

                <form id="deletePlanForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-custom px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark px-4 shadow-sm" style="background-color: #0f172a; border-color: #0f172a;">
                            <i class="far fa-trash-alt me-1"></i> Yes, Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@push('custom_scripts')
<script type="text/javascript">
    $(document).ready(function() {

        // Global CSRF Token setup for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ========================================================
        // 1. DYNAMIC ACTIVE / DEACTIVE STATUS TOGGLE (AJAX Switch)
        // Web Theme: Indigo / Slate (No Red/Green)
        // ========================================================
        $('.status-toggle-checkbox').on('change', function() {
            const checkbox = $(this);
            const planId = checkbox.data('id');
            const planName = checkbox.data('name') || 'Plan';
            const newStatus = checkbox.prop('checked') ? 1 : 0;

            // Disable switch momentarily to prevent double triggers
            checkbox.prop('disabled', true);

            // Use status route if available, otherwise resource update endpoint
            const statusUrl = "{{ Route::has('admin.update.membership-plan.status') ? route('admin.update.membership-plan.status') : url('admin/membership-plans') }}/" + planId;

            $.ajax({
                url: statusUrl,
                type: 'POST',
                data: {
                    id: planId,
                    status: newStatus,
                    _method: 'PATCH'
                },
                success: function(response) {
                    checkbox.prop('disabled', false);

                    if (newStatus === 1) {
                        checkbox.closest('.switch-ios').attr('title', 'Active (Click to deactivate)');
                        toastr.success(`${planName} is now active!`, 'Plan Updated');
                    } else {
                        checkbox.closest('.switch-ios').attr('title', 'Deactive (Click to activate)');
                        toastr.info(`${planName} has been deactivated.`, 'Plan Updated');
                    }

                    // Dynamically recalculate stats counters
                    updateStatsCounters();
                },
                error: function(xhr) {
                    checkbox.prop('disabled', false);
                    // Revert checkbox state on failure
                    checkbox.prop('checked', !newStatus);
                    toastr.error('Failed to change plan status. Please check backend connection.', 'Error');
                }
            });
        });

        // Helper to recalculate active/inactive badges dynamically
        function updateStatsCounters() {
            let active = 0;
            let inactive = 0;

            $('.status-toggle-checkbox').each(function() {
                if ($(this).prop('checked')) {
                    active++;
                } else {
                    inactive++;
                }
            });

            $('#statActivePlans').text(active);
            $('#statInactivePlans').text(inactive);
        }


        // ========================================================
        // 2. VIEW MEMBERSHIP PLAN DETAILS MODAL POPULATION
        // ========================================================
        $('.view-plan-btn').on('click', function() {
            const btn = $(this);
            const id = btn.data('id');
            const name = btn.data('name');
            const price = btn.data('price');
            const durationValue = btn.data('duration-value');
            const durationUnit = btn.data('duration-unit') || 'months';
            const colorCode = (btn.data('color-code') || 'gold').toUpperCase();
            const sortOrder = btn.data('sort-order') || 1;
            const description = btn.data('description') || 'No description provided.';

            // Populate view elements
            $('#view_name').text(name);
            $('#view_price_display').text('$' + price);
            $('#view_tier').text(colorCode + ' Tier');
            $('#view_duration_display').text(`Duration: ${durationValue} ${durationUnit}`);
            $('#view_billing').text(`${durationValue} ${durationUnit}`);
            $('#view_order').text(`Priority #${sortOrder}`);
            $('#view_description').text(description);

            // Bind edit button inside view modal to trigger edit modal
            $('#viewModalEditBtn').off('click').on('click', function() {
                $('#viewPlanModal').modal('hide');
                btn.closest('tr').find('.edit-plan-btn').trigger('click');
            });

            $('#viewPlanModal').modal('show');
        });


        // ========================================================
        // 3. EDIT MEMBERSHIP PLAN MODAL POPULATION
        // ========================================================
        $('.edit-plan-btn').on('click', function() {
            const btn = $(this);
            const id = btn.data('id');
            const name = btn.data('name');
            const price = btn.data('price');
            const durationValue = btn.data('duration-value');
            const durationUnit = btn.data('duration-unit');
            const colorCode = btn.data('color-code') || 'gold';
            const sortOrder = btn.data('sort-order');
            const description = btn.data('description');
            const isActive = btn.data('active');

            // Populate form fields
            $('#edit_plan_id').val(id);
            $('#edit_name').val(name);
            $('#edit_price').val(price);
            $('#edit_duration_value').val(durationValue);
            $('#edit_duration_unit').val(durationUnit);
            $('#edit_color_code').val(colorCode);
            $('#edit_sort_order').val(sortOrder);
            $('#edit_description').val(description);
            $('#edit_is_active').val(isActive);

            // Dynamically set form action route
            const updateUrl = "{{ route('admin.membership-plans.update', ':id') }}".replace(':id', id);
            $('#editPlanForm').attr('action', updateUrl);

            $('#editPlanModal').modal('show');
        });


        // ========================================================
        // 4. DELETE MEMBERSHIP PLAN CONFIRMATION
        // ========================================================
        $('.delete-plan-btn').on('click', function() {
            const btn = $(this);
            const id = btn.data('id');
            const name = btn.data('name');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Membership Plan?',
                    html: `Are you sure you want to delete <strong>${name}</strong>?<br><small class="text-muted">Members currently active on this plan will retain their access.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0f172a',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="far fa-trash-alt me-1"></i> Yes, Delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const deleteUrl = "{{ route('admin.membership-plans.update', ':id') }}".replace(':id', id); // Resource destroy
                        
                        const form = $('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.membership-plans.destroy', ':id') }}".replace(':id', id)
                        });
                        form.append($('<input>', { type: 'hidden', name: '_token', value: $('meta[name="csrf-token"]').attr('content') }));
                        form.append($('<input>', { type: 'hidden', name: '_method', value: 'DELETE' }));
                        $('body').append(form);
                        form.submit();
                    }
                });
            } else {
                // Fallback to Bootstrap Modal
                $('#delete_plan_name').text(name);
                const deleteUrl = "{{ route('admin.membership-plans.destroy', ':id') }}".replace(':id', id);
                $('#deletePlanForm').attr('action', deleteUrl);
                $('#deletePlanModal').modal('show');
            }
        });

    });
</script>
@endpush
