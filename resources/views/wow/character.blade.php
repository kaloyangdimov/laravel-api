<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('custom.character_view') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{$characterData->name}}<br>
                    {{$characterData->character->realm->name->en_US}}<br>
                    {{$characterData->position->zone->name->en_US}} {{$characterData->position->map->name->en_US}}<br>
                    @foreach ($characterData->protected_stats as $statName => $statValue)
                        {{ __('custom.'.$statName) }}: {{number_format($statValue, 0, '.', ' ')}}<br>
                    @endforeach
                   <b> <a href="{{route('view.char.achievments', [$characterData->character->realm->slug, $characterData->name])}}">{{__('custom.achievements')}}</a></b>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
