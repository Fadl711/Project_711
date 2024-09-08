{{--

<div class="flex justify-center">
    <canvas id="chart" width="800" height="400"></canvas>
</div>


<script>


const ctx = document.getElementById('chart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['النقد', 'الحسابات المدينة', 'المخزن', 'اجمالي الاصول الحالية', 'اجمالي الاصول الغير حالية', 'الممتلكات', 'المعدات'],
        datasets: [{
            label: 'الاصول الحالية',
            data: [1000, 2000, 3000, 6000, 0, 0, 0],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }, {
            label: 'الاصول الغير حالية',
            data: [0, 0, 0, 0, 9000, 5000, 4000],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});



</script>
 --}}

<!-- component -->
<div class="shadow-lg rounded-lg overflow-hidden">
    <div class=" px-5 py-1 bg-gray-50 flex justify-between">
            <div class="bg-gray-50 p-3 shadow-md rounded-lg text-xl">
              أجمالي الأصول : 30000
            </div>
    </div>
    <canvas class="p-10" id="chartBar1"></canvas>
  </div>

  <!-- Required chart.js -->

  <!-- Chart bar -->
  <script>
      const labelsBarChart1 = ['النقد', 'الحسابات المدينة', 'المخزن', 'اجمالي الاصول الحالية', 'اجمالي الاصول الغير حالية', 'الممتلكات', 'المعدات']
      const dataBarChart1 = {
  labels: labelsBarChart1,
  datasets: [
      {
          backgroundColor: "hsl(220, 60%, 60%)",
          borderColor: "hsl(220, 60%, 60%)",
          label: 'الاصول الحالية',
          data: [1000, 2000, 3000, 6000, 0, 0, 0],
          datalabels: {
              anchor: 'center',
              align: 'center',

            }
        },   {
            label: 'الاصول الغير حالية',
            backgroundColor: "hsl(210, 100%, 20%)",
            borderColor: "hsl(210, 100%, 20%)",
            data: [0, 0, 0, 0, 9000, 5000, 4000],
            datalabels: {
                anchor: 'center',
                align: 'center',
            }
        },
    ],
};

const configBarChart1 = {
    type: "bar",
    data: dataBarChart1,
    options: {
        scales: {
            x: {
        ticks: {
          font: {
              size: 18 // increase the font size to 18
          }
        }
    }
}
}
};

var chartBar = new Chart(
    document.getElementById("chartBar1"),
    configBarChart1
);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
