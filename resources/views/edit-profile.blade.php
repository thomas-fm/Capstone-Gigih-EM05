<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- @if($user->user_id) --}}
                    <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="email" value="{{Auth::user()->email}}">
                        <div class="mt-4">
                            <x-label for="fullname" :value="__('Nama Lengkap')" />
            
                            <x-input id="fullname" class="block mt-1 w-full"
                            type="text"
                            name="fullname"
                            required value="{{$user ? $user->fullname : ''}}"/>
                        </div>
                        
                        <div class="mt-4">
                            <x-label for="description" :value="__('Deskripsi')" />
            
                            <x-input id="description" class="block mt-1 w-full"
                            type="text"
                            name="description"
                            required value="{{$user ? $user->description : ''}}"/>
                        </div>
                        
                        <div class="mt-4">
                            <x-label for="password" :value="__('Foto')" />
            
                            <x-input id="photo" class="block mt-1 w-full"
                            type="file"
                            name="photo"
                            value="{{$user ? $user->photo : ''}}"/>
                        </div>

                        <div class="mt-4">
                            <x-label for="phone" :value="__('Nomor Handphone')" />
            
                            <x-input id="phone" class="block mt-1 w-full"
                            type="text"
                            name="phone"
                            required value="{{$user ? $user->phone : ''}}"/>
                        </div>

                        <div class="mt-4">
                            <x-label for="city" :value="__('Kota')" />
            
                            <x-input id="city" class="block mt-1 w-full"
                            type="text"
                            name="city"
                            required value="{{$user ? $user->city : ''}}"/>
                        </div>

                        <div class="mt-4">
                            <x-label for="province" :value="__('Provinsi')" />
            
                            <x-input id="province" class="block mt-1 w-full"
                            type="text"
                            name="province"
                            required value="{{$user ? $user->province : ''}}"/>
                        </div>
            
                        <div class="flex items-center justify-end mt-4">            
                            <x-button class="ml-3">
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>
                    {{-- @else
                    <form method="POST" action="{{ route('editprofile.store') }}">
                        @csrf
                        <div class="mt-4">
                            <x-label for="fullname" :value="__('Nama Lengkap')" />
            
                            <input type="text" name="fullname" id="" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                        </div>
                        
                        <div class="mt-4">
                            <x-label for="description" :value="__('Deskripsi')" />
            
                            <input type="text" name="description" id="" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                        </div>
                        
                        <div class="mt-4">
                            <x-label for="password" :value="__('Foto')" />
            
                            <input type="file" name="photo" id="" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                        </div>

                        <div class="mt-4">
                            <x-label for="phone" :value="__('Nomor Handphone')" />
            
                            <input type="text" name="phone" id="" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                        </div>

                        <div class="mt-4">
                            <x-label for="city" :value="__('Kota')" />
            
                            <input type="text" name="city" id="" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                        </div>

                        <div class="mt-4">
                            <x-label for="province" :value="__('Provinsi')" />
            
                            <input type="text" name="province" id="" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                        </div>
            
                        <div class="flex items-center justify-end mt-4">            
                            <x-button class="ml-3">
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
