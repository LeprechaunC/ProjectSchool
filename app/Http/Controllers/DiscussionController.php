<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Reply;
use App\Models\Like;
use App\Models\DiscussionGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DiscussionController extends Controller
{
    public function index()
    {
        $genres = DiscussionGenre::withCount('discussions')->get();
        $discussions = Discussion::with(['user', 'team', 'genre'])
            ->withCount('replies')
            ->withCount(['likes' => function($query) {
                $query->where('likeable_type', Discussion::class);
            }])
            ->when(request('team_id'), function ($query, $teamId) {
                return $query->where('team_id', $teamId);
            })
            ->when(!request('team_id'), function ($query) {
                return $query->where('is_public', true);
            })
            ->latest()
            ->paginate(10);

        return Inertia::render('Discussions/Index', [
            'discussions' => $discussions,
            'genres' => $genres,
        ]);
    }

    public function show(Discussion $discussion)
    {
        $discussion->load(['user', 'team', 'replies.user', 'replies.replies.user'])
            ->loadCount('replies')
            ->loadCount(['likes' => function($query) {
                $query->where('likeable_type', Discussion::class);
            }]);

        return Inertia::render('Discussions/Show', [
            'discussion' => $discussion
        ]);
    }

    public function create(Request $request)
    {
        $genres = DiscussionGenre::all();
        $teams = auth()->user()->teams;
        $selectedGenre = null;
        if ($request->has('genre')) {
            $selectedGenre = DiscussionGenre::where('slug', $request->input('genre'))->first();
        }
        return Inertia::render('Discussions/Create', [
            'genres' => $genres,
            'teams' => $teams,
            'selectedGenre' => $selectedGenre,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'team_id' => 'nullable|exists:teams,id',
            'genre_id' => 'required|exists:discussion_genres,id',
            'is_public' => 'boolean',
        ]);

        $discussion = auth()->user()->discussions()->create($validated);

        return redirect()->route('discussions.show', $discussion)
            ->with('success', 'Discussion created successfully.');
    }

    public function reply(Request $request, Discussion $discussion)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:replies,id',
        ]);

        $reply = $discussion->replies()->create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return back()->with('success', 'Reply added successfully.');
    }

    public function like(Discussion $discussion)
    {
        $like = $discussion->likes()->firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Discussion liked successfully.');
    }

    public function unlike(Discussion $discussion)
    {
        $discussion->likes()->where('user_id', Auth::id())->delete();

        return back()->with('success', 'Discussion unliked successfully.');
    }

    public function likeReply(Reply $reply)
    {
        $like = $reply->likes()->firstOrCreate([
            'user_id' => Auth::id(),
            'likeable_type' => Reply::class,
        ]);

        return back()->with('success', 'Reply liked successfully.');
    }

    public function unlikeReply(Reply $reply)
    {
        $reply->likes()
            ->where('user_id', Auth::id())
            ->where('likeable_type', Reply::class)
            ->delete();

        return back()->with('success', 'Reply unliked successfully.');
    }

    public function genre($slug)
    {
        $genre = DiscussionGenre::where('slug', $slug)->withCount('discussions')->firstOrFail();
        $discussions = Discussion::with(['user', 'team', 'genre'])
            ->where('genre_id', $genre->id)
            ->withCount('replies')
            ->withCount(['likes' => function($query) {
                $query->where('likeable_type', Discussion::class);
            }])
            ->latest()
            ->paginate(10);

        return Inertia::render('Discussions/Genre', [
            'genre' => $genre,
            'discussions' => $discussions,
        ]);
    }

    public function update(Request $request, Discussion $discussion)
    {
        // Check if user is authorized to edit
        if ($request->user()->id !== $discussion->user_id && $request->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $discussion->update($validated);

        return back()->with('success', 'Discussion updated successfully.');
    }

    public function destroy(Request $request, Discussion $discussion)
    {
        // Check if user is authorized to delete
        if ($request->user()->id !== $discussion->user_id && $request->user()->role !== 'admin') {
            abort(403);
        }

        $discussion->delete();

        return redirect()->route('discussions.index')
            ->with('success', 'Discussion deleted successfully.');
    }
} 