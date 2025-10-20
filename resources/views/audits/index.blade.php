@extends('layouts.app')

@section('title', 'Audit Logs')

@section('styles')
<style>
/* Audit Page Styles */
.audit-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  margin-bottom: 2rem;
  overflow: hidden;
  position: relative;
}

.audit-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.2) 0%, transparent 70%);
  pointer-events: none;
}

.audit-header .card-body {
  position: relative;
  z-index: 1;
}

.audit-stats {
  margin-bottom: 2rem;
}

.stat-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
  border: 1px solid rgba(99, 126, 234, 0.1);
  border-radius: 12px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #667eea, #764ba2);
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(99, 126, 234, 0.15);
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.stat-icon.created {
  background: linear-gradient(45deg, #4ade80, #22c55e);
  color: white;
}

.stat-icon.updated {
  background: linear-gradient(45deg, #60a5fa, #3b82f6);
  color: white;
}

.stat-icon.deleted {
  background: linear-gradient(45deg, #f87171, #ef4444);
  color: white;
}

.stat-icon.viewed {
  background: linear-gradient(45deg, #a78bfa, #8b5cf6);
  color: white;
}

.filters-section {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 15px;
  margin-bottom: 2rem;
}

.audit-table {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 15px;
  overflow: hidden;
}

.table thead th {
  background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
  border: none;
  padding: 1.2rem 1rem;
  font-weight: 600;
  color: #374151;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.table tbody tr {
  border: none;
  transition: all 0.3s ease;
  cursor: pointer;
}

.table tbody tr:hover {
  background: linear-gradient(135deg, rgba(99, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
  transform: translateX(5px);
}

.table tbody td {
  border: none;
  padding: 1.2rem 1rem;
  vertical-align: middle;
}

.timestamp {
  display: flex;
  flex-direction: column;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(45deg, #667eea, #764ba2);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.875rem;
}

.action-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.action-created {
  background: linear-gradient(45deg, #dcfce7, #bbf7d0);
  color: #166534;
  border: 1px solid #22c55e;
}

.action-updated {
  background: linear-gradient(45deg, #dbeafe, #bfdbfe);
  color: #1e40af;
  border: 1px solid #3b82f6;
}

.action-deleted {
  background: linear-gradient(45deg, #fee2e2, #fecaca);
  color: #991b1b;
  border: 1px solid #ef4444;
}

.action-viewed {
  background: linear-gradient(45deg, #f3e8ff, #e9d5ff);
  color: #6b21a8;
  border: 1px solid #8b5cf6;
}

.model-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.4rem 0.8rem;
  background: linear-gradient(45deg, #f1f5f9, #e2e8f0);
  color: #475569;
  border-radius: 15px;
  font-size: 0.75rem;
  font-weight: 500;
  border: 1px solid #cbd5e1;
}

.ip-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.4rem 0.8rem;
  background: linear-gradient(45deg, #f8fafc, #f1f5f9);
  color: #64748b;
  border-radius: 12px;
  font-size: 0.75rem;
  font-family: 'Courier New', monospace;
  border: 1px solid #e2e8f0;
}

.changes-preview {
  max-width: 250px;
}

.changes-preview > div {
  font-size: 0.75rem;
  line-height: 1.4;
}

.empty-state {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-state-icon {
  width: 80px;
  height: 80px;
  margin: 0 auto 1.5rem;
  background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: #9ca3af;
}

.pagination-wrapper {
  margin-top: 2rem;
  padding: 1.5rem;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  text-align: center;
}

.export-btn {
  background: linear-gradient(45deg, #10b981, #059669);
  border: none;
  color: white;
  transition: all 0.3s ease;
}

.export-btn:hover {
  background: linear-gradient(45deg, #059669, #047857);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
}

.filter-btn {
  background: linear-gradient(45deg, #667eea, #764ba2);
  border: none;
  color: white;
  transition: all 0.3s ease;
}

.filter-btn:hover {
  background: linear-gradient(45deg, #5a67d8, #6b46c1);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.clear-filters-btn {
  background: linear-gradient(45deg, #64748b, #475569);
  border: none;
  color: white;
  transition: all 0.3s ease;
}

.clear-filters-btn:hover {
  background: linear-gradient(45deg, #475569, #334155);
  transform: translateY(-2px);
}

/* Loading states */
.loading {
  opacity: 0.6;
  pointer-events: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .audit-header h1 {
    font-size: 1.5rem;
  }
  
  .stat-card {
    margin-bottom: 1rem;
  }
  
  .user-info {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .table-responsive {
    font-size: 0.875rem;
  }
  
  .changes-preview {
    max-width: 150px;
  }
}

/* Animation keyframes */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

/* Scrollbar styling */
.table-responsive::-webkit-scrollbar {
  height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background: linear-gradient(90deg, #667eea, #764ba2);
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(90deg, #5a67d8, #6b46c1);
}
    color: white;
    margin-bottom: 2rem;
  }
  
  .audit-header {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px 20px 0 0;
    border: none;
/* Scrollbar styling */
.table-responsive::-webkit-scrollbar {
  height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background: linear-gradient(90deg, #667eea, #764ba2);
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(90deg, #5a67d8, #6b46c1);
}
  }
</style>
@endsection

@section('content')
<div class="container-fluid px-6 py-4">
  
  {{-- Page Header --}}
  <div class="card audit-header mb-6">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <h1 class="card-title mb-1" style="color: white; font-size: 1.75rem; font-weight: 800;">
            <i class="ki-duotone ki-shield-search fs-2 me-2">
              <span class="path1"></span>
              <span class="path2"></span>
              <span class="path3"></span>
            </i>
            Audit Logs
          </h1>
          <p class="mb-0" style="color: rgba(255,255,255,0.8); font-size: 1rem;">
            Track all system activities and changes
          </p>
        </div>
        <div class="d-flex gap-2">
          <button class="btn export-btn">
            <i class="ki-duotone ki-file-down me-2">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
            Export
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Statistics Row --}}
  <div class="audit-stats mb-6">
    <div class="row g-4">
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card stat-card">
          <div class="card-body text-center">
            <div class="stat-icon created mx-auto">
              <i class="ki-duotone ki-chart-pie-simple">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </div>
            <h3 class="fw-bold text-gray-800 mb-1">{{ $logs->total() }}</h3>
            <div class="text-muted fw-semibold">Total Logs</div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card stat-card">
          <div class="card-body text-center">
            <div class="stat-icon created mx-auto">
              <i class="ki-duotone ki-plus-circle">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </div>
            <h3 class="fw-bold text-gray-800 mb-1">{{ $logs->where('action', 'created')->count() }}</h3>
            <div class="text-muted fw-semibold">Created</div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card stat-card">
          <div class="card-body text-center">
            <div class="stat-icon updated mx-auto">
              <i class="ki-duotone ki-pencil">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </div>
            <h3 class="fw-bold text-gray-800 mb-1">{{ $logs->where('action', 'updated')->count() }}</h3>
            <div class="text-muted fw-semibold">Updated</div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card stat-card">
          <div class="card-body text-center">
            <div class="stat-icon deleted mx-auto">
              <i class="ki-duotone ki-trash">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
                <span class="path5"></span>
              </i>
            </div>
            <h3 class="fw-bold text-gray-800 mb-1">{{ $logs->where('action', 'deleted')->count() }}</h3>
            <div class="text-muted fw-semibold">Deleted</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="card filters-section mb-6">
    <div class="card-body">
      <form method="GET" class="row g-4 align-items-end">
        <div class="col-lg-3 col-md-6">
          <label class="form-label fw-bold">Action Type</label>
          <select name="action" class="form-select">
            <option value="">All Actions</option>
            <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
            <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
            <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-6">
          <label class="form-label fw-bold">Model Type</label>
          <select name="model_type" class="form-select">
            <option value="">All Models</option>
            @foreach($logs->pluck('model_type')->unique()->filter() as $model)
              <option value="{{ $model }}" {{ request('model_type') === $model ? 'selected' : '' }}>
                {{ class_basename($model) }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-2 col-md-4">
          <label class="form-label fw-bold">From Date</label>
          <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-lg-2 col-md-4">
          <label class="form-label fw-bold">To Date</label>
          <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-lg-2 col-md-4">
          <div class="d-flex gap-2">
            <button type="submit" class="btn filter-btn flex-grow-1">
              <i class="ki-duotone ki-magnifier me-1">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              Filter
            </button>
            <a href="{{ route('audits.index') }}" class="btn clear-filters-btn">
              <i class="ki-duotone ki-cross">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
  {{-- Audit Logs Table --}}
  <div class="card audit-table">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle gs-0 gy-4 mb-0">
          <thead>
            <tr>
              <th class="min-w-150px">Timestamp</th>
              <th class="min-w-125px">User</th>
              <th class="min-w-100px">Action</th>
              <th class="min-w-125px">Model</th>
              <th class="min-w-100px">Record ID</th>
              <th class="min-w-200px">Changes</th>
              <th class="min-w-100px">IP Address</th>
            </tr>
          </thead>
          <tbody>
            @forelse($logs as $log)
              <tr>
                <td>
                  <div class="timestamp">
                    <div class="fw-bold text-gray-800">{{ $log->created_at->format('M d, Y') }}</div>
                    <div class="fs-7 text-muted">{{ $log->created_at->format('H:i:s') }}</div>
                  </div>
                </td>
                <td>
                  <div class="user-info">
                    <div class="user-avatar">
                      {{ $log->user_id ? strtoupper(substr($log->user->name ?? 'U', 0, 1)) : 'S' }}
                    </div>
                    <div>
                      <div class="fw-bold text-gray-800 fs-7">
                        {{ $log->user->name ?? 'System' }}
                      </div>
                      <div class="fs-8 text-muted">
                        ID: {{ $log->user_id ?? 'N/A' }}
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="action-badge action-{{ strtolower($log->action) }}">
                    {{ ucfirst($log->action) }}
                  </span>
                </td>
                <td>
                  <span class="model-badge">
                    {{ class_basename($log->model_type) }}
                  </span>
                </td>
                <td>
                  <div class="fw-bold text-gray-800">#{{ $log->model_id }}</div>
                </td>
                <td>
                  @if($log->changes && !empty($log->changes))
                    <div class="changes-preview">
                      @php
                        $changes = is_string($log->changes) ? json_decode($log->changes, true) : $log->changes;
                        $changeCount = is_array($changes) ? count($changes) : 0;
                      @endphp
                      @if($changeCount > 0)
                        <div class="fw-bold mb-1 text-primary">{{ $changeCount }} field(s) changed</div>
                        @foreach(array_slice($changes, 0, 3) as $field => $value)
                          <div class="mb-1">
                            <span class="text-muted">{{ $field }}:</span>
                            <span class="text-gray-800">{{ is_array($value) ? json_encode($value) : (strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value) }}</span>
                          </div>
                        @endforeach
                        @if($changeCount > 3)
                          <div class="text-muted fs-8">... and {{ $changeCount - 3 }} more</div>
                        @endif
                      @else
                        <span class="text-muted fs-7">No changes recorded</span>
                      @endif
                    </div>
                  @else
                    <span class="text-muted fs-7">No changes</span>
                  @endif
                </td>
                <td>
                  <span class="ip-badge">{{ $log->ip ?? 'Unknown' }}</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7">
                  <div class="empty-state">
                    <div class="empty-state-icon">
                      <i class="ki-duotone ki-file-search">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                      </i>
                    </div>
                    <h4 class="fw-bold mb-2">No Audit Logs Found</h4>
                    <p class="mb-0">No audit logs match your current filters. Try adjusting your search criteria.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Pagination --}}
  @if($logs->hasPages())
    <div class="pagination-wrapper">
      {{ $logs->appends(request()->query())->links() }}
    </div>
  @endif

</div>

{{-- Enhanced Detail Modal --}}
<div class="modal fade" id="auditDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Audit Log Details</h3>
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
          <i class="ki-duotone ki-cross fs-1">
            <span class="path1"></span>
            <span class="path2"></span>
          </i>
        </div>
      </div>
      <div class="modal-body">
        <div id="auditDetailContent">
          <!-- Content will be loaded dynamically -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Enhanced table row interactions
  const tableRows = document.querySelectorAll('.table tbody tr');
  
  tableRows.forEach(row => {
    row.addEventListener('click', function() {
      // Add click animation
      this.style.transform = 'scale(0.98)';
      setTimeout(() => {
        this.style.transform = 'scale(1)';
      }, 150);
    });
  });

  // Auto-refresh functionality
  let autoRefresh = false;
  const refreshInterval = 30000; // 30 seconds
  let refreshTimer;

  function startAutoRefresh() {
    if (autoRefresh) {
      refreshTimer = setTimeout(() => {
        window.location.reload();
      }, refreshInterval);
    }
  }

  // Add auto-refresh toggle (could be added to UI)
  document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'r') {
      e.preventDefault();
      autoRefresh = !autoRefresh;
      if (autoRefresh) {
        console.log('Auto-refresh enabled');
        startAutoRefresh();
      } else {
        console.log('Auto-refresh disabled');
        clearTimeout(refreshTimer);
      }
    }
  });

  // Enhanced filtering with live search
  const filterForm = document.querySelector('form');
  if (filterForm) {
    const inputs = filterForm.querySelectorAll('select, input');
    inputs.forEach(input => {
      input.addEventListener('change', function() {
        // Could implement live filtering here
        console.log('Filter changed:', this.name, this.value);
      });
    });
  }

  // Export functionality
  const exportBtn = document.querySelector('.export-btn');
  if (exportBtn) {
    exportBtn.addEventListener('click', function() {
      // Add loading state
      const originalText = this.innerHTML;
      this.innerHTML = '<i class="ki-duotone ki-loading fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>Exporting...';
      this.disabled = true;
      
      // Simulate export process
      setTimeout(() => {
        this.innerHTML = originalText;
        this.disabled = false;
        
        // Show success message
        alert('Export completed! Check your downloads folder.');
      }, 2000);
    });
  }

  // Tooltip initialization for truncated content
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>
@endsection
