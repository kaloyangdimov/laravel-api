<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('custom.character_list') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($profile)
                        @foreach ($profile['heroes'] as $singleHero)
                             <a href="{{route('diablo.char', $singleHero['id'])}}">
                                <b>{{$singleHero['name']}}</b>
                            </a> - {{$singleHero['class']}} - {{$singleHero['level']}} <br>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
