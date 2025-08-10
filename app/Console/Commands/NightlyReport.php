<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderPerHour;
use App\Mail\DailyReport;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NightlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:nightly-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'nightly report of orders and revenue to the admin.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();
        $orders = Order::whereDate('created_at', $today)->get();
        $totalRevenue = $orders->sum('total_price');
        $adminEmail = 'mah06.hajj@gmail.com';


        Mail::to($adminEmail)->send(new DailyReport($orders, $totalRevenue, $today));

    }
}
