<aside class="fixed inset-y-0 left-0 z-30 flex flex-col overflow-hidden border-r border-slate-800 bg-slate-900 text-slate-100 transition-all duration-200" :class="sidebarOpen ? 'w-64' : 'w-16'">
  <div class="flex items-center gap-3 border-b border-slate-800 px-4 py-4">
    <div class="flex h-10 w-10 items-center justify-center rounded bg-gradient-to-r from-blue-600 to-cyan-400 font-bold text-white">EF</div>
    <div class="min-w-0 flex-1" x-show="sidebarOpen">
      <div class="text-sm font-semibold">EstateFlow ERP</div>
      <div class="text-xs text-slate-300">Real Estate ERP</div>
    </div>
    <button type="button" @click="sidebarOpen = !sidebarOpen" class="ml-auto text-slate-400 hover:text-white focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
    </button>
  </div>

  <nav class="flex-1 space-y-4 overflow-auto px-2 py-4">
    <div>
      <div class="px-3 text-xs uppercase tracking-wide text-slate-400" x-show="sidebarOpen">Main</div>
      <ul class="mt-2 space-y-1">
        <li>
          <a href="{{ route('dashboard') }}" data-nav-link class="flex items-center gap-3 rounded px-3 py-2 transition hover:bg-slate-800 {{ Route::is('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
            <span class="text-sm" x-show="sidebarOpen">Dashboard</span>
          </a>
        </li>
        <li>
          <div class="rounded">
            <button type="button" @click="crmOpen = !crmOpen" class="flex w-full items-center justify-between gap-3 rounded px-3 py-2 text-left transition hover:bg-slate-800 {{ Route::is('crm.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
              <span class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v14l7-3 7 3V5a2 2 0 00-2-2H5z"/></svg>
                <span class="text-sm" x-show="sidebarOpen">CRM</span>
              </span>
              <span class="text-xs text-slate-400" x-show="sidebarOpen" x-text="crmOpen ? '▾' : '▸'"></span>
            </button>
            <div x-show="sidebarOpen && crmOpen" x-collapse class="space-y-1 pb-2 ps-8 pt-2 text-sm">
              <a href="{{ route('crm.dashboard') }}" data-nav-link class="block rounded px-2 py-1 transition hover:bg-slate-800 {{ Route::is('crm.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">Dashboard</a>
              <a href="{{ route('crm.customers.index') }}" data-nav-link class="block rounded px-2 py-1 transition hover:bg-slate-800 {{ Route::is('crm.customers.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">Customers</a>
              <a href="{{ route('crm.leads.index') }}" data-nav-link class="block rounded px-2 py-1 transition hover:bg-slate-800 {{ Route::is('crm.leads.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">Leads</a>
              <a href="{{ route('crm.follow-ups.index') }}" data-nav-link class="block rounded px-2 py-1 transition hover:bg-slate-800 {{ Route::is('crm.follow-ups.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">Follow-ups</a>
              <a href="{{ route('crm.reports') }}" data-nav-link class="block rounded px-2 py-1 transition hover:bg-slate-800 {{ Route::is('crm.reports') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">Reports</a>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <div>
      <div class="px-3 text-xs uppercase tracking-wide text-slate-400" x-show="sidebarOpen">Modules</div>
      <ul class="mt-2 space-y-1">
        <li><a href="#" class="flex items-center gap-3 rounded px-3 py-2 text-slate-300 transition hover:bg-slate-800"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zM13 21h8V11h-8v10z"/></svg><span class="text-sm" x-show="sidebarOpen">Project Management</span></a></li>
        <li><a href="#" class="flex items-center gap-3 rounded px-3 py-2 text-slate-300 transition hover:bg-slate-800"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M2 8l10-6 10 6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"/></svg><span class="text-sm" x-show="sidebarOpen">Properties</span></a></li>
        <li><a href="#" class="flex items-center gap-3 rounded px-3 py-2 text-slate-300 transition hover:bg-slate-800"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zM2 20a10 10 0 0120 0H2z"/></svg><span class="text-sm" x-show="sidebarOpen">Manage Customer</span></a></li>
      </ul>
    </div>
  </nav>

  <div class="border-t border-slate-800 px-3 py-4 text-xs text-slate-400">
    <div x-show="sidebarOpen">Powered by EstateFlow ERP</div>
    <div class="mt-1">Version 0.1</div>
  </div>
</aside>
