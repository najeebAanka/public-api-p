<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\Model\Subscription;
use App\Model\Testimonial;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $subscriptions = Subscription::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $subscriptions = new Subscription();
        }
        $notifications = $subscriptions->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.subscriptions.index', compact('notifications', 'search'));
    }

    public function delete(Request $request)
    {
        $notification = Subscription::find($request->id);
        $notification->delete();
        return response()->json();
    }
}
