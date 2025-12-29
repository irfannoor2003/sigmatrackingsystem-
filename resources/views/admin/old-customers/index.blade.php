@extends('layouts.app')

@section('title','Imported Old Customers')

@section('content')

<div class="p-6 text-white">

<!-- Header -->
<h1 class="text-3xl font-bold mb-6 tracking-wide flex items-center gap-2">
    <i data-lucide="database" class="w-7 h-7 text-[#ff2ba6]"></i>
    Imported Customers (Old)
</h1>

<!-- Filters -->
<form method="GET" class="flex flex-col md:flex-row gap-4 mb-6">

    <div class="relative w-full md:w-1/3">
        <i data-lucide="search" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Search company / contact / phone / email"
               class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white">
    </div>

    <button type="submit"
        class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] font-semibold flex items-center gap-2">
        <i data-lucide="filter"></i> Filter
    </button>

</form>

<!-- ACTION BAR -->
<div class="flex gap-3 mb-4">
    <button id="send-old-email-btn" disabled
        class="px-5 py-3 rounded-xl bg-gradient-to-r from-blue-500/50 to-indigo-500/50
               font-semibold disabled:opacity-50 flex items-center gap-2">
        <i data-lucide="mail"></i> Send Email
    </button>
</div>

<!-- TABLE -->
<div class="overflow-x-auto bg-white/5 border border-white/10 rounded-2xl hidden md:block">
<table class="min-w-full text-sm">
<thead class="bg-white/10">
<tr>
    <th class="p-4">
        <input type="checkbox" id="select-all-old">
    </th>
    <th class="p-4">ID</th>
    <th class="p-4">Company</th>
    <th class="p-4">Person</th>
    <th class="p-4">Email</th>
    <th class="p-4">Mobile</th>
</tr>
</thead>

<tbody>
@forelse($customers as $customer)
<tr class="border-t border-white/10">
    <td class="p-4">
        <input type="checkbox"
               class="old-customer-checkbox"
               value="{{ $customer->id }}">
    </td>
    <td class="p-4">#{{ $customer->id }}</td>
    <td class="p-4 font-semibold">{{ $customer->company_name }}</td>
    <td class="p-4">{{ $customer->contact_person ?? '-' }}</td>
    <td class="p-4">{{ $customer->email ?? '-' }}</td>
    <td class="p-4">{{ $customer->contact ?? '-' }}</td>
</tr>
@empty
<tr>
    <td colspan="6" class="p-6 text-center text-white/60">No records found</td>
</tr>
@endforelse
</tbody>
</table>
</div>

<div class="mt-6">
    {{ $customers->links() }}
</div>

</div>

<!-- EMAIL MODAL (REUSED) -->
<div id="email-modal"
     class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">

<div class="bg-slate-900 p-6 rounded-2xl w-full max-w-xl">

<h2 class="text-xl font-bold mb-4">Send Promotion Email</h2>

<form method="POST" action="{{ route('admin.promotions.send') }}" enctype="multipart/form-data">
@csrf

<input type="hidden" name="customer_type" value="old">
<div id="selected-customers-inputs"></div>

<input type="text" name="subject" required
       class="w-full p-3 rounded-xl bg-white/10 mb-3"
       placeholder="Subject">

<textarea name="message" rows="5" required
          class="w-full p-3 rounded-xl bg-white/10 mb-3"
          placeholder="Message"></textarea>

<input type="file" name="attachment" class="mb-4">

<div class="flex justify-end gap-3">
<button type="button" id="close-email-modal"
        class="px-4 py-2 border rounded-xl">Cancel</button>

<button type="submit"
        class="px-6 py-2 bg-gradient-to-r from-[#ff2ba6] to-[#ff5fcf]
               rounded-xl font-semibold">Send</button>
</div>

</form>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const sendBtn = document.getElementById('send-old-email-btn');
    const modal = document.getElementById('email-modal');
    const closeBtn = document.getElementById('close-email-modal');
    const inputsDiv = document.getElementById('selected-customers-inputs');
    const selectAll = document.getElementById('select-all-old');
    const checkboxes = document.querySelectorAll('.old-customer-checkbox');

    function updateBtn() {
        sendBtn.disabled =
            document.querySelectorAll('.old-customer-checkbox:checked').length === 0;
    }

    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBtn();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateBtn));

    sendBtn.addEventListener('click', () => {
        inputsDiv.innerHTML = '';

        document.querySelectorAll('.old-customer-checkbox:checked')
            .forEach(cb => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'customer_ids[]';
                input.value = cb.value;
                inputsDiv.appendChild(input);
            });

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

});
</script>

@endsection
