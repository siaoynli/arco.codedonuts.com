<?php

namespace App\Jobs;

use App\Jobs\Traits\QueueLogs;
use App\Models\Api\V1\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncOneProductToES implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use QueueLogs;

    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $data = $this->product->toESArray();
        try {
            app('es')->index([
                'index' => 'products',
                'id' => $data['id'],
                'body' => $data,
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function failed(Exception $exception): void
    {
        $this->createQueueLogs('error:同步产品' . $this->product->name . '数据到ES出错', $exception->getMessage(), 0);
    }
}
