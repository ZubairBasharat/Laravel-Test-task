<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscriber as subscription;


class Subscriber extends Controller
{
    public function subscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|int',
            'website_id' => 'required|int',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response([
                "success" => false,
                "message" => $error,
            ]);

        }else{
            
            $check_subscriptio = subscription::where(['user_id' => $request->user_id, 'website_id' => $request->website_id])->first();
            if(!empty($check_subscriptio))
            {
                return response([
                    "success" => false,
                    "message" => 'Already Subscribed',
                ]);
            }

            subscription::create($request->all());

            return response([
                "success" => true,
                "message" => 'Subscribed Successfully',
            ]);
        }
    }
}
