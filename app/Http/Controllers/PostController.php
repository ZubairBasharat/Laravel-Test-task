<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\Jobs\SendMailJob;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function create_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website_id' => 'required',
            'post' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response([
                "success" => false,
                "message" => $error,
            ]);

        }else{
            $post = [
                'website_id' => $request->website_id,
                'post' => $request->post,
            ];

            SendMailJob::dispatch()->delay(now()->addSeconds(5));

            Post::create($post);

            return response([
                "success" => true,
                "message" => 'Post Created Successfully'
            ]);
        }   
    }
}
