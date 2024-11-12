<?php

namespace App\Console\Commands;

use App\Models\BigSale;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateBigSaleStatus extends Command
{
    public function __construct()
    {
        parent::__construct();
    }
    
    protected $signature = 'app:update-big-sale-status';
    protected $description = 'Command description';
    public function handle()
    {
        // Get the current time
        $now = Carbon::now();
        $this->info("Current time: $now");


        // Find all active Big Sales where end_time has passed
        $expiredSales = BigSale::where('status', true)
                                ->where('end_time', '<', $now)
                                ->update(['status' => false]);

        if ($expiredSales) {
            $this->info("Updated $expiredSales Big Sale(s) to inactive.");
        } else {
            $this->info("No Big Sales need updating.");
        }
    }
}
