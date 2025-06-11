<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
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

    public function handle()
    {
        Log::info('🎯 [PRODUCT-SERVICE] ReduceProductStock job started', [
            'product_id' => $this->productId,
            'quantity' => $this->quantity
        ]);

        try {
            $product = Product::find($this->productId);
            
            if (!$product) {
                Log::error('❌ Product not found', ['product_id' => $this->productId]);
                return;
            }

            Log::info('📦 Product found', [
                'id' => $product->id,
                'name' => $product->name,
                'current_stock' => $product->stock
            ]);

            if ($product->stock < $this->quantity) {
                Log::warning('⚠️ Insufficient stock', [
                    'available' => $product->stock,
                    'requested' => $this->quantity
                ]);
                return;
            }

            $oldStock = $product->stock;
            $product->stock = $product->stock - $this->quantity;
            $product->save();

            Log::info('✅ Stock reduced successfully!', [
                'product_id' => $this->productId,
                'old_stock' => $oldStock,
                'new_stock' => $product->stock,
                'reduced_by' => $this->quantity
            ]);

        } catch (\Exception $e) {
            Log::error('💥 Job failed', [
                'product_id' => $this->productId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
