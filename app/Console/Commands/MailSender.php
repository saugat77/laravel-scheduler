<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MailSender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the mail log database table frequently for log';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $mailCheck = DB::table('email_logs')
        ->orderBy('id')
        ->get();
        foreach($mailCheck as $mail){
            if($mail->send_date == null){
                $user = DB::table('users')->where('id',$mail->user_id);
                \Mail::to($user->first()->email)->send(new \App\Mail\FinalRenewableNotice($user->first()->email,$user->first()->next_renew_date));
                $user->update(['second_renewable_notice_sent_date' => Carbon::now()->format('Y-m-d')]);

                DB::table('email_logs')->where('user_id',$user->first()->id)->update([
                    'is_send'=>true,
                    'send_date'=>Carbon::now()->format('Y-m-d'),
                ]);
            }
        }

        return Command::SUCCESS;
    }
}
