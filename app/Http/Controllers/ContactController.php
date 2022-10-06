<?php

namespace App\Http\Controllers;

use App\Imports\ContactsImport;
use App\Models\Contact;
use App\Models\File;
use App\Models\TemporaryContact;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;

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

        $user = Auth::user();

        $file = File::create([ 
            'name' => $request->file->getClientOriginalName(),
            'user_id' => $user->id
        ]);

        $data = Excel::toArray(new ContactsImport, $request->file)[0];

        if (empty($data)) 
            return redirect('home')->with('error', 'Please upload a non empty file!');

        foreach ($data as $contact) {
            $contact["user_id"] = $user->id; 
            $contact["file_id"] = $file->id; 
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
        $errors_count = 0;
        $user = Auth::user();
        
        DB::beginTransaction();
        try {
            $file = File::findOrFail($request->file_id);
            $file->status = 'Processing';
            $file->save();

            for ($i=0; $i < count($request->name); $i++) { 
                $data[$fields[0]] = $request->name[$i];
                $data[$fields[1]] = $request->phone[$i];
                $data[$fields[2]] = $request->email[$i];
                $data[$fields[3]] = $request->address[$i];
                $data[$fields[4]] = $request->birthdate[$i];
                $data[$fields[5]] = $request->cc_number[$i];
                $data[$fields[6]] = $request->cc_network[$i];
                
                $validator = Validator::make($data, [
                    "name"    => "required|alpha_dash",
                    "email"  => "required|email",
                    "phone"  => "required", //|regex:/(01)[0-9]{9}/
                    "address"  => "required|string",
                    "birthdate"  => "required|date",'unique:cards',
                    "cc_number"  =>  ["required"],
                    "cc_network"  => "required|string"
                ]);
    
                $temp_contact = TemporaryContact::where('email', $request->email[$i])->first();
                $email = $request->email[$i];
                $name = $request->name[$i];

                if ($validator->fails()) {
                    $msg = implode('<br>', $validator->messages()->all());
                    $fields_errors .= "<br>".$name."(".$email.") could not be saved. <br> Errors: ".$msg;
                    Session::flash('fields_errors', $fields_errors);
                    $errors_count++;
                } else {
                    $contact = Contact::where(["email" => $email, "user_id" => $user->id])->first();
                    
                    if ($contact != null) {
                        $msg = "Contact already exists.";
                        $fields_errors .= "<br>".$name."(".$email.") could not be saved. <br> Errors: ".$msg;
                        Session::flash('fields_errors', $fields_errors);
                        $errors_count++;
                    } else {
                        $data["birthdate"] = date('Y-m-d', strtotime($request->birthdate[$i]));
                        $data["user_id"] = $user->id;
                        Contact::create($data);
                        $temp_contact->delete();
                    }
                }   
            }

            $file->status = count($request->name) == $errors_count? 'Failed' : 'Finished';
            $file->save();

            if ($file->status === 'Failed') {
                $message = 'Contacts could not be saved!';
                $color_class = 'error';
            } else {
                $message = 'Contacts saved successfully!';
                $color_class = 'success';
            }
            
            Auth::user()->temporary_contacts()->delete();
            DB::commit();

            return back()->with($color_class, $message);
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
