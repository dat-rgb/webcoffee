<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CheckExpiredOrders;

class CheckExpiredOrdersCommand extends Command
{
    protected $signature = 'orders:check-expired';
    protected $description = 'Kiểm tra và huỷ các đơn hàng hết hạn thanh toán';

    public function handle()
    {
        CheckExpiredOrders::dispatch();
        $this->info('Đã dispatch job kiểm tra đơn hết hạn.');
    }
}