<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    /** Inbox: conversations list + public board. */
    public function index(Request $request)
    {
        $me = Auth::id();

        // People I can message (everyone active except me)
        $contacts = User::where('id', '!=', $me)
            ->where('is_active', true)
            ->orderBy('name')->get(['id','name','role','photo_url','avatar_url']);

        // Private conversation with a selected user
        $activeId = $request->integer('with') ?: optional($contacts->first())->id;
        $conversation = collect();
        if ($activeId) {
            $conversation = Message::private()
                ->where(function ($q) use ($me, $activeId) {
                    $q->where(['sender_id'=>$me,'recipient_id'=>$activeId])
                      ->orWhere(['sender_id'=>$activeId,'recipient_id'=>$me]);
                })
                ->with('sender')->orderBy('created_at')->get();

            // Mark incoming as read
            Message::private()->where(['sender_id'=>$activeId,'recipient_id'=>$me])
                ->whereNull('read_at')->update(['read_at'=>now()]);
        }

        // Public board (latest 50)
        $publicMessages = Message::public()->with('sender')->latest()->take(50)->get()->reverse();

        return view('dashboard.messages', compact('contacts','conversation','activeId','publicMessages'));
    }

    public function sendPrivate(Request $request)
    {
        $data = $request->validate([
            'recipient_id' => ['required','exists:users,id'],
            'body' => ['required','string','max:2000'],
        ]);
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $data['recipient_id'],
            'scope' => 'PRIVATE',
            'body' => $data['body'],
        ]);
        return back()->with('success', 'Message sent.');
    }

    public function sendPublic(Request $request)
    {
        $data = $request->validate(['body' => ['required','string','max:2000']]);
        Message::create([
            'sender_id' => Auth::id(),
            'scope' => 'PUBLIC',
            'body' => $data['body'],
        ]);
        return back()->with('success', 'Posted to the board.');
    }

    /** Unread count for the sidebar badge (used app-wide). */
    public static function unreadCount(): int
    {
        if (! Auth::check()) return 0;
        return Message::private()->where('recipient_id', Auth::id())->whereNull('read_at')->count();
    }
}
