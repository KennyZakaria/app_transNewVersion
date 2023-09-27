<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Mail\ContactSaved;
use Illuminate\Support\Facades\Mail;

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

        $contact = new Contact($validatedData);
        $recipientEmail = config('app.recipient_email');
        Mail::to($recipientEmail)->send(new ContactSaved($contact));
        $contact->save();
        return $this->sendResponse('Contact enregistré avec succès', $contact);
    }

}
