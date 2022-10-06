@extends('layouts.app')

@section('content')

@php
   $contact_fields = config('app.contact_fields'); 
   $show_fields = count($temp_contacts) > 0; 
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header text-left">{{ __('Upload file to import contacts') }}</h3>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif 

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif 

                    @if (session('fields_errors'))
                        <div class="alert alert-danger" role="alert">
                            {!! session('fields_errors') !!}
                        </div>
                    @endif 

                    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <span class="text-info">{{ __('Allowed extensions: .csv') }}</span>

                            <div class="col-md-6 form-inline">
                                <input id="file" type="file" class="form-control @error('file') is-invalid @enderror" name="file" value="{{ old('file') }}" required>
                                
                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Upload') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('store') }}" method="POST">
                        @csrf
                        @if($show_fields)
                            <table class="table mt-2">
                                <thead>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Date of birth</th>
                                    <th>Credit Card Number</th>
                                    <th>Credit Card Network</th>
                                </thead>
                                <tbody>
                                    @forelse ($temp_contacts as $contact)
                                        <tr>
                                            <td>
                                                {{ $contact->name }}
                                                <input name="name[]" type="hidden" value="{{ $contact->name }}">
                                            </td>
                                            <td>
                                                {{ $contact->phone }}
                                                <input name="phone[]" type="hidden" value="{{ $contact->phone }}">
                                            </td>
                                            <td>
                                                {{ $contact->email }}
                                                <input name="email[]" type="hidden" value="{{ $contact->email }}">
                                            </td>
                                            <td>
                                                {{ $contact->address }}
                                                <input name="address[]" type="hidden" value="{{ $contact->address }}">
                                            </td>
                                            <td>
                                                {{ $contact->birthdate }}
                                                <input name="birthdate[]" type="hidden" value="{{ $contact->birthdate }}">
                                            </td>
                                            <td>
                                                {{ $contact->cc_number }}
                                                <input name="cc_number[]" type="hidden" value="{{ $contact->cc_number }}">
                                            </td>
                                            <td>
                                                {{ $contact->cc_network }}
                                                <input name="cc_network[]" type="hidden" value="{{ $contact->cc_network }}">
                                                <input name="file_id" type="hidden" value="{{ $contact->file_id }}">
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse

                                    @if ($show_fields)
                                        <tr>
                                            <td>
                                                <select name="fields[]" class="w-100">
                                                    <option value="name" selected>Name</option>
                                                    <option value="phone">Phone</option>
                                                    <option value="email">Email</option>
                                                    <option value="address">Address</option>
                                                    <option value="birthdate">Date of birth</option>
                                                    <option value="cc_number">Credit Card Number</option>
                                                    <option value="cc_network">Credit Card Network</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="fields[]" class="w-100">
                                                    <option value="name">Name</option>
                                                    <option value="phone" selected>Phone</option>
                                                    <option value="email">Email</option>
                                                    <option value="address">Address</option>
                                                    <option value="birthdate">Date of birth</option>
                                                    <option value="cc_number">Credit Card Number</option>
                                                    <option value="cc_network">Credit Card Network</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="fields[]" class="w-100">
                                                    <option value="name">Name</option>
                                                    <option value="phone">Phone</option>
                                                    <option value="email" selected>Email</option>
                                                    <option value="address">Address</option>
                                                    <option value="birthdate">Date of birth</option>
                                                    <option value="cc_number">Credit Card Number</option>
                                                    <option value="cc_network">Credit Card Network</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="fields[]" class="w-100">
                                                    <option value="name">Name</option>
                                                    <option value="phone">Phone</option>
                                                    <option value="email">Email</option>
                                                    <option value="address" selected>Address</option>
                                                    <option value="birthdate">Date of birth</option>
                                                    <option value="cc_number">Credit Card Number</option>
                                                    <option value="cc_network">Credit Card Network</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="fields[]" class="w-100">
                                                    <option value="name">Name</option>
                                                    <option value="phone">Phone</option>
                                                    <option value="email">Email</option>
                                                    <option value="address">Address</option>
                                                    <option value="birthdate" selected>Date of birth</option>
                                                    <option value="cc_number">Credit Card Number</option>
                                                    <option value="cc_network">Credit Card Network</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="fields[]" class="w-100">
                                                    <option value="name">Name</option>
                                                    <option value="phone">Phone</option>
                                                    <option value="email">Email</option>
                                                    <option value="address">Address</option>
                                                    <option value="birthdate">Date of birth</option>
                                                    <option value="cc_number">Credit Card Number</option>
                                                    <option value="cc_network" selected>Credit Card Network</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="fields[]" class="w-100">
                                                    <option value="name">Name</option>
                                                    <option value="phone">Phone</option>
                                                    <option value="email">Email</option>
                                                    <option value="address">Address</option>
                                                    <option value="birthdate">Date of birth</option>
                                                    <option value="cc_number" selected>Credit Card Number</option>
                                                    <option value="cc_network">Credit Card Network</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <button type="submit" class="btn btn-success" id="btn_save">
                                                    {{ __('Save') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(count($files) > 0)
        <div class="row justify-content-center mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Uploaded files') }}</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>File</th>
                                <th>Date uploaded</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @forelse ($files as $file)
                                    <tr>
                                        <td>{{ $file->name }}</td>
                                        <td>{{ $file->created_at }}</td>
                                        <td>{{ $file->status }}</td>
                                    </tr>
                                @empty 
                                    <center>No files found.</center>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Contacts') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Date of birth</th>
                            <th>Credit Card Number</th>
                            <th>Credit Card Network</th>
                        </thead>
                        <tbody>
                            @forelse ($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->phone }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->address }}</td>
                                    <td>{{ $contact->birthdate }}</td>
                                    <td>{{ $contact->cc_number }}</td>
                                    <td>{{ $contact->cc_network }}</td>
                                </tr>
                            @empty 
                                <center>No contacts yet.</center>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


