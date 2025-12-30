@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')

<div class="max-w-2xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20
           p-8 rounded-2xl shadow-xl mt-10">

    {{-- Form Title with Icon --}}
    <h2 class="text-2xl font-bold text-white mb-6 tracking-wide flex items-center">
        {{-- Lucide Icon: edit --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="mr-3 text-[#ff2ba6]">
            <path d="M12 20h9"/>
            <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4Z"/>
        </svg>
        Edit Customer
    </h2>

    {{-- Form --}}
    <form action="{{ route('salesman.customers.update', $customer->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-5">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Customer Name --}}
            <div>
                <label class="block text-sm text-white/80 mb-1">Customer Name</label>
                <input name="name" type="text"
                       value="{{ old('name', $customer->name) }}"
                       class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                              placeholder-white/50 focus:bg-white/20 outline-none"
                       required>
                @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Contact Person --}}
            <div>
                <label class="block text-sm text-white/80 mb-1">Contact Person</label>
                <input name="contact_person" type="text"
                       value="{{ old('contact_person', $customer->contact_person) }}"
                       class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                              placeholder-white/50 focus:bg-white/20 outline-none"
                       required>
                @error('contact_person') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Phone 1 --}}
            <div>
                <label class="block text-sm text-white/80 mb-1">Mobile 1</label>
                <div class="flex items-center bg-white/10 border border-white/20 rounded-lg overflow-hidden">
                    <span class="px-4 py-3 text-white bg-white/5 border-r border-white/10">+92-</span>
                    <input type="text" id="phone1_local"
                           value="{{ old('phone1', str_replace('+92-', '', $customer->phone1)) }}"
                           maxlength="10"
                           inputmode="numeric"
                           class="w-full px-4 py-3 bg-transparent text-white outline-none">
                </div>
                <input type="hidden" name="phone1" id="phone1">
            </div>

            {{-- Phone 2 --}}
            <div>
                <label class="block text-sm text-white/80 mb-1">Mobile 2</label>
                <div class="flex items-center bg-white/10 border border-white/20 rounded-lg overflow-hidden">
                    <span class="px-4 py-3 text-white bg-white/5 border-r border-white/10">+92-</span>
                    <input type="text" id="phone2_local"
                           value="{{ old('phone2', str_replace('+92-', '', $customer->phone2)) }}"
                           maxlength="10"
                           inputmode="numeric"
                           class="w-full px-4 py-3 bg-transparent text-white outline-none">
                </div>
                <input type="hidden" name="phone2" id="phone2">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm text-white/80 mb-1">Email</label>
                <input name="email" type="email"
                       value="{{ old('email', $customer->email) }}"
                       class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                              placeholder-white/50 focus:bg-white/20 outline-none">
            </div>

            {{-- Address --}}
            <div class="md:col-span-2">
                <label class="block text-sm text-white/80 mb-1">Address</label>
                <input name="address" type="text"
                       value="{{ old('address', $customer->address) }}"
                       class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                              placeholder-white/50 focus:bg-white/20 outline-none"
                       required>
            </div>

            {{-- Industry --}}
            <div>
                <label class="block text-sm text-white/80 mb-1">Industry</label>
                <select name="industry_id"
                        class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                               focus:bg-white/20 outline-none"
                        required>
                    @foreach($industries as $industry)
                        <option value="{{ $industry->id }}" class="text-black"
                            {{ old('industry_id', $customer->industry_id) == $industry->id ? 'selected' : '' }}>
                            {{ $industry->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm text-white/80 mb-1">Category</label>
                <select name="category_id"
                        class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                               focus:bg-white/20 outline-none"
                        required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" class="text-black"
                            {{ old('category_id', $customer->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- City --}}
            <div>
                <label class="block text-sm text-white/80 mb-1">City</label>
                <select name="city_id"
                        class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                               focus:bg-white/20 outline-none"
                        required>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" class="text-black"
                            {{ old('city_id', $customer->city_id) == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Image --}}
            <div>
                <label class="block text-sm text-white/80 mb-1">Customer Image</label>

                @if($customer->image)
                    <img src="{{ asset('storage/'.$customer->image) }}"
                         class="h-24 rounded-xl mb-3 border border-white/20">
                @endif

                <input type="file" name="image" accept="image/*"
                       class="w-full text-white file:bg-white/20 file:text-white file:border-0
                              file:px-4 file:py-2 file:rounded-lg bg-white/10 rounded-lg py-2 px-3
                              focus:bg-white/20">
                <p class="text-xs text-white/60 mt-1">Leave empty to keep existing image</p>
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex gap-3 pt-4">
            <a href="{{ route('salesman.customers.index') }}"
               class="flex-1 py-3 text-center rounded-xl bg-white/10 text-white hover:bg-white/20">
                Cancel
            </a>

            <button type="submit"
                class="flex-1 py-3 rounded-xl text-white font-semibold tracking-wide
                       bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                       shadow-lg hover:opacity-90 transition">
                Update Customer
            </button>
        </div>

    </form>
</div>

@endsection

{{-- Phone JS --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    function setupPhone(localId, hiddenId) {
        const local = document.getElementById(localId);
        const hidden = document.getElementById(hiddenId);

        function sync() {
            local.value = local.value.replace(/\D/g, "");
            hidden.value = local.value ? "+92-" + local.value : "";
        }

        local.addEventListener("input", sync);
        sync();
    }

    setupPhone("phone1_local", "phone1");
    setupPhone("phone2_local", "phone2");

});
</script>
