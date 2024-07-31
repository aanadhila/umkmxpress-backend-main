<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetOTPDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:otp-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset request otp daily';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::query()->where('otp_daily', '>', 0)->update([
            'otp_daily' => 0,
        ]);
        $this->info('OTP harian user direset');
        return Command::SUCCESS;
    }
}
