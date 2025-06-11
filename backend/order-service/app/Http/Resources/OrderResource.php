<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public $status;
    public $message;
    public $resource;

    public function __construct($resource, $status = 'success', $message = 'Data retrieved successfully')
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
        $this->resource = $resource;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->when($this->resource !== null, function () {
                // Handle collection vs single resource
                if (is_array($this->resource) || $this->resource instanceof \Illuminate\Support\Collection) {
                    return $this->resource->map(function ($order) {
                        return [
                            'id' => $order->id ?? null,
                            'user_id' => $order->user_id ?? null,
                            'product_id' => $order->product_id ?? null,
                            'product_name' => $order->product_name ?? null,
                            'product_price' => $order->product_price ?? null,
                            'quantity' => $order->quantity ?? null,
                            'total_price' => $order->total_price ?? null,
                            'status' => $order->status ?? 'pending',
                            'created_at' => $order->created_at ?? null,
                            'updated_at' => $order->updated_at ?? null,
                        ];
                    });
                } else {
                    // Single resource
                    return [
                        'id' => $this->resource->id ?? null,
                        'user_id' => $this->resource->user_id ?? null,
                        'product_id' => $this->resource->product_id ?? null,
                        'product_name' => $this->resource->product_name ?? null,
                        'product_price' => $this->resource->product_price ?? null,
                        'quantity' => $this->resource->quantity ?? null,
                        'total_price' => $this->resource->total_price ?? null,
                        'status' => $this->resource->status ?? 'pending',
                        'created_at' => $this->resource->created_at ?? null,
                        'updated_at' => $this->resource->updated_at ?? null,
                    ];
                }
            })
        ];
    }
}
