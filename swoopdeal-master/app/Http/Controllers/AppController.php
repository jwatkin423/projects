<?php namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppController extends BaseController
{

    public function getIndex()
    {
        return $this->render('index');
    }

    public function getHome()
    {
        return $this->render('home');
    }

    public function getAbout()
    {
        return $this->render('about');
    }

    public function getServices()
    {
        return $this->render('services');
    }

    public function getFAQ()
    {
        return $this->render('faq');
    }

    public function getContact(Request $request)
    {
        return $this->render('contact');
    }

    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'message' => 'required',
            'name' => 'required',
            'g-recaptcha-response' => (app()->environment() === 'testing' ? [] : ['required', 'recaptcha']),
        ], [
            'g-recaptcha-response.required' => 'You must submit the recaptcha to prove you are human'
        ]);

        //Send message
        $data = $request->only(['name', 'email']);
        $data['content'] = $request->message;
        Mail::to(config('mail.contact_us.recipient'))
            ->send(new ContactMessage($data));

        return $this->render('contact')->with('success', config('mail.contact_us.success_message'));

    }
}