<x-app-layout>
    @php
        $adminStates = App\Models\User::getAdminStates();
        $activeStates = App\Models\User::getActiveStates();
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('custom.viewing_user') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-2">
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    <label for="name">{{__('custom.name')}}</label>
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ? old('name') : $user->name" required autocomplete="off" />
                    <label for="name" class="mt-2">{{__('custom.email')}}</label>
                    <x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email') ? old('email') : $user->email" required autocomplete="off" />

                    @if ($user->is_admin)
                        <label for="name">{{__('custom.is_admin')}}</label>
                        <select name="is_admin" id="is_admin" class="block mt-1 w-full">
                            @foreach ($adminStates as $ind => $label)
                                <option value="{{ $ind }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    @endif
                    <label for="active">{{__('custom.active')}}</label>
                    <select name="active" id="active" class="block mt-1 w-full">
                        @foreach ($activeStates as $ind => $label)
                            <option value="{{ $ind }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-button class="mt-2 bg-indigo-700">
                        {{ __('custom.edit') }}
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
