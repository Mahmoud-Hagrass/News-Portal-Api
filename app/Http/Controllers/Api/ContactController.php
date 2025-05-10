<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function storeContact(StoreContactRequest $request)
    {
        $request->merge([
            'ip_address' => $request->ip() , 
        ]) ;

        $request = $request->only([
                'name' ,
                'email' , 
                'phone' , 
                'subject' ,
                'message' ,
                'address' , 
                'ip_address' , 
                'create_at' => now() , 
                'updated_at' => now() , 
        ]) ; 

        $contact = Contact::create($request) ; 

        if(!$contact){
            return apiResponse(409 , 'Try Aagin!') ; 
        }
        return apiResponse(201 , 'Contact Created Successfully!') ; 
    }
}
