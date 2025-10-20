{{-- Add to top navigation if needed --}}
<li class="nav-item">
  <a class="nav-link {{ request()->routeIs('itr.*') ? 'active' : '' }}" href="{{ route('itr.summary', ['fy' => date('Y').'-'.substr(date('Y')+1,2,2)]) }}">
    <i class="ki-duotone ki-calculator fs-5 me-2"></i> ITR
  </a>
</li>
<li class="nav-item">
  <a class="nav-link {{ request()->routeIs('reminders.*') ? 'active' : '' }}" href="{{ route('reminders.index') }}">
    <i class="ki-duotone ki-alarm fs-5 me-2"></i> Reminders
  </a>
</li>
<li class="nav-item">
  <a class="nav-link {{ request()->routeIs('audits.*') ? 'active' : '' }}" href="{{ route('audits.index') }}">
    <i class="ki-duotone ki-activity fs-5 me-2"></i> Audit Logs
  </a>
</li>
