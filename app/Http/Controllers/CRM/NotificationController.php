<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Fetch notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $unread = $user->unreadNotifications;
        $read = $user->readNotifications()->take(10)->get(); // Get recent read ones
        
        return response()->json([
            'unread' => $unread,
            'read' => $read,
            'unread_count' => $unread->count(),
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->delete();
        }
        return response()->json(['success' => true]);
    }
    
    /**
     * Test notification (Development/Testing only)
     */
    public function testNotification(Request $request)
    {
        $user = $request->user();
        $user->notify(new \App\Notifications\SystemNotification(
            'Welcome to NexaFlow',
            'Your account has been fully configured and is ready to use.',
            'bi-rocket-fill',
            'primary',
            route('dashboard')
        ));
        
        return back()->with('success', 'Test notification sent!');
    }
}
