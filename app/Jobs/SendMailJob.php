<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use App\Models\Subscriber;
use Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notifiy_post = Post::where(['notification'=> 0])->first();

        $subscribers = Subscriber::where(['website_id' => $notifiy_post->id])->with('user')->get();

        foreach($subscribers as $subscriber)
        {
              $details = [
                'title' => 'New Post',
                'body' => $notifiy_post->post
            ];
           
            \Mail::to($subscriber->user->email)->send(new \App\Mail\EmailNotification($details));
        }
    }
}
