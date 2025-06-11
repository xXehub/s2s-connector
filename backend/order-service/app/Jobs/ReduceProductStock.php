<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReduceProductStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $productId;
    public $quantity;

    public function __construct($productId, $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function handle()
    {
        // This job will be processed by product service
    }
}
