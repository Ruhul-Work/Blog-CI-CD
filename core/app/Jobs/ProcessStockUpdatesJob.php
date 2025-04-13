<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessStockUpdatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $batchSize = 1000; // Number of products to process at a time

        do {
            // Fetch a batch of pending updates
            $pendingUpdates = DB::table('pending_stock_updates')
                ->select('product_id')
                ->limit($batchSize)
                ->get();

            if ($pendingUpdates->isEmpty()) {
                break;
            }

            // Extract product IDs
            $productIds = $pendingUpdates->pluck('product_id');

            // Recalculate stock for each product
            $stockData = DB::table('stocks')
                ->select(
                    'product_id',
                    DB::raw("SUM(CASE WHEN stock_type = 'purchase' THEN item_qty ELSE 0 END) AS total_purchase"),
                    DB::raw("SUM(CASE WHEN stock_type = 'return' THEN item_qty ELSE 0 END) AS total_return"),
                    DB::raw("SUM(CASE WHEN stock_type = 'sale' THEN item_qty ELSE 0 END) AS total_order")
                )
                ->whereIn('product_id', $productIds)
                ->groupBy('product_id')
                ->get();

            foreach ($stockData as $stock) {
                $calculatedStock = ($stock->total_purchase ?? 0) + ($stock->total_return ?? 0) - ($stock->total_order ?? 0);

                // Update the product stock
                DB::table('products')->where('id', $stock->product_id)->update(['stock' => $calculatedStock]);
            }

            // Remove processed entries
            DB::table('pending_stock_updates')->whereIn('product_id', $productIds)->delete();

        } while (true);
    }
}
