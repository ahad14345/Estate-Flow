<aside :class="sidebarOpen ? 'w-64' : 'w-16'" class="h-screen transition-all duration-200 bg-slate-900 text-slate-100 flex flex-col flex-shrink-0 z-30">

  <!-- Logo Brand Panel Container -->
  <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-800 h-14 flex-shrink-0">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 bg-gradient-to-r from-indigo-600 to-cyan-400 rounded flex items-center justify-center text-white font-bold text-sm flex-shrink-0">EF</div>
      <div x-show="sidebarOpen" class="leading-tight" x-cloak>
        <div class="text-sm font-semibold">EstateFlow ERP</div>
        <div class="text-xs text-slate-400">Real Estate ERP</div>
      </div>
    </div>
  </div>

  <!-- User Profile Section -->
  <div class="px-4 py-3 border-b border-slate-800 flex items-center gap-3 flex-shrink-0">
    <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-white font-medium text-sm flex-shrink-0">A</div>
    <div x-show="sidebarOpen" class="flex-1 min-w-0" x-cloak>
      <div class="text-sm font-medium truncate">admin</div>
      <div class="text-xs text-slate-400 truncate">admin@estateflow.com</div>
    </div>
  </div>

  <!-- Scrollable Module Tree Context -->
  <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-4">
    
    <!-- MAIN SECTION LINKS -->
    <div>
      <div x-show="sidebarOpen" class="px-3 text-xs text-slate-500 uppercase font-semibold tracking-wider" x-cloak>Main</div>
      <ul class="mt-2 space-y-1">
        <li>
          <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">
            <i class="bi bi-speedometer2 text-slate-400"></i>
            <span x-show="sidebarOpen" class="text-sm" x-cloak>Dashboard</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- MAIN APP CORE MODULES -->
    <div>
      <div x-show="sidebarOpen" class="px-3 text-xs text-slate-500 uppercase font-semibold tracking-wider" x-cloak>Modules</div>
      <ul class="mt-2 space-y-1">

        <!-- CRM Module -->
        <li>
          <button @click="crmOpen = !crmOpen" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">
            <div class="flex items-center gap-3 min-w-0">
              <i class="bi bi-person-lines-fill text-slate-400"></i>
              <span x-show="sidebarOpen" class="text-sm truncate" x-cloak>CRM</span>
            </div>
            <i x-show="sidebarOpen" :class="crmOpen ? 'rotate-180' : ''" class="bi bi-chevron-down transform transition-transform text-slate-400 text-xs flex-shrink-0" x-cloak></i>
          </button>
          <ul x-show="crmOpen && sidebarOpen" x-cloak class="mt-1 pl-9 space-y-1 border-l border-slate-800 ml-5 text-slate-400">
            <li>
              <a href="{{ route('crm.dashboard') }}" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white {{ request()->routeIs('crm.dashboard') ? 'text-white bg-slate-800' : '' }}">
                CRM Dashboard
              </a>
            </li>
            <li>
              <a href="{{ route('crm.leads.index') }}" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white {{ request()->routeIs('crm.leads.*') ? 'text-white bg-slate-800' : '' }}">
                Leads
              </a>
            </li>
            <li>
              <a href="{{ route('crm.customers.index') }}" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white {{ request()->routeIs('crm.customers.*') ? 'text-white bg-slate-800' : '' }}">
                Customers
              </a>
            </li>
            <li>
              <a href="{{ route('crm.follow-ups.index') }}" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white {{ request()->routeIs('crm.follow-ups.*') ? 'text-white bg-slate-800' : '' }}">
                Follow-ups
              </a>
            </li>
            <li>
              <a href="{{ route('crm.reports') }}" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white {{ request()->routeIs('crm.reports') ? 'text-white bg-slate-800' : '' }}">
                Reports
              </a>
            </li>
          </ul>
        </li>

       <!-- Projects & Properties Dropdown Tree -->
<li>
  <button @click="expandedModule = (expandedModule === 'projects' ? null : 'projects')" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">
    <div class="flex items-center gap-3 min-w-0">
      <i class="bi bi-building text-slate-400"></i>
      <span x-show="sidebarOpen" class="text-sm truncate" x-cloak>Projects & Properties</span>
    </div>
    <i x-show="sidebarOpen" :class="expandedModule === 'projects' ? 'rotate-180' : ''" class="bi bi-chevron-down transform transition-transform text-slate-400 text-xs flex-shrink-0" x-cloak></i>
  </button>
  <ul x-show="expandedModule === 'projects' && sidebarOpen" x-cloak class="mt-1 pl-9 space-y-1 border-l border-slate-800 ml-5 text-slate-400">
    <li>
      <a href="{{ route('projects.index') }}" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white {{ request()->routeIs('projects.*') ? 'text-white bg-slate-800' : '' }}">
        Projects
      </a>
    </li>
   <li>
  <a href="{{ route('properties.index') }}" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white {{ request()->routeIs('properties.*') ? 'text-white bg-slate-800' : '' }}">
    Properties
  </a>
</li>
  </ul>
</li>

        <!-- Sales Dropdown Tree -->
        <li>
          <button @click="expandedModule = (expandedModule === 'sales' ? null : 'sales')" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">
            <div class="flex items-center gap-3 min-w-0">
              <i class="bi bi-graph-up-arrow text-slate-400"></i>
              <span x-show="sidebarOpen" class="text-sm truncate" x-cloak>Sales</span>
            </div>
            <i x-show="sidebarOpen" :class="expandedModule === 'sales' ? 'rotate-180' : ''" class="bi bi-chevron-down transform transition-transform text-slate-400 text-xs flex-shrink-0" x-cloak></i>
          </button>
          <ul x-show="expandedModule === 'sales' && sidebarOpen" x-cloak class="mt-1 pl-9 space-y-1 border-l border-slate-800 ml-5 text-slate-400">
            <li><a href="#" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white">Sales Orders</a></li>
          </ul>
        </li>

       <!-- Purchase Dropdown Tree -->
<li :class="{ 'bg-slate-900/50 rounded-md': expandedModule === 'purchase' || {{ request()->routeIs('purchases.*') ? 'true' : 'false' }} }">
  <button 
    @click="expandedModule = (expandedModule === 'purchase' ? null : 'purchase')" 
    class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-slate-800 text-slate-300 hover:text-white transition-colors {{ request()->routeIs('purchases.*') ? 'text-white bg-slate-800' : '' }}"
    x-init="if({{ request()->routeIs('purchases.*') ? 'true' : 'false' }}) expandedModule = 'purchase'"
  >
    <div class="flex items-center gap-3 min-w-0">
      <i class="bi bi-cart3 {{ request()->routeIs('purchases.*') ? 'text-blue-500' : 'text-slate-400' }}"></i>
      <span x-show="sidebarOpen" class="text-sm truncate {{ request()->routeIs('purchases.*') ? 'font-medium text-white' : '' }}" x-cloak>Purchase</span>
    </div>
    <i x-show="sidebarOpen" :class="expandedModule === 'purchase' ? 'rotate-180' : ''" class="bi bi-chevron-down transform transition-transform text-slate-400 text-xs flex-shrink-0" x-cloak></i>
  </button>
  
  <ul x-show="expandedModule === 'purchase' && sidebarOpen" x-cloak class="mt-1 pl-9 space-y-1 border-l border-slate-800 ml-5 text-slate-400">
    <li>
      <a href="#" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white transition-colors">
        Vendors
      </a>
    </li>
    <li>
      <a 
        href="{{ route('purchases.index') }}" 
        class="block py-1.5 px-2 rounded text-xs transition-colors {{ request()->routeIs('purchases.index') ? 'bg-blue-600/20 text-blue-400 font-medium border-r-2 border-blue-500' : 'hover:bg-slate-800 hover:text-white' }}"
      >
        Purchase Orders
      </a>
    </li>
  </ul>
</li>

        <!-- Accounting Dropdown Tree -->
        <li>
          <button @click="expandedModule = (expandedModule === 'accounting' ? null : 'accounting')" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">
            <div class="flex items-center gap-3 min-w-0">
              <i class="bi bi-cash-coin text-slate-400"></i>
              <span x-show="sidebarOpen" class="text-sm truncate" x-cloak>Accounting</span>
            </div>
            <i x-show="sidebarOpen" :class="expandedModule === 'accounting' ? 'rotate-180' : ''" class="bi bi-chevron-down transform transition-transform text-slate-400 text-xs flex-shrink-0" x-cloak></i>
          </button>
          <ul x-show="expandedModule === 'accounting' && sidebarOpen" x-cloak class="mt-1 pl-9 space-y-1 border-l border-slate-800 ml-5 text-slate-400">
            <li><a href="#" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white">Transactions</a></li>
          </ul>
        </li>

        <!-- Employee Management Dropdown Tree -->
        <li>
          <button @click="expandedModule = (expandedModule === 'employees' ? null : 'employees')" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">
            <div class="flex items-center gap-3 min-w-0">
              <i class="bi bi-people text-slate-400"></i>
              <span x-show="sidebarOpen" class="text-sm truncate" x-cloak>Employee Management</span>
            </div>
            <i x-show="sidebarOpen" :class="expandedModule === 'employees' ? 'rotate-180' : ''" class="bi bi-chevron-down transform transition-transform text-slate-400 text-xs flex-shrink-0" x-cloak></i>
          </button>
          <ul x-show="expandedModule === 'employees' && sidebarOpen" x-cloak class="mt-1 pl-9 space-y-1 border-l border-slate-800 ml-5 text-slate-400">
            <li><a href="#" class="block py-1.5 px-2 rounded text-xs hover:bg-slate-800 hover:text-white">Directory</a></li>
          </ul>
        </li>

      </ul>
    </div>
  </nav>

  <!-- Sidebar Footer Notice Panel -->
  <div class="px-4 py-3 border-t border-slate-800 text-xs text-slate-500 flex-shrink-0">
    <div x-show="sidebarOpen" class="truncate" x-cloak>Powered by EstateFlow</div>
    <div class="mt-0.5">Version 0.1</div>
  </div>

</aside>