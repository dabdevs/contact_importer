<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\TemporaryContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $contacts = $user->contacts()->orderBy('id', 'desc')->paginate(5);
        $temp_contacts = $user->temporary_contacts;
        $files = $user->files;

        return view('home', [
            'contacts' => $contacts,
            'temp_contacts' => $temp_contacts,
            'files' => $files
        ]);
    }
}
