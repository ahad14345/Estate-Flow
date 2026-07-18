@extends('layouts.app')

@section('content')
<section class="bg-white rounded-lg shadow-sm overflow-hidden">
  <div class="flex items-center gap-6 p-6">
    <div class="flex-1">
      <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800">EstateFlow ERP</h1>
      <p class="text-slate-500 mt-1">A Cloud Based Real Estate Automation</p>
    </div>
    <div class="w-1/2 hidden md:block">
      <img src="https://via.placeholder.com/900x160.png?text=City+Skyline+Banner" alt="skyline" class="w-full h-32 object-cover rounded"/>
    </div>
  </div>
</section>

<section>
  <div class="bg-blue-600 text-white px-4 py-2 rounded-t">
    <div class="max-w-7xl mx-auto flex items-center gap-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zM13 21h8V11h-8v10z"/></svg>
      <div class="font-semibold">Real Estate</div>
    </div>
  </div>

  <div class="bg-white p-4 rounded-b shadow-sm">
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
      <div class="flex items-center justify-center p-6 rounded text-white font-semibold bg-blue-500">
        <div class="text-center"><div class="text-lg">Location</div><div class="text-2xl mt-2">11</div></div>
      </div>
      <div class="flex items-center justify-center p-6 rounded text-white font-semibold bg-red-500">
        <div class="text-center"><div class="text-lg">Projects</div><div class="text-2xl mt-2">36</div></div>
      </div>
      <div class="flex items-center justify-center p-6 rounded text-white font-semibold bg-yellow-400">
        <div class="text-center"><div class="text-lg">Products</div><div class="text-2xl mt-2">38</div></div>
      </div>
      <div class="flex items-center justify-center p-6 rounded text-white font-semibold bg-teal-500">
        <div class="text-center"><div class="text-lg">Customers</div><div class="text-2xl mt-2">29</div></div>
      </div>
    </div>
  </div>
</section>
@endsection
