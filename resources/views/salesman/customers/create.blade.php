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
            <label class="block text-sm text-white/80 mb-1">Phone 1</label>
            <input name="phone1" type="text"
                placeholder="Primary phone number"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none"
                required>
        </div>

        <!-- Phone 2 -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Phone 2</label>
            <input name="phone2" type="text"
                placeholder="Optional phone number"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none">
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
               bg-gradient-to-r from-indigo-500 to-purple-500 shadow-lg
               hover:opacity-90 transition">
        Save Customer
    </button>

    </form>

</div>

@endsection
