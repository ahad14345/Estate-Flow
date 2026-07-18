<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EstateFlow ERP</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-screen antialiased text-gray-700" x-data="{sidebarOpen: true, crmOpen: false}">
  <div class="flex h-screen overflow-hidden">
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'" class="transition-width duration-200 bg-slate-900 text-slate-100 flex flex-col">
      <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-800">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-cyan-400 rounded flex items-center justify-center text-white font-bold">EF</div>
          <div x-show="sidebarOpen" class="leading-tight">
            <div class="text-sm font-semibold">EstateFlow ERP</div>
            <div class="text-xs text-slate-300">Real Estate ERP</div>
          </div>
        </div>
      </div>

      <nav class="flex-1 overflow-auto px-2 py-4 space-y-4">
        <div>
          <div class="px-3 text-xs text-slate-400 uppercase tracking-wide">Main</div>
          <ul class="mt-2 space-y-1">
            <li>
              <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                <span x-show="sidebarOpen" class="text-sm">Dashboard</span>
              </a>
            </li>
            <li>
              <div class="rounded hover:bg-slate-800">
                <button type="button" @click="crmOpen = !crmOpen" class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded text-left">
                  <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v14l7-3 7 3V5a2 2 0 00-2-2H5z"/></svg>
                    <span x-show="sidebarOpen" class="text-sm">CRM</span>
                  </span>
                  <span x-show="sidebarOpen" class="text-xs text-slate-400" x-text="crmOpen ? '▾' : '▸'"></span>
                </button>
                <div x-show="sidebarOpen && crmOpen" x-collapse class="ps-8 pb-2 space-y-1 text-sm text-slate-300">
                  <a href="{{ route('crm.dashboard') }}" class="block hover:text-white">Dashboard</a>
                  <a href="{{ route('crm.customers.index') }}" class="block hover:text-white">Customers</a>
                  <a href="{{ route('crm.leads.index') }}" class="block hover:text-white">Leads</a>
                  <a href="{{ route('crm.follow-ups.index') }}" class="block hover:text-white">Follow-ups</a>
                  <a href="{{ route('crm.reports') }}" class="block hover:text-white">Reports</a>
                </div>
              </div>
            </li>
          </ul>
        </div>

        <div>
          <div class="px-3 text-xs text-slate-400 uppercase tracking-wide">Modules</div>
          <ul class="mt-2 space-y-1">
            <li><a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-800"><svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zM13 21h8V11h-8v10z"/></svg><span x-show="sidebarOpen" class="text-sm">Project Management</span></a></li>
            <li><a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-800"><svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path d="M2 8l10-6 10 6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"/></svg><span x-show="sidebarOpen" class="text-sm">Properties</span></a></li>
            <li><a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-800"><svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zM2 20a10 10 0 0120 0H2z"/></svg><span x-show="sidebarOpen" class="text-sm">Manage Customer</span></a></li>
          </ul>
        </div>
      </nav>

      <div class="px-3 py-4 border-t border-slate-800 text-xs text-slate-400">
        <div x-show="sidebarOpen">Powered by EstateFlow ERP</div>
        <div class="mt-1">Version 0.1</div>
      </div>
    </aside>

    <div class="flex-1 flex flex-col bg-gray-100 overflow-auto">
      <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between h-14">
            <div class="flex items-center gap-3">
              <div class="text-sm font-semibold">EstateFlow ERP</div>
            </div>
            <div class="flex items-center gap-3">
              <div class="text-sm text-gray-600">admin</div>
            </div>
          </div>
        </div>
      </header>

      <main class="p-6 space-y-6">
        @yield('content')
      </main>
    </div>
  </div>
</body>
</html>
