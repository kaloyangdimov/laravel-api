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
                    @if ($accounts)

                        @foreach ($accounts as $account => $accountData)
                            @foreach ($accountData->characters as $index => $singleCharacterObject)
                                @if (!in_array($singleCharacterObject->name, ['Dattass', 'Spankess', 'Beberonche']))
                                <a href="{{route('view.char', [$singleCharacterObject->realm->id, $singleCharacterObject->id])}}">
                                    <b>{{$singleCharacterObject->name}}</b>
                                </a> - {{$singleCharacterObject->playable_class->name->en_US}} - {{$singleCharacterObject->playable_race->name->en_US}} - {{$singleCharacterObject->faction->type}} - {{$singleCharacterObject->level}}<br>
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
