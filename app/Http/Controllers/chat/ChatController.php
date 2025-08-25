<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\tbl_chat_messages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display the chat interface
     */
    public function index()
    {
        $currentUser = Auth::user();
        $users = User::where('id', '!=', $currentUser->id)->get();
        
        return view('chat.chat', compact('users', 'currentUser'));
    }

    /**
     * Get all users for chat
     */
    public function getUsers()
    {
        try {
            $currentUser = Auth::user();
            $users = User::where('id', '!=', $currentUser->id)
                        ->with(['department', 'position'])
                        ->select('id', 'name', 'email', 'photo', 'department_id', 'position_id', 'created_at')
                        ->get()
                        ->map(function($user) {
                            $user->photo_url = $user->photo_url ?? asset('dist/images/profile-11.jpg');
                            return $user;
                        });

            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get conversations for current user
     */
    public function getConversations()
    {
        try {
            $currentUser = Auth::user();
            
            // Get conversations where user is either sender or receiver
            $conversations = tbl_chat_messages::select(
                'from_user_id',
                'to_user_id',
                'message',
                'status',
                'created_at'
            )
            ->where(function($query) use ($currentUser) {
                $query->where('from_user_id', $currentUser->id)
                      ->orWhere('to_user_id', $currentUser->id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($currentUser) {
                // Group by the other user in the conversation
                return $message->from_user_id == $currentUser->id 
                    ? $message->to_user_id 
                    : $message->from_user_id;
            })
            ->map(function($messages, $otherUserId) {
                $latestMessage = $messages->first();
                $otherUser = User::with(['department', 'position'])->find($otherUserId);
                
                return [
                    'user_id' => $otherUserId,
                    'user_name' => $otherUser->name,
                    'user_photo' => $otherUser->photo_url ?? asset('dist/images/profile-11.jpg'),
                    'user_email' => $otherUser->email,
                    'user_role' => $otherUser->position ? $otherUser->position->name : 'User',
                    'user_department' => $otherUser->department ? $otherUser->department->name : 'General',
                    'user_created_at' => $otherUser->created_at,
                    'last_message' => $latestMessage->message,
                    'last_message_time' => $latestMessage->created_at->diffForHumans(),
                    'unread_count' => $messages->where('to_user_id', Auth::id())
                                              ->where('status', 'unread')
                                              ->count()
                ];
            })
            ->values();

            return response()->json([
                'success' => true,
                'conversations' => $conversations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching conversations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get messages between two users
     */
    public function getMessages(Request $request)
    {
        try {
            $request->validate([
                'other_user_id' => 'required|exists:users,id'
            ]);

            $currentUser = Auth::user();
            $otherUserId = $request->other_user_id;

            // Get messages between the two users
            $messages = tbl_chat_messages::where(function($query) use ($currentUser, $otherUserId) {
                $query->where('from_user_id', $currentUser->id)
                      ->where('to_user_id', $otherUserId);
            })->orWhere(function($query) use ($currentUser, $otherUserId) {
                $query->where('from_user_id', $otherUserId)
                      ->where('to_user_id', $currentUser->id);
            })
            ->with(['sender:id,name,photo', 'receiver:id,name,photo'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->values()
            ->map(function($message) use ($currentUser) {
                $isOwnMessage = $message->from_user_id == $currentUser->id;
                
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'is_own' => $isOwnMessage,
                    'sender_name' => $message->sender->name,
                    'sender_photo' => $message->sender->photo_url ?? asset('dist/images/profile-11.jpg'),
                    'time' => $message->created_at->format('H:i'),
                    'date' => $message->created_at->format('M d, Y'),
                    'status' => $message->status
                ];
            });

            // Mark messages as read
            tbl_chat_messages::where('from_user_id', $otherUserId)
                            ->where('to_user_id', $currentUser->id)
                            ->where('status', 'unread')
                            ->update(['status' => 'read']);

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching messages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'to_user_id' => 'required|exists:users,id',
                'message' => 'required|string|max:1000'
            ]);

            $currentUser = Auth::user();
            
            $chatMessage = tbl_chat_messages::create([
                'from_user_id' => $currentUser->id,
                'to_user_id' => $request->to_user_id,
                'message' => $request->message,
                'status' => 'unread'
            ]);

            $chatMessage->load(['sender:id,name,photo', 'receiver:id,name,photo']);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'chat_message' => [
                    'id' => $chatMessage->id,
                    'message' => $chatMessage->message,
                    'is_own' => true,
                    'sender_name' => $chatMessage->sender->name,
                    'sender_photo' => $chatMessage->sender->photo_url ?? asset('dist/images/profile-11.jpg'),
                    'time' => $chatMessage->created_at->format('H:i'),
                    'date' => $chatMessage->created_at->format('M d, Y'),
                    'status' => $chatMessage->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request)
    {
        try {
            $request->validate([
                'from_user_id' => 'required|exists:users,id'
            ]);

            $currentUser = Auth::user();
            
            tbl_chat_messages::where('from_user_id', $request->from_user_id)
                            ->where('to_user_id', $currentUser->id)
                            ->where('status', 'unread')
                            ->update(['status' => 'read']);

            return response()->json([
                'success' => true,
                'message' => 'Messages marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking messages as read: ' . $e->getMessage()
            ], 500);
        }
    }
}
