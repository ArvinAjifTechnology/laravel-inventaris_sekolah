<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function sendToWhatsapp(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $subject = $request->input('subject');
        $phone = "6281222828906";
        $message = $request->input('message');

        // Mengirim pesan ke WhatsApp menggunakan API WhatsApp
        $url = 'https://api.whatsapp.com/send?phone=' . $phone . '&text=' . 'Name%3A%0A' . urlencode($name). '%0AEmail%3A%0A' . urlencode($email) . '%0ASubject%3A%0A'. urlencode($subject). '%0AMessage%3A'. urlencode($message);

        // redirect();
    //    return redirect($url)->route('contact')->with('success', 'Your message has been sent via WhatsApp!');
    return redirect($url . '&success=Your+message+has+been+sent+via+WhatsApp!');
    }

    public function sendToEmail(Request $request)
    {
        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message
        );

        Mail::to('arvinajif3@gmail.com')->send(new ContactMail($data));

        return redirect()->back()->with('success', 'Your message has been sent.');
    }
}