<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Mail\ContactSaved;
use Illuminate\Support\Facades\Mail;
use Infobip\Api\SmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;

class ContactController extends BaseController
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'subject' => 'nullable|string',
            'first_name' => 'nullable|string',
            'email' => 'nullable|email',
            'category' => 'nullable|string',
            'message' => 'nullable|string',
        ]);
        $BASE_URL = "https://j3dl59.api.infobip.com";
        $API_KEY = "799f40433c33f48823bbc046ed1962db-683997cd-f964-4410-91dc-697f6200d044";

        $SENDER = "InfoSMS";
        $RECIPIENT = "212695116738";
        $MESSAGE_TEXT = "This is a sample message";



        $contact = new Contact($validatedData);
        $contact->save();

}
}
