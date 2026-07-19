@extends('layouts.app')

@section('content')
<!-- Hero Banner -->
<section class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
  <div class="flex items-center gap-6 p-6">
    <div class="flex-1">
      <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">ESTATEFLOW ERP</h1>
      <p class="text-slate-500 mt-1 text-sm">A Cloud Based Real Estate Automation Engine</p>
    </div>
    <div class="w-1/3 hidden md:block">
      <div class="w-full h-20 bg-gradient-to-r from-slate-800 to-indigo-900 rounded opacity-90 flex items-center justify-center text-white text-xs font-mono">System Engine Online</div>
    </div>
  </div>
</section>

<!-- Real Estate Overview Summary Grid -->
<section class="border border-gray-200 rounded-lg overflow-hidden shadow-sm mt-6">
  <div class="bg-emerald-600 text-white px-4 py-3 flex items-center gap-3">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
    <div class="font-semibold text-sm tracking-wide">Real Estate Metrics Summary</div>
  </div>

  <div class="bg-white p-4">
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
      
      <div class="flex items-center p-4 rounded-lg text-white font-semibold bg-blue-500 transition-transform duration-150 hover:scale-[1.02]">
        <div>
          <div class="text-xs uppercase opacity-80 font-medium tracking-wider">Location</div>
          <div class="text-2xl font-bold mt-1">11</div>
        </div>
      </div>

      <div class="flex items-center p-4 rounded-lg text-white font-semibold bg-rose-500 transition-transform duration-150 hover:scale-[1.02]">
        <div>
          <div class="text-xs uppercase opacity-80 font-medium tracking-wider">Projects</div>
          <div class="text-2xl font-bold mt-1">36</div>
        </div>
      </div>

      <div class="flex items-center p-4 rounded-lg text-white font-semibold bg-amber-500 transition-transform duration-150 hover:scale-[1.02]">
        <div>
          <div class="text-xs uppercase opacity-80 font-medium tracking-wider">Products</div>
          <div class="text-2xl font-bold mt-1">38</div>
        </div>
      </div>

      <div class="flex items-center p-4 rounded-lg text-white font-semibold bg-teal-500 transition-transform duration-150 hover:scale-[1.02]">
        <div>
          <div class="text-xs uppercase opacity-80 font-medium tracking-wider">Customers</div>
          <div class="text-2xl font-bold mt-1">29</div>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
