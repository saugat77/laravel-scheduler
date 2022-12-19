<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\SchedularController;

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
    public function handle(SchedularController $schedulartask)
    {
        $schedulartask->RunEverySecond();
        sleep(10);
        $schedulartask->RunEverySecond();
        sleep(10);
        $schedulartask->RunEverySecond();
        sleep(10);
        $schedulartask->RunEverySecond();
        sleep(10);
        $schedulartask->RunEverySecond();
        sleep(10);
        $schedulartask->RunEverySecond();

        return Command::SUCCESS;
    }
}
