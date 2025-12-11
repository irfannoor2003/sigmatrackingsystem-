@extends('layouts.app')
@section('title', 'Add Customer')

@section('content')

<div class="max-w-2xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20
            p-8 rounded-2xl shadow-xl">

    <h2 class="text-2xl font-bold text-white mb-6 tracking-wide">Add Customer</h2>

    <form action="{{ route('salesman.customers.store') }}" method="POST"
      enctype="multipart/form-data" class="space-y-5">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- Customer Name -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Customer Name</label>
            <input name="name" type="text"
                placeholder="Enter customer name"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none"
                required>
        </div>

        <!-- Contact Person -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Contact Person</label>
            <input name="contact_person" type="text"
                placeholder="Enter contact person name"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none"
                required>
        </div>

   <!-- Phone 1 -->
<div>
    <label class="block text-sm text-white/80 mb-1">Mobile 1</label>

    <div class="flex items-center bg-white/10 backdrop-blur-xl border border-white/20
                rounded-lg overflow-hidden">
        <span class="px-4 py-3 text-white bg-white/5 border-r border-white/10">
            +92-
        </span>

        <input type="text"
               id="phone1_local"
               maxlength="10"
               inputmode="numeric"
               placeholder="3001234567"
               class="w-full px-4 py-3 bg-transparent text-white placeholder-white/40
                      outline-none">
    </div>

    <input type="hidden" name="phone1" id="phone1">
</div>

<!-- Phone 2 -->
<div>
    <label class="block text-sm text-white/80 mb-1">Mobile 2</label>

    <div class="flex items-center bg-white/10 backdrop-blur-xl border border-white/20
                rounded-lg overflow-hidden">
        <span class="px-4 py-3 text-white bg-white/5 border-r border-white/10">
            +92-
        </span>

        <input type="text"
               id="phone2_local"
               maxlength="10"
               inputmode="numeric"
               placeholder="3001234567 (optional)"
               class="w-full px-4 py-3 bg-transparent text-white placeholder-white/40
                      outline-none">
    </div>

    <input type="hidden" name="phone2" id="phone2">
</div>


        <!-- Email -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Email</label>
            <input name="email" type="email"
                placeholder="Enter email address"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none"
                required>
        </div>

        <!-- Address -->
        <div class="md:col-span-2">
            <label class="block text-sm text-white/80 mb-1">Address</label>
            <input name="address" type="text"
                placeholder="Enter full address"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none"
                required>
        </div>



        <!-- Industry -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Industry</label>
            <select name="industry_id"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                focus:bg-white/20 outline-none"
                required>
                <option value="" class="text-black">Select industry</option>
                @foreach($industries as $industry)
                    <option value="{{ $industry->id }}" class="text-black">
                        {{ $industry->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Category -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Category</label>
            <select name="category_id"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                focus:bg-white/20 outline-none"
                required>
                <option value="" class="text-black">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" class="text-black">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- City -->
        <div>
            <label class="block text-sm text-white/80 mb-1">City</label>
            <select name="city_id"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                focus:bg-white/20 outline-none"
                required>
                <option value="" class="text-black">Select city</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" class="text-black">
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Image Upload -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Customer Image</label>
            <input type="file" name="image" accept="image/*"
                class="w-full text-white file:bg-white/20 file:text-white file:border-0
                file:px-4 file:py-2 file:rounded-lg bg-white/10 rounded-lg py-2 px-3
                focus:bg-white/20"
                required>
        </div>

    </div>

    <button
        class="w-full py-3 rounded-xl text-white font-semibold tracking-wide
               bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] shadow-lg
               hover:opacity-90 transition">
        Save Customer
    </button>

    </form>

</div>

@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {

    function setupPhone(localId, hiddenId) {
        const local = document.getElementById(localId);
        const hidden = document.getElementById(hiddenId);

        // Update hidden field when typing
        local.addEventListener("input", function () {
            // allow only digits
            this.value = this.value.replace(/\D/g, "");

            // update hidden field
            if (this.value.length > 0) {
                hidden.value = "+92-" + this.value;
            } else {
                hidden.value = "";
            }
        });

        // Force update hidden before form submit
        const form = local.closest("form");
        form.addEventListener("submit", function () {
            local.value = local.value.replace(/\D/g, "");

            hidden.value = local.value.length > 0
                ? "+92-" + local.value
                : "";
        });
    }

    setupPhone("phone1_local", "phone1");
    setupPhone("phone2_local", "phone2");

});
</script>
