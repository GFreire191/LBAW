<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
    	if(Auth::check()){

	    	$notifications = Notification::where('user_id',$user->id)->get();

	    	foreach ($notifications as $notification) {
			    // Get the user associated with the notification
			    $userNotification = User::find($notification->user_notification);

			    // Add userNotification to the notification object
			    $notification->userNotification = $userNotification;
			}

	        return view('partials.notificationsList', ['notifications' => $notifications]);
    	}
    	return redirect()->back();
    }

	public function delete(Notification $notification)
	{
		if(Auth::check() && Auth::user()->id == $notification->user_id){
			$notification->delete();
			return;
		}
		return;
		
	}

}
