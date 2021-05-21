<x-layout>
    <div class="grid grid-cols-3 gap-4 py-8">
        @foreach($users as $user)
            <div class="bg-gray-700 rounded flex flex-row justify-between">
                <div class="flex flex-col p-4">
                    <span class="text-md mb-2 font-medium text-white">{{ $user->name }}</span>
                    <span class="text-sm text-red-400">Balance: {{ $user->balance }}</span>
                </div>
                <a href="{{ route('donate.create', $user->id) }}" class="flex justify-center items-center bg-yellow-400 rounded-r">
                    <span class="text-gray-900 text-md font-bold py-2 px-4">Donate</span>
                </a>
            </div>
        @endforeach
    </div>
</x-layout>
