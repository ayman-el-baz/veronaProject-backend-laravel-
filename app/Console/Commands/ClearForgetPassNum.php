<?php

namespace App\Console\Commands;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearForgetPassNum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:forget-pass-num';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the forgetPassNum field in the clients table after 1 minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeLimit = Carbon::now()->subMinutes(1);
        
        Client::where('forgetPassNum', '!=', null)
            ->where('created_at', '<', $timeLimit)
            ->update(['forgetPassNum' => null]);
        
        $this->info('ForgetPassNum cleared successfully.');
    }
}
