@extends('layouts.app')
@section('title','Reminders')

@section('content')
    <!-- Add New Reminder Card -->
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ki-duotone ki-alarm fs-2 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Add New Reminder
            </h3>
        </div>
        <div class="card-body">
            @if(session('ok')) 
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('ok') }}
                </div> 
            @endif

            <form method="post" action="{{ route('reminders.store') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label class="form-label fw-semibold required">Title</label>
                    <input name="title" class="form-control form-control-solid" required placeholder="e.g., GST Filing Due">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Message</label>
                    <input name="message" class="form-control form-control-solid" placeholder="Optional description or notes">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold required">Due Date & Time</label>
                    <input name="due_at" type="datetime-local" class="form-control form-control-solid" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reminders List Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="ki-duotone ki-notification-bing fs-2 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                Your Reminders
            </h3>
            <div class="d-flex gap-2">
                <button class="btn btn-light-warning" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                    <i class="ki-duotone ki-setting-3 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Bulk Actions
                </button>
                <button class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="ki-duotone ki-funnel fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Filter
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Summary Stats -->
            <div class="row g-5 mb-8">
                <div class="col-xl-3">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100" style="background-color: #7239EA; background-image:url('{{ asset('assets/media/patterns/vector-1.png') }}')">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $rows->count() }}</span>
                                </div>
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Reminders</span>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-end pt-0">
                            <div class="d-flex align-items-center flex-column mt-3 w-100">
                                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                                    <span>{{ $rows->where('is_sent', false)->count() }} Active</span>
                                    <span>100%</span>
                                </div>
                                <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                                    <div class="bg-white rounded h-8px" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $rows->where('is_sent', false)->count() }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Pending</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-end pe-0">
                            @php 
                                $overdueCount = $rows->where('is_sent', false)->where('due_at', '<', now())->count();
                            @endphp
                            <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">{{ $overdueCount }} Overdue</span>
                            <div class="d-flex align-items-center flex-column mt-3 w-100">
                                @php $pendingPercentage = $rows->count() > 0 ? ($rows->where('is_sent', false)->count() / $rows->count()) * 100 : 0; @endphp
                                <div class="d-flex justify-content-between fw-bold fs-6 text-gray-400 w-100 mt-auto mb-2">
                                    <span>{{ number_format($pendingPercentage, 1) }}%</span>
                                    <span>of total</span>
                                </div>
                                <div class="h-8px mx-3 w-100 bg-gray-300 rounded">
                                    <div class="bg-warning rounded h-8px" role="progressbar" style="width: {{ $pendingPercentage }}%;" aria-valuenow="{{ $pendingPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $rows->where('is_sent', true)->count() }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Completed</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-end pe-0">
                            @php $completedPercentage = $rows->count() > 0 ? ($rows->where('is_sent', true)->count() / $rows->count()) * 100 : 0; @endphp
                            <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">{{ number_format($completedPercentage, 1) }}% Complete</span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                @php $upcomingCount = $rows->where('is_sent', false)->where('due_at', '>', now())->where('due_at', '<=', now()->addDays(7))->count(); @endphp
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $upcomingCount }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">This Week</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-end pe-0">
                            @php $tomorrowCount = $rows->where('is_sent', false)->whereBetween('due_at', [now()->startOfDay()->addDay(), now()->endOfDay()->addDay()])->count(); @endphp
                            <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">{{ $tomorrowCount }} Tomorrow</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reminders Table -->
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="kt_table_reminders">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_table_reminders .form-check-input" value="1">
                                </div>
                            </th>
                            <th class="min-w-200px">Title & Message</th>
                            <th class="min-w-150px">Due Date</th>
                            <th class="min-w-100px">Priority</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-80px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="{{ $r->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            <div class="symbol-label bg-light-{{ $r->is_sent ? 'success' : ($r->due_at < now() ? 'danger' : 'warning') }}">
                                                @if($r->is_sent)
                                                    <i class="ki-duotone ki-check fs-2 text-success">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                @elseif($r->due_at < now())
                                                    <i class="ki-duotone ki-warning fs-2 text-danger">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                @else
                                                    <i class="ki-duotone ki-alarm fs-2 text-warning">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="text-gray-800 fw-bold">{{ $r->title }}</div>
                                            @if($r->message)
                                                <div class="text-muted fs-7">{{ Str::limit($r->message, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-gray-800 fw-semibold">{{ \Carbon\Carbon::parse($r->due_at)->format('d M Y') }}</div>
                                    <div class="text-muted fs-7">{{ \Carbon\Carbon::parse($r->due_at)->format('h:i A') }}</div>
                                    <div class="text-{{ $r->due_at < now() ? 'danger' : 'muted' }} fs-8">
                                        {{ \Carbon\Carbon::parse($r->due_at)->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $now = now();
                                        $dueDate = \Carbon\Carbon::parse($r->due_at);
                                        $diffInHours = $now->diffInHours($dueDate, false);
                                        
                                        if ($r->is_sent) {
                                            $priority = 'completed';
                                            $priorityColor = 'success';
                                            $priorityText = 'Completed';
                                        } elseif ($diffInHours < 0) {
                                            $priority = 'overdue';
                                            $priorityColor = 'danger';
                                            $priorityText = 'Overdue';
                                        } elseif ($diffInHours <= 24) {
                                            $priority = 'urgent';
                                            $priorityColor = 'warning';
                                            $priorityText = 'Urgent';
                                        } elseif ($diffInHours <= 72) {
                                            $priority = 'high';
                                            $priorityColor = 'info';
                                            $priorityText = 'High';
                                        } else {
                                            $priority = 'normal';
                                            $priorityColor = 'secondary';
                                            $priorityText = 'Normal';
                                        }
                                    @endphp
                                    <span class="badge badge-light-{{ $priorityColor }} fw-bold">{{ $priorityText }}</span>
                                </td>
                                <td>
                                    @if($r->is_sent)
                                        <span class="badge badge-light-success fw-bold">
                                            <i class="ki-duotone ki-check fs-7 me-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Sent
                                        </span>
                                    @else
                                        <span class="badge badge-light-warning fw-bold">
                                            <i class="ki-duotone ki-time fs-7 me-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end flex-shrink-0">
                                        @if(!$r->is_sent)
                                            <a href="#" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1" title="Mark as Sent">
                                                <i class="ki-duotone ki-check fs-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </a>
                                        @endif
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Reminder">
                                            <i class="ki-duotone ki-pencil fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete Reminder" 
                                           onclick="return confirm('Are you sure you want to delete this reminder?')">
                                            <i class="ki-duotone ki-trash fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10">
                                    <div class="text-gray-400 fs-6">
                                        <i class="ki-duotone ki-alarm fs-3x mb-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <br>
                                        No reminders set yet.
                                        <br>
                                        Use the form above to add your first reminder.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="modal-title text-white">
                        <i class="ki-duotone ki-funnel fs-2 text-white me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Filter Reminders
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1 text-white">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body">
                    <form method="get">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select form-select-solid">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="sent">Sent</option>
                                    <option value="overdue">Overdue</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Priority</label>
                                <select name="priority" class="form-select form-select-solid">
                                    <option value="">All Priorities</option>
                                    <option value="urgent">Urgent (24h)</option>
                                    <option value="high">High (3 days)</option>
                                    <option value="normal">Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">From Date</label>
                                <input type="date" name="from" class="form-control form-control-solid">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">To Date</label>
                                <input type="date" name="to" class="form-control form-control-solid">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h3 class="modal-title text-white">
                        <i class="ki-duotone ki-setting-3 fs-2 text-white me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Bulk Actions
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-warning ms-2" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1 text-white">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-5">
                        <i class="ki-duotone ki-setting-3 fs-3x text-warning mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <h4>Bulk Actions</h4>
                        <p class="text-muted">Select reminders and choose an action</p>
                    </div>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <button class="btn btn-light-success">
                            <i class="ki-duotone ki-check fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Mark as Sent
                        </button>
                        <button class="btn btn-light-danger">
                            <i class="ki-duotone ki-trash fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            Delete Selected
                        </button>
                        <button class="btn btn-light-primary">
                            <i class="ki-duotone ki-notification-bing fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Send Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
