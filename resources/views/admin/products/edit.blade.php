@extends('appAdminLayout.layout')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')

    <a href="{{ route('admin.products.index') }}"
        class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-black transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Products
    </a>

    <div class="bg-white border border-[#19140010] rounded-2xl p-8 max-w-2xl">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-[#1b1b18] mb-2">Product name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-[#1b1b18] mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors resize-none">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-[#1b1b18] mb-2">Price (MAD)</label>
                <div class="relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-sm text-gray-400">DH</span>
                    <input id="price" type="number" step="0.01" min="0" name="price"
                        value="{{ old('price', $product->price) }}" required
                        class="w-full pl-8 pr-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                </div>
                @error('price')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block text-sm font-medium text-[#1b1b18] mb-2">Gender</label>
                    <select id="gender" name="gender" required
                        class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors appearance-none">
                        <option value="" disabled>Select gender</option>
                        <option value="men" {{ old('gender', $product->gender) === 'men' ? 'selected' : '' }}>Men
                        </option>
                        <option value="women" {{ old('gender', $product->gender) === 'women' ? 'selected' : '' }}>Women
                        </option>
                        <option value="unisex" {{ old('gender', $product->gender) === 'unisex' ? 'selected' : '' }}>Unisex
                        </option>
                    </select>
                    @error('gender')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-[#1b1b18] mb-2">Category</label>
                    <select id="category" name="category" required
                        class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors appearance-none">
                        <option value="" disabled>Select category</option>
                        <option value="glasses" {{ old('category', $product->category) === 'glasses' ? 'selected' : '' }}>
                            Glasses</option>
                        <option value="sunglasses"
                            {{ old('category', $product->category) === 'sunglasses' ? 'selected' : '' }}>Sunglasses
                        </option>
                    </select>
                    @error('category')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-[#1b1b18]">Variants</label>
                    <button type="button" id="toggle-variants"
                        class="text-xs font-semibold text-gray-500 hover:text-black transition-colors">
                        Select all
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach ($variants as $variant)
                        <label
                            class="flex items-center gap-2 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-[#1b1b18]/40 transition-colors has-[:checked]:border-black has-[:checked]:bg-gray-50">
                            <input type="checkbox" name="variant_ids[]" value="{{ $variant->id }}"
                                class="variant-checkbox w-4 h-4 rounded border-gray-300 text-black focus:ring-black/20 cursor-pointer"
                                {{ in_array($variant->id, old('variant_ids', $product->variants->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <span class="text-sm text-[#1b1b18]">{{ $variant->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('variant_ids')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1b1b18] mb-2">Product image</label>

                <label for="image"
                    class="block border border-dashed border-gray-300 rounded-2xl p-8 text-center cursor-pointer hover:border-[#1b1b18]/40 transition-colors"
                    id="dropzone">
                    <img id="preview-img" src="{{ $product->image_url }}"
                        class="{{ $product->image_url ? '' : 'hidden' }} mx-auto max-h-48 object-contain rounded-xl"
                        alt="{{ $product->name }}">
                    <div id="preview-empty" class="{{ $product->image_url ? 'hidden' : '' }}">
                        <div
                            class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-[#1b1b18]">Click to upload an image</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG or WEBP — up to 4MB</p>
                    </div>
                    <p class="text-xs text-gray-400 mt-3">Click anywhere to replace the current image</p>
                </label>
                <input id="image" type="file" name="image" accept="image/png,image/jpeg,image/webp" class="hidden">
                @error('image')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-sm font-semibold hover:bg-black/90 transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.products.index') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 border border-gray-200 rounded-full text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        const imageInput = document.getElementById('image');
        const previewImg = document.getElementById('preview-img');
        const previewEmpty = document.getElementById('preview-empty');

        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                previewEmpty.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });

        // Select all / clear all variants
        const toggleVariantsBtn = document.getElementById('toggle-variants');
        const variantCheckboxes = document.querySelectorAll('.variant-checkbox');

        function refreshToggleLabel() {
            const allChecked = variantCheckboxes.length > 0 &&
                Array.from(variantCheckboxes).every(cb => cb.checked);
            toggleVariantsBtn.textContent = allChecked ? 'Clear all' : 'Select all';
        }

        toggleVariantsBtn.addEventListener('click', () => {
            const allChecked = Array.from(variantCheckboxes).every(cb => cb.checked);
            variantCheckboxes.forEach(cb => cb.checked = !allChecked);
            refreshToggleLabel();
        });

        variantCheckboxes.forEach(cb => cb.addEventListener('change', refreshToggleLabel));

        refreshToggleLabel();
    </script>
@endpush
