<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\ReduceProductStock;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderData;

    public function __construct(array $orderData)
    {
        $this->orderData = $orderData;
    }

    public function handle(): void
    {
        try {
            // GUNAKAN NAMA SERVICE DOCKER, BUKAN localhost
            $userServiceUrl = "http://user-service:9000/api/users/{$this->orderData['user_id']}";
            $productServiceUrl = "http://product-service:9000/api/products/{$this->orderData['product_id']}";

            $userResponse = Http::timeout(5)->get($userServiceUrl);
            if ($userResponse->failed()) {
                Log::error("User not found. URL: $userServiceUrl");
                return;
            }

            $productResponse = Http::timeout(5)->get($productServiceUrl);
            if ($productResponse->failed()) {
                Log::error("Product not found. URL: $productServiceUrl");
                return;
            }

            $product = $productResponse->json('data');

            if ($product['stock'] < $this->orderData['quantity']) {
                Log::warning("Stok tidak cukup: tersedia {$product['stock']}, diminta {$this->orderData['quantity']}");
                return;
            }

            $order = Order::create([
                'user_id'       => $this->orderData['user_id'],
                'product_id'    => $this->orderData['product_id'],
                'product_name'  => $product['name'],
                'product_price' => $product['price'],
                'quantity'      => $this->orderData['quantity'],
                'total_price'   => $product['price'] * $this->orderData['quantity'],
            ]);

            Log::info("Order berhasil dibuat. ID: {$order->id}");

            ReduceProductStock::dispatch(
                $this->orderData['product_id'],
                $this->orderData['quantity']
            );
        } catch (\Exception $e) {
            Log::error('Order processing failed: ' . $e->getMessage());
        }
    }
}
