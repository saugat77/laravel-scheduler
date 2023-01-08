<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ReminderRenewsubscription;
class SendRenewSubscriptionEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send email notificatin to user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Get all reminder
        $users = DB::table('users')
        ->orderBy('id')
        ->get();

        foreach($users as $user){
                $id = DB::table('users')->get()->pluck('id')->first();
                $updateUser = DB::table('users')->where('id',$id);
                $firstRenewablenotice = Carbon::parse($user->next_renew_date)->subDays(30)->format('Y-m-d');
                $secondRenewablenotice = Carbon::parse($user->next_renew_date)->subDays(15)->format('Y-m-d');
                $finalRenewablenotice = Carbon::parse($user->next_renew_date)->subDays(1)->format('Y-m-d');
                if($firstRenewablenotice == Carbon::now()->format('Y-m-d')){
                    DB::table('email_logs')->insert([
                        'user_id' => $user->id,
                        'subject'=> 'First Renewable Notice',
                        'message'=>"Dear $user->email, Your subscription is expiring soon.Renew them now.Your subscription is expiring on  $user->next_renew_date.",
                        'initiated_date' => Carbon::now()->format('Y-m-d'),
                        'to_email_address' =>$user->email,
                        'notice_type' => 'First'

                    ]);
                    try{
                        \Mail::to($user->first()->email)->send(new \App\Mail\FirstRenewableNotice($user->first()->email,$user->first()->next_renew_date));
                        $updateUser->update(['first_renewable_notice_sent_date' => Carbon::now()->format('Y-m-d')]);
                        DB::table('email_logs')->where('user_id',$user->id)->update([
                            'is_send'=>true,
                            'send_date'=>Carbon::now()->format('Y-m-d'),
                        ]);
                        }
                        catch(\throwable $ex) {
                            logger('Error While sending mail.');
                            report($ex);
                        }

                }
                if($secondRenewablenotice == Carbon::now()->format('Y-m-d')){
                    DB::table('email_logs')->insert([
                        'user_id' => $user->id,
                        'subject'=> 'Second Renewable Notice',
                        'message'=>"Dear $user->email, Your subscription is expiring soon.Renew them now.Your subscription is expiring on  $user->next_renew_date.",
                        'initiated_date' => Carbon::now()->format('Y-m-d'),
                        'to_email_address' =>$user->email,
                        'notice_type' => 'Second'

                    ]);
                    try{
                    \Mail::to($user->email)->send(new \App\Mail\SecondRenewableNotice($user->email,$user->next_renew_date));
                    $updateUser->update(['second_renewable_notice_sent_date' => Carbon::now()->format('Y-m-d')]);
                    DB::table('email_logs')->where('user_id',$user->id)->update([
                        'is_send'=>true,
                        'send_date'=>Carbon::now()->format('Y-m-d'),
                    ]);
                }
                catch(\throwable $ex) {
                    logger('Error While sending mail.');
                    report($ex);
                }

                }
                if($finalRenewablenotice == Carbon::now()->addDays(364)->format('Y-m-d')){
                    DB::table('email_logs')->insert([
                        'user_id' => $user->id,
                        'subject'=> 'Final Renewable Notice',
                        'message'=>"Dear $user->email, Your subscription is expiring soon.Renew them now.Your subscription is expiring on  $user->next_renew_date.",
                        'initiated_date' => Carbon::now()->format('Y-m-d'),
                        'to_email_address' =>$user->email,
                        'notice_type' => 'Final'
                    ]);
                    try{
                    \Mail::to($user->email)->send(new \App\Mail\FinalRenewableNotice($user->email,$user->next_renew_date));
                    $updateUser->update(['second_renewable_notice_sent_date' => Carbon::now()->format('Y-m-d')]);
                    DB::table('email_logs')->where('user_id',$user->id)->update([
                        'is_send'=>true,
                        'send_date'=>Carbon::now()->format('Y-m-d'),
                    ]);
                      }
                    catch(\throwable $ex) {
                        logger('Error While sending mail');
                        report($ex);
                    }

                }

            }

        return Command::SUCCESS;
}}
