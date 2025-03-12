<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

@forelse ($dataProducts as $item)
<div class="block max-w-sm   p-1 bg-white   text-sm   rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <p class="font-bold text-gray-700 dark:text-gray-400">{{$item['productName']}}</p>
    </div>
<div class=" w-[%100] py-4 px-1  bg-white border  text-center  border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 block">
<div class=" w-[%100] py-4 px-1 gap-2    text-center   hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 flex">

<div class="block max-w-sm  h-20 text-center text-sm  p-1 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class="font-bold   text-gray-900 dark:text-white">ارباح   المبيعات</h5>
    <p class="font-bold text-gray-700 dark:text-gray-400">{{$item['salesProfit4']}}</p>
    </div>
<div class="block max-w-sm p-1 h-20 text-center text-sm bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class="  font-bold   text-gray-900 dark:text-white">ارباح مردود  المبيعات</h5>
    <p class="font-bold text-gray-700 dark:text-gray-400">{{$item['salesProfit5']}}</p>
    </div>
<div class="block max-w-sm h-20  p-1 text-center text-sm bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class=" font-bold   dark:text-white">  خصم المبيعات </h5>
    <p class="font-bold  text-red-500  dark:text-gray-400">{{$item['salesDiscount4']}}</p>
    </div>
<div class="block text-sm h-20 text-center max-w-sm p-1 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class="  font-bold  text-gray-900 dark:text-white">  خصم  مردودالمبيعات </h5>
    <p class="font-bold text-green-500 dark:text-gray-400">{{$item['salesDiscount5']}}</p>
    </div>
    @php
        $Discount=$item['salesDiscount4']-$item['salesDiscount5'];
    @endphp
    @php
        $salesProfit=$item['salesProfit4']-$item['salesProfit5'];
        $Profit=$salesProfit -$Discount;
    @endphp
    <div class="block max-w-sm p-1 h-20 text-center bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
        <h5 class=" text-sm font-bold  text-gray-900 dark:text-white"> صافي  خصم   </h5>
        <p class="font-bold text-red-500 dark:text-gray-400">{{$Discount}}</p>
    </div>
<div class="block text-center max-w-sm p-1 h-20 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class="  font-bold  text-gray-900 dark:text-white"> صافي الأرباح </h5>
    <p class="font-bold text-green-500 dark:text-gray-400">{{$Profit}}</p>
</div>
</div>
<div class=" w-[%100]  px-1 gap-2    text-center   hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 flex">


<div class="block text-center  p-1 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
   
<canvas id="paymentChart" width="400" height="350"></canvas>


<script>
    const ctx = document.getElementById('paymentChart').getContext('2d');

    // تحويل البيانات من PHP إلى JavaScript
    const dailyTotals = {!! json_encode($item['dailyTotals']) !!}; // استخدام json_encode لتحويل المصفوفة إلى JSON
    
    // إعداد الملصقات والقيم
    const labels = dailyTotals.map(item => item.month); // استخراج الأيام
    const data = dailyTotals.map(item => item.total); // استخراج الأرباح

    const paymentChart = new Chart(ctx, {
        type: 'line', // أو 'bar' حسب نوع المخطط الذي تريده
        data: {
            labels: labels, // استخدام الملصقات
            datasets: [{
                label: 'الربح الشهري',
                data: data, // استخدام القيم
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false, // لملء المنطقة تحت الخط
                
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    color: '#36A2EB', // لون النص
                    font: {
                        weight: 'bold',
                        size: 10,
                    },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: false,
                        text: 'المبلغ'
                    }
                },
                x: {
                    title: {
                        display: false,
                        text: 'الأشهر'
                    }
                }
            }
        },
        plugins: [ChartDataLabels] // إضافة البيانات كـ plugin
    });
</script>
</div>
<div class="block text-center  p-1 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
   
    <canvas id="paymentChart2" width="200" height="350"></canvas>


<script>
    const ct = document.getElementById('paymentChart2').getContext('2d');

    // تحويل البيانات من PHP إلى JavaScript
    const dailyTotal = {!! json_encode($item['dailyTotal']) !!}; // استخدام json_encode لتحويل المصفوفة إلى JSON
    
    // إعداد الملصقات والقيم
    const labela = dailyTotal.map(item => item.months); // استخراج الأيام
    const datas = dailyTotal.map(item => item.totals); // استخراج الأرباح

    const paymentChart2 = new Chart(ct, {
        type: 'line', // أو 'bar' حسب نوع المخطط الذي تريده
        data: {
            labels: labela, // استخدام الملصقات
            datasets: [{
                label: 'الربح السنوي',
                data: datas, // استخدام القيم
                backgroundColor: 'rgba(45, 100, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 2)',
                borderWidth: 2,
                fill: false, // لملء المنطقة تحت الخط
                
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    color: '#36A2EB', // لون النص
                    font: {
                        weight: 'bold',
                        size: 10,
                    },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: false,
                        text: 'المبلغ'
                    }
                },
                x: {
                    title: {
                        display: false,
                        text: 'الأشهر'
                    }
                }
            }
        },
        plugins: [ChartDataLabels] // إضافة البيانات كـ plugin
    });
</script>
</div>
</div>
    </div>

    @empty
    
@endforelse