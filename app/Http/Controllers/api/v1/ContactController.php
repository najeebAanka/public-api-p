<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Contact;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ], [
            'name.required' => 'Name is Empty!',
            'email.required' => ' Email Empty!',
            'message.required' => 'Message is Empty!',

        ]);
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_number = $request->mobile_number ? $request->mobile_number : '';
        $contact->subject = $request->subject ? $request->subject : '';
        $contact->message = $request->message;
        $contact->save();

        return BackEndHelper::sendSuccess('Your Message Send Successfully', '');
    }

}
