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
    public function index($field = null, $order = 'DESC')
    {
        $messages = $field ? Contact::orderBy($field, $order)->paginate(8) : Contact::orderBy('read', 'ASC')->orderBy('created_at', 'DESC')->orderBy('favorite', 'DESC')->paginate(8);
        return response()->json([
            'messages' => $messages
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
            'message' => $contact,
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
        $data = $request->all();
        $contact->update($data);
        return response()->json([
            'message' => $contact
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
            'message' => $contact,
        ]);
    }

    /**
     * Display a listing of the resource by search.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = $request->search;
        if ($search != "") {
            $contact = Contact::where('name', 'LIKE', '%' . $search . '%')->orWhere('message', 'LIKE', '%' . $search . '%')->orderBy('read', 'ASC')->orderBy('created_at', 'DESC')->paginate(5);
            if (count($contact) > 0) {
                return response()->json([
                    'messages' => $contact
                ]);
            }
            return response()->json([
                'errors' => 'No pudimos encontrar registros para tu búsqueda'
            ], 400);
        } else {
            $contact = Contact::orderBy('read', 'ASC')->orderBy('created_at', 'DESC')->orderBy('favorite', 'DESC')->paginate(8);
            return response()->json([
                'messages' => $contact
            ]);
        }
    }
}
