<!-- component -->
<div class="shadow-lg rounded-lg overflow-hidden">
    <div class="py-3 px-5 bg-gray-50">Bar chart</div>
    <canvas class="p-10" id="chartBar"></canvas>
  </div>

  <!-- Required chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Chart bar -->
  <script>
const labelsBarChart = [
  "المبيعات",
  "المشتريات",
  "المردوادت",
  "ديون العملاء",
  "ديون المحل",
  "المردودات",
];
const dataBarChart = {
  labels: labelsBarChart,
  datasets: [
    {
      label: "My First dataset",
      backgroundColor: "hsl(252, 82.9%, 67.8%)",
      borderColor: "hsl(252, 82.9%, 67.8%)",
      data: [0, 10, 5, 2, 20, 30, 45],
      datalabels: {
        anchor: 'center',
        align: 'center',
        formatter: function(value) {
          return value + '%'; // append the percentage sign to each value
        }
      }
    },
  ],
};

const configBarChart = {
  type: "bar",
  data: dataBarChart,
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
  document.getElementById("chartBar"),
  configBarChart
);
  </script>
