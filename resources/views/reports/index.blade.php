@extends('layouts.app')

@section('title','Reports')

@section('content')
<div class="max-w-7xl mx-auto">
  <div class="bg-white p-6 rounded-xl shadow mb-6">
    <h2 class="text-2xl font-bold">Monthly Reports</h2>
    <p class="text-sm text-gray-500 mt-1">Visits and New Customers</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-xl shadow">
      <h3 class="font-semibold mb-3">Monthly Visits</h3>
      <canvas id="visitsChart" height="140"></canvas>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
      <h3 class="font-semibold mb-3">New Customers (Monthly)</h3>
      <canvas id="customersChart" height="140"></canvas>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const visits = @json($visits ?? array_fill(0,12,0));
const customers = @json($customers ?? array_fill(0,12,0));

new Chart(document.getElementById('visitsChart'), {
  type:'line',
  data:{
    labels: months,
    datasets:[{ label:'Visits', data: visits, borderWidth:2, tension:0.3 }]
  }
});

new Chart(document.getElementById('customersChart'), {
  type:'bar',
  data:{
    labels: months,
    datasets:[{ label:'New Customers', data: customers, borderWidth:2 }]
  }
});
</script>
@endsection
