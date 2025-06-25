<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckExpiringProducts implements ShouldQueue
{
    use Queueable;
    protected $signature = 'products:check-expiry';
    protected $description = 'التحقق من المنتجات المنتهية الصلاحية قريبًا';
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
        $products = Product::expiringSoon()->get();

        if ($products->count() > 0) {
            $this->info("⚠️ هناك {$products->count()} منتج مقارب للانتهاء!");
            foreach ($products as $product) {
                $this->line("اسم المنتج: {$product->name} - ينتهي في: {$product->expiry_date}");
            }
        } else {
            $this->info("✅ لا توجد منتجات مقاربة للانتهاء");
        }
    }
}
