<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingPeriod;
use App\Models\Sale;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // الحصول على الفترة المحاسبية غير المغلقة
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        if ($accountingPeriod) {
            // الحصول على جميع المبيعات الخاصة بالفترة المحاسبية
            $totalsale = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('transaction_type', 4)
                ->get();

            // تجميع المبيعات حسب الشهر
            $monthlySales = $totalsale->groupBy(function ($sale) {
                return $sale->created_at->format('Y-m'); // تجميع المبيعات حسب السنة والشهر
            });

            // تحويل المجموعة إلى مصفوفة مع إجمالي المبيعات لكل شهر
            $monthlyTotals = $monthlySales->map(function ($sales, $month) {
                return [
                    'month' => $month,
                    'total' => $sales->sum('total_Profit'), // أو أي عمود يمثل قيمة المبيعات
                ];
            })->values();

            $currentMonth = Carbon::now()->format('Y-m');

            // الحصول على المبيعات للشهر الحالي
            $sales = Sale::where('created_at', '>=', Carbon::now()->startOfMonth())
                ->where('created_at', '<=', Carbon::now()->endOfMonth())
                ->where('transaction_type', 4)
                ->get();

            $sa = Sale::where('created_at', '>=', Carbon::now()->startOfMonth())
                ->where('created_at', '<=', Carbon::now()->endOfMonth())
                ->where('transaction_type', 5)
                ->get();

            // تجميع الأرباح حسب اليوم
            $dailyProfits = $sales->groupBy(function ($sale) {
                return $sale->created_at->format('Y-m-d'); // تجميع المبيعات حسب اليوم
            });

            // تحويل المجموعة إلى مصفوفة مع إجمالي الأرباح لكل يوم
            $dailyTotals = $dailyProfits->map(function ($sales, $day) {
                // حساب إجمالي الأرباح للمعاملات من النوع 4
                $profitType4 = $sales->where('transaction_type', 4)->sum('total_Profit');

                // حساب إجمالي الأرباح للمعاملات من النوع 5
                $profitType5 = $sales->where('transaction_type', 5)->sum('total_Profit');

                // حساب الربح الصافي
                $netProfit = $profitType4 - $profitType5;

                return [
                    'day' => $day,
                    'total_Profit' => $netProfit,
                ];
            })->values(); // لتحويل المجموعات إلى مصفوفة
        }

        return view('dashboard', [
            'dailyTotals' => $dailyTotals ?? [],
            'monthlyTotals' => $monthlyTotals ?? [],
        ]);
    }
}
