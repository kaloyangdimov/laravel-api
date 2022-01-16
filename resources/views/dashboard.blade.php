<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (is_null(session()->get('blizzAccessToken')))
                        <form action="/authAccess" method="get">
                            <button type="submit">
                                Authorize access
                            </button>
                        </form>
                    @else
                    <form action="/getProfileData" method="get">
                        <button type="submit">
                            WoW
                        </button>
                    </form>
                    <form action="/getApiAccount" method="get">
                        <button type="submit">
                            Diablo
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
