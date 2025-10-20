{{-- Add to your sidebar where appropriate --}}
<li class="menu-item {{ request()->routeIs('itr.*') ? 'here show' : '' }}">
  <a class="menu-link" href="{{ route('itr.summary', ['fy' => date('Y').'-'.substr(date('Y')+1,2,2)]) }}">
    <span class="menu-icon"><i class="ki-duotone ki-calculator"></i></span>
    <span class="menu-title">ITR Summary</span>
  </a>
</li>
<li class="menu-item {{ request()->routeIs('reminders.*') ? 'here show' : '' }}">
  <a class="menu-link" href="{{ route('reminders.index') }}">
    <span class="menu-icon"><i class="ki-duotone ki-alarm"></i></span>
    <span class="menu-title">Reminders</span>
  </a>
</li>
<li class="menu-item {{ request()->routeIs('audits.*') ? 'here show' : '' }}">
  <a class="menu-link" href="{{ route('audits.index') }}">
    <span class="menu-icon"><i class="ki-duotone ki-activity"></i></span>
    <span class="menu-title">Audit Logs</span>
  </a>
</li>
