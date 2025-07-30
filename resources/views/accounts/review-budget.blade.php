@extends('layout')

@section('conm')
<x-navbar_accounts/>

<style>
  
  
   
    
    .debit-amount {
        color: #28a745;
    }
    
    .credit-amount {
        color: #dc3545;
    }
   
    
    @media print {
        body {
            font-size: 12pt;
        }
        
        .no-print {
            display: none;
        }
        
      
    }
      .print-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.5rem;
        border-bottom: 1px solid #ddd;
    }
  .english {
      font-family: 'Times New Roman', serif; /* الخط الإنجليزي */
  }
      /* تخصيص للطباعة */
      @media print {
          body {
              width: 100%;
              margin: 0;
              padding: 0;
          }
          .print-container {
              @apply w-full max-w-full mx-auto p-2;
          }
            .print-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 0.5rem;
        }

        
      }

  table {
      table-layout: ; /* استخدم تخطيط ثابت */
      width: 100%;
  }

  th, td {
      border: 1px solid #000;
      /* padding: 8px; */
  }

 

  /* تحسين مظهر الجدول */
  .header-section, .totals-section {
      margin-top: 10px;
      border: 2px solid #000;
      border-radius: 8px;
  }
      
</style>
<div class=" justify-between items-center  print-container">
                    <div class="   grid grid-cols-3 gap-2 p-2 ">

            <div>
                <p>الفترة المحاسبية:
                من: {{ $startDate }} إلى: {{ $endDate }}</p>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-center mb-1">الميزانية العمومية</h1>
            </div>
                <div>
            <button onclick="window.print()" class="no-print bg-blue-500 text-white px-4 py-1 rounded">
                طباعة التقرير
            </button>
        </div>
        </div>
        </div>
   <div class= "  print-container  bg-white shadow-md sm:rounded-lg w-full px-1 py-2 max-h-full flex ">
    
        
                <div class="   grid grid-cols-2 gap-2  ">

          <div class="overflow-y-auto   print:overflow-y-hidden   ">
        <!-- جدول الأصول -->
        @include('accounts.partials.assets-table', [
            'title' => 'الأصول',
            'balances' => $assets,
            'showYER' => true,
            'showSAR' => true,
            'showUSD' => true
        ])
            </div>

          <div class=" overflow-y-auto  print:overflow-y-hidden ">
        <!-- جدول الخصوم وحقوق الملكية -->
        @include('accounts.partials.liabilities-table', [
            'title' => 'الخصوم وحقوق الملكية',
            'balances' => $liabilities,
            'showYER' => true,
            'showSAR' => true,
            'showUSD' => true
        ])
    </div>
    </div>
</div>
   
   

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    document.querySelectorAll('.money-format').forEach(el => {
        const amount = parseFloat(el.dataset.amount);
        if (!isNaN(amount)) {
            el.textContent = formatter.format(amount);
        }
    });
});
</script>
@endsection