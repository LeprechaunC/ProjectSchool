<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Diskusijas</h2>
                        <a href="{{ route('discussions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Jauna Diskusija
                        </a>
                    </div>

                    <div class="space-y-4">
                        @foreach($discussions as $discussion)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <a href="{{ route('discussions.show', $discussion) }}" class="text-xl font-semibold text-gray-900 hover:text-blue-600">
                                            {{ $discussion->title }}
                                        </a>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Ievietoja {{ $discussion->user->name }} 
                                            @if($discussion->team)
                                                komandā {{ $discussion->team->name }}
                                            @endif
                                            {{ $discussion->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-600">
                                            {{ $discussion->replies_count }} atbildes
                                        </span>
                                        <span class="text-gray-600">
                                            {{ $discussion->likes_count }} patīk
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $discussions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 