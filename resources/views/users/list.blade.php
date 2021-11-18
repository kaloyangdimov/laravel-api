<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('custom.users_list') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-row flex-wrap flex-grow justify-center">
                        <table class="table-auto border border-solid flex-grow">
                            <thead class="border border-solid">
                                <tr>
                                    <th class="border border-solid border-left">{{ __('custom.name') }}</th>
                                    <th class="border border-solid border-left">{{ __('custom.email') }}</th>
                                    <th class="border border-solid border-left">{{ __('custom.active') }}</th>
                                    <th class="border border-solid border-left">{{ __('custom.is_admin') }}</th>
                                    <th class="border border-solid border-left">{{ __('custom.created_at') }}</th>
                                    <th class="border border-solid border-left">{{ __('custom.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="border border-solid">
                                @if ($users->isNotEmpty())
                                    @foreach ($users as $singleUser)
                                        <tr>
                                            <td class="border border-solid border-left text-center">{{ $singleUser->name }}</td>
                                            <td class="border border-solid border-left text-center">{{ $singleUser->email }}</td>
                                            <td class="border border-solid border-left text-center">{{ $singleUser->active ? __('custom.yes') : __('custom.no') }}</td>
                                            <td class="border border-solid border-left text-center">{{ $singleUser->is_admin ? __('custom.yes') : __('custom.no') }}</td>
                                            <td class="border border-solid border-left text-center">{{ $singleUser->created_at }}</td>
                                            <td class="border border-solid border-left text-center">
                                                @can('update', $singleUser)
                                                    <a href="{{ route('user.show', $singleUser->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
