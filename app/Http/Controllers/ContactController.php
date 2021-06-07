<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Contact::orderBy('read', 'ASC')->orderBy('created_at', 'DESC')->get();
        return response()->json([
            'data' => $messages
        ], 200);
    }

    /**
     * Display the count of new contacts message.
     *
     * @return \Illuminate\Http\Response
     */
    public function news()
    {
        $newsMessagesCount = Contact::where('read', 0)->count();

        return response()->json([
            'data' => $newsMessagesCount
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $locale)
    {
        App::setLocale($locale);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:contacts',
            'country' => 'required|string',
            'countryCode' => 'required|string',
            'phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'address' => 'required|string',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $message = Contact::create($data);
        return response()->json([
            'message' => $message
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return response()->json([
            'data' => $contact,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email|unique:contacts',
            'country' => 'string',
            'countryCode' => 'string',
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'address' => 'string',
            'message' => 'string',
            'read' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $contact->update($data);
        return response()->json([
            'data' => $contact
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json([
            'message' => 'Mensaje de contacto eliminado correctamente',
        ]);
    }
}
