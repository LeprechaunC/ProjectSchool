<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id === $user->id 
                    ? $message->receiver_id 
                    : $message->sender_id;
            })
            ->map(function ($messages) use ($user) {
                $otherUser = $messages->first()->sender_id === $user->id 
                    ? $messages->first()->receiver 
                    : $messages->first()->sender;
                
                return [
                    'user' => $otherUser,
                    'last_message' => $messages->first(),
                    'unread_count' => $messages->where('receiver_id', $user->id)
                        ->where('read', false)
                        ->count()
                ];
            });

        return response()->json($conversations->values());
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        $messages = Message::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $currentUser->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json($messages);
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $messageData = [
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'content' => $request->content ?? '',
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Store the image in the public disk
            $path = $image->storeAs('message_images', $imageName, 'public');
            
            if ($path) {
                $messageData['image_path'] = $path;
            } else {
                return response()->json(['error' => 'Failed to store image'], 500);
            }
        }

        $message = Message::create($messageData);
        $message->load('sender', 'receiver');

        return response()->json($message, 201);
    }

    public function unreadCount()
    {
        $count = Message::where('receiver_id', auth()->id())
            ->where('read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function destroy(Message $message)
    {
        // Check if the authenticated user is either the sender or receiver
        if ($message->sender_id !== auth()->id() && $message->receiver_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the image file if it exists
        if ($message->image_path) {
            Storage::disk('public')->delete($message->image_path);
        }

        $message->delete();

        return response()->json(['message' => 'Message deleted successfully']);
    }

    public function getUsers()
    {
        $users = User::where('id', '!=', auth()->id())
            ->select('id', 'name', 'email')
            ->get();

        return response()->json($users);
    }
} 