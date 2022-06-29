<x-app-layout>
    <x-slot name="logo">
        <a href="/">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
        </a>
    </x-slot>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <title>Create Job Application</title>

        <!-- Type -->
        <div>
            <x-label for="type" :value="__('Type')" />

            <select name="type" class="block mt-1 w-full">
                <option value="intern">Internship</option>
                <option value="fulltime">Fulltime</option>
                <option value="parttime">Part Time</option>
                <option value="freelance">Freelance</option>
            </select>
        </div>

        <!-- Category -->
        <div>
            <x-label for="category" :value="__('Categories')" />
            <select name="category" class="block mt-1 w-full">
                @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>


        <!-- Position -->
        <div class="mt-4">
            <x-label for="position" :value="__('Position')" />

            <x-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position')" required autofocus />
        </div>

        <!-- Description -->
        <div class="mt-4">
            <x-label for="description" :value="__('Description')" />

            <textarea class="block mt-1 w-full" rows="4", cols="40" id="keterangan" name="keterangan" :value="old('description)" style="resize:none, " required></textarea>
        </div>

        <!-- Remote -->
        <div class="mt-4">
            <x-label for="remote" :value="__('Remote work')" />

            <select name="remote" class="block mt-1 w-full">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>

        <!-- Min Salary -->
        <div class="mt-4">
            <x-label for="minSalary" :value="__('Minimum Salary')" />

            <x-input id="minSalary" class="block mt-1 w-full"
                            type="number"
                            name="minSalary"
                            min="0"
                            required />
        </div>

        <!-- Max Salary -->
        <div class="mt-4">
            <x-label for="maxSalary" :value="__('Minimum Salary')" />

            <x-input id="maxSalary" class="block mt-1 w-full"
                            type="number"
                            name="maxSalary"
                            min="0"
                            required />
        </div>

        <!-- Expired  -->
        <div class="mt-4">
            <x-label for="expired" :value="__('Expired')" />

            <x-input id="expired" class="block mt-1 w-full"
                            type="date"
                            name="expired"
                            required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-button class="ml-4">
                {{ __('Register') }}
            </x-button>
        </div>
    </form>
</x-app-layout>
