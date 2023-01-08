<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SchedularController extends Controller
{
    public function RunEverySecond(){
        $mailCheck = DB::table('email_logs')
        ->orderBy('id')
        ->get();

        foreach($mailCheck as $mail){
            if($mail->send_date == null){
                $user = DB::table('users')->where('id',$mail->user_id);
                try{
                    if($mail->notice_name == 'First'){
                        \Mail::to($user->first()->email)->send(new \App\Mail\FirstRenewableNotice($user->first()->email,$user->first()->next_renew_date));
                        $user->update(['first_renewable_notice_sent_date' => Carbon::now()->format('Y-m-d')]);
                        DB::table('email_logs')->where('id',$mail->id)->update([
                            'is_send'=>true,
                            'send_date'=>Carbon::now()->format('Y-m-d'),
                        ]);
                    }
                    if($mail->notice_name == 'Second'){
                        \Mail::to($user->email)->send(new \App\Mail\SecondRenewableNotice($user->email,$user->next_renew_date));
                        $user->update(['second_renewable_notice_sent_date' => Carbon::now()->format('Y-m-d')]);
                        DB::table('email_logs')->where('user_id',$user->id)->update([
                            'is_send'=>true,
                            'send_date'=>Carbon::now()->format('Y-m-d'),
                        ]);
                    }
                    if($mail->notice_name == 'Final'){

                        \Mail::to($user->first()->email)->send(new \App\Mail\FinalRenewableNotice($user->first()->email,$user->first()->next_renew_date));
                        $user->update(['final_renewable_notice_sent_date' => Carbon::now()->format('Y-m-d')]);
                        DB::table('email_logs')->where('user_id',$user->first()->id)->update([
                            'is_send'=>true,
                            'send_date'=>Carbon::now()->format('Y-m-d'),
                        ]);
                    }
                }
                catch(\throwable $ex) {
                    logger('Error While Sending mail.');
                    report($ex);
                }


            }
        }

    }
}
