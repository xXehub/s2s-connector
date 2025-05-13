<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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

    public function handle(): void
    {
        try {
            $url = "http://product-service:9000/api/products/{$this->productId}/reduce-stock";

            $response = Http::timeout(5)->patch($url, [
                'quantity' => $this->quantity
            ]);

            if ($response->failed()) {
                Log::error("Gagal mengurangi stok produk. URL: $url", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            } else {
                Log::info("Stok produk {$this->productId} dikurangi sebanyak {$this->quantity}.");
            }
        } catch (\Exception $e) {
            Log::error("Exception saat mengurangi stok produk: " . $e->getMessage());
        }
    }
}
