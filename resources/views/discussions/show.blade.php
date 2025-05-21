<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Discussion Header -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $discussion->title }}</h1>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span>Ievietoja {{ $discussion->user->name }}</span>
                            @if($discussion->team)
                                <span class="mx-2">•</span>
                                <span>komandā {{ $discussion->team->name }}</span>
                            @endif
                            <span class="mx-2">•</span>
                            <span>{{ $discussion->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Discussion Content -->
                    <div class="prose max-w-none mb-8">
                        {{ $discussion->content }}
                    </div>

                    <!-- Like Button -->
                    <div class="flex items-center space-x-4 mb-8">
                        <form action="{{ route('discussions.like', $discussion) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                                <span>{{ $discussion->likes_count }} Patīk</span>
                            </button>
                        </form>
                    </div>

                    <!-- Reply Form -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Pievienot Atbildi</h3>
                        <form action="{{ route('discussions.reply', $discussion) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <textarea name="content" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required></textarea>
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ievietot Atbildi
                            </button>
                        </form>
                    </div>

                    <!-- Replies -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold">{{ $discussion->replies->count() }} Atbildes</h3>
                        @foreach($discussion->replies->whereNull('parent_id') as $reply)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold">{{ $reply->user->name }}</span>
                                            <span class="text-sm text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mt-2">{{ $reply->content }}</p>
                                    </div>
                                    <form action="{{ route('replies.like', $reply) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                            </svg>
                                            <span>{{ $reply->likes_count }}</span>
                                        </button>
                                    </form>
                                </div>

                                <!-- Nested Replies -->
                                @if($reply->replies->count() > 0)
                                    <div class="ml-8 mt-4 space-y-4">
                                        @foreach($reply->replies as $nestedReply)
                                            <div class="border-l-2 pl-4">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="font-semibold">{{ $nestedReply->user->name }}</span>
                                                            <span class="text-sm text-gray-500">{{ $nestedReply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="mt-2">{{ $nestedReply->content }}</p>
                                                    </div>
                                                    <form action="{{ route('replies.like', $nestedReply) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                                            </svg>
                                                            <span>{{ $nestedReply->likes_count }}</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Reply to Reply Form -->
                                <div class="ml-8 mt-4">
                                    <form action="{{ route('discussions.reply', $discussion) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                                        <div class="mb-2">
                                            <textarea name="content" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Atbildēt uz šo komentāru..." required></textarea>
                                        </div>
                                        <button type="submit" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-1 px-3 rounded">
                                            Atbildēt
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 