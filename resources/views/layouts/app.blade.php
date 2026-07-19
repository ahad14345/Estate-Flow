<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('page_title', 'EstateFlow ERP')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="h-screen antialiased text-gray-700 bg-gray-100" 
  x-data="{ 
    sidebarOpen: true, 
    expandedModule: null,
    crmOpen: false 
  }" 
  x-init="
    const saved = localStorage.getItem('estateflow.sidebar.open');
    if (saved !== null) {
      sidebarOpen = saved === 'true';
    }
    
    // Auto-detect Laravel route state mappings
    crmOpen = {{ request()->routeIs('crm.*') ? 'true' : 'false' }};
    if ({{ request()->routeIs('projects.*') || request()->routeIs('properties.*') ? 'true' : 'false' }}) { expandedModule = 'projects'; }
    else if ({{ request()->routeIs('sales.*') ? 'true' : 'false' }}) { expandedModule = 'sales'; }
    else if ({{ request()->routeIs('purchase.*') || request()->routeIs('vendors.*') ? 'true' : 'false' }}) { expandedModule = 'purchase'; }
    else if ({{ request()->routeIs('accounting.*') ? 'true' : 'false' }}) { expandedModule = 'accounting'; }
    else if ({{ request()->routeIs('employees.*') ? 'true' : 'false' }}) { expandedModule = 'employees'; }
    else if ({{ request()->routeIs('users.*') ? 'true' : 'false' }}) { expandedModule = 'users'; }

    $watch('sidebarOpen', value => localStorage.setItem('estateflow.sidebar.open', String(value)));
  }" data-layout-root>
  
  @if(request()->has('content_only'))
    <div id="page-content" class="space-y-6">
      @yield('content')
    </div>
  @else
    <!-- The outer flex container connects sidebar + main content in one continuous scope -->
    <div class="flex h-screen overflow-hidden w-full">
      
      <!-- Include Sidebar Partition -->
      @include('layouts.partials.sidebar')

      <!-- Viewport Frame Wrapper -->
      <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <!-- Navbar Header -->
        <header class="bg-white border-b border-gray-200 h-14 flex-shrink-0 z-10">
          <div class="h-full flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
              <button type="button" @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
              </button>
              <div class="text-sm font-semibold">EstateFlow ERP</div>
            </div>
            <div class="flex items-center gap-3">
              <div class="text-sm text-gray-600">admin</div>
            </div>
          </div>
        </header>

        <!-- Document Scroll Area Canvas -->
        <main id="page-content" class="flex-1 overflow-y-auto p-6 space-y-6">
          @if(session('success'))
            <div class="d-none" data-auto-toast="success">{{ session('success') }}</div>
            <div class="rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="d-none" data-auto-toast="error">{{ implode($errors->all(), ' | ') }}</div>
            <div class="rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
              <ul class="mb-0 list-disc ps-5">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @hasSection('breadcrumbs')
            <nav aria-label="breadcrumb" class="mb-2">
              <ol class="breadcrumb mb-0">
                @yield('breadcrumbs')
              </ol>
            </nav>
          @endif

          @yield('content')
        </main>

        <!-- Footer Partition -->
        <footer class="border-t border-gray-200 bg-white px-6 py-4 text-sm text-gray-500 flex-shrink-0">
          EstateFlow ERP • Admin dashboard
        </footer>
      </div>
    </div>
  @endif

  <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toastContainer = document.getElementById('toast-container');
      const showToast = (message, type = 'success') => {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type === 'error' ? 'danger' : 'success'} border-0 show`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
        toastContainer.appendChild(toast);
        setTimeout(() => toast.remove(), 2800);
      };

      document.querySelectorAll('[data-auto-toast]').forEach((element) => {
        showToast(element.textContent.trim(), element.getAttribute('data-auto-toast'));
      });
    });
  </script>
</body>
</html>