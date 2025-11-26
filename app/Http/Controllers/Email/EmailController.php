<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendWelcomeEmail()
    {
        $title = 'Light weight baby!';
        $body = 'Thank you for gym!';

        Mail::to('karagez28@gmail.com')->send(new WelcomeMail($title, $body));

        return "Email sent successfully!";
    }
}
