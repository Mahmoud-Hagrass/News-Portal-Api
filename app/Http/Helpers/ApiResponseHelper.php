<?php

function apiResponse($status , $message , $data = null){
    $response = [
        'status' => $status , 
        'message' => $message  , 
    ] ; 
    if(!$data){
        return response()->json($response) ; 
    }
    $response['data'] = $data  ;
    return response()->json($response) ; 
}