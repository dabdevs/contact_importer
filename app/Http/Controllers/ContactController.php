<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Imports\ContactsImport;
use App\Models\Contact;
use App\Models\TemporaryContact;
use Illuminate\Http\Request;
use Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Process uploaded csv file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $data = Excel::toArray(new ContactsImport, $request->file)[0];

        foreach ($data as $contact) {
            $contact["user_id"] = Auth::user()->id; 
            TemporaryContact::create($contact);
        }

        return redirect('home')->with('success', 'File uploaded successfully!');
    }

    /**
     * Store imported contacts
     *
     */
    public function store(Request $request)
    {
        $fields = $request->fields; 
        $fields_errors = '';
        
        DB::beginTransaction();
        try {
            for ($i=0; $i < count($request->name); $i++) { 
                $data[$fields[0]] = $request->name[$i];
                $data[$fields[1]] = $request->phone[$i];
                $data[$fields[2]] = $request->email[$i];
                $data[$fields[3]] = $request->address[$i];
                $data[$fields[4]] = $request->birthdate[$i];
                $data[$fields[5]] = $request->cc_number[$i];
                $data[$fields[6]] = $request->cc_network[$i];
                
                $validator = Validator::make($data, [
                    "name"    => "required|min:3",
                    "email"  => "required|email|unique:contacts",
                    "phone"  => "required|integer",
                    "address"  => "required|string",
                    "birthdate"  => "required|date",
                    "cc_number"  => "required|string",
                    "cc_network"  => "required|string"
                ]);
    
                $temp_contact = TemporaryContact::where('email', $request->email[$i])->first();
                
                if ($validator->fails()) {
                    $email = $request->email[$i];
                    $msg = implode('<br>', $validator->messages()->all());
                    $fields_errors .= "<br>".$email." could not be saved. <br> Errors: ".$msg;
                    Session::flash('fields_errors', $fields_errors);
                } else {
                    $data["birthdate"] = date('Y-m-d', strtotime($request->birthdate[$i]));
                    $data["user_id"] = Auth::user()->id;
                    Contact::create($data);
                    $temp_contact->delete();
                }   
            }
            
            TemporaryContact::truncate();
            DB::commit();

            return back()->with('success', 'Contacts saved successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
