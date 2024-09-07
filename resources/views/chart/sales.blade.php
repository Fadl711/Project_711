<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <link rel="shortcut icon" href="../../assets/img/favicon.ico" />
    <link
      rel="apple-touch-icon"
      sizes="76x76"
      href="../../assets/img/apple-icon.png"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"
    />
    <link
      rel="stylesheet"
      href="../../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css"
    />
    <link rel="stylesheet" href="../../assets/styles/tailwind.css" />
    <title>Dashboard | Notus JS by Creative Tim</title>
  </head>
  <body class="text-blueGray-700 antialiased">
    <noscript>You need to enable JavaScript to run this app.</noscript>
    <div
      class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded"
    >
      <div class="rounded-t mb-0 px-4 py-3 bg-transparent">
        <div class="flex flex-wrap items-center">
          <div class="relative w-full max-w-full flex-grow flex-1">
            <h6
              class="uppercase text-blueGray-400 mb-1 text-xs font-semibold"
            >
              شغل المحل
            </h6>
            <h2 class="text-blueGray-700 text-xl font-semibold">
              مجموع الدخل والخرج
            </h2>
          </div>
        </div>
      </div>
      <div class="p-4 flex-auto">
        <!-- Chart -->
        <div class="relative h-350-px">
          <canvas id="bar-chart"></canvas>
        </div>
      </div>
    </div>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"
      charset="utf-8"
    ></script>
    <script type="text/javascript">

      (function () {
        /* Chart initialisations */
        /* Bar Chart */
        var config = {
          type: "bar",
          data: {
            labels: [
              "يناير",
              "فبراير",
              "مارس",
              "ابريل",
              "مايو",
              "يونيو",
              "يوليو",
            ],
            datasets: [
              {
                label:  "الدخل",
                backgroundColor: "#ed64a6",
                borderColor: "#ed64a6",
                data: [900055, 885550, 965000, 845500,905000, 455000, 683500],
                fill: false,
                barThickness: 8,
              },
              {
                label: "الخرج",
                fill: false,
                backgroundColor: "#4c51bf",
                borderColor: "#4c51bf",
                data: [857559, 685559, 869559, 745595, 709559, 605595, 775590],
                barThickness: 8,
              },
            ],
          },
          options: {
            maintainAspectRatio: false,
            responsive: true,
            title: {
              display: false,
              text: "Orders Chart",
            },
            tooltips: {
              mode: "index",
              intersect: false,
            },
            hover: {
              mode: "nearest",
              intersect: true,
            },
            legend: {
              labels: {
                fontColor: "rgba(0,0,0,.4)",
              },
              align: "end",
              position: "bottom",
            },
            scales: {
              xAxes: [
                {
                  display: true,
                  scaleLabel: {
/*                     display: true,
                    labelString: "Month", */
                  },
                  gridLines: {
                    borderDash: [2],
                    borderDashOffset: [2],
                    color: "rgba(33, 37, 41, 0.3)",
                    zeroLineColor: "rgba(33, 37, 41, 0.3)",
                    zeroLineBorderDash: [2],
                    zeroLineBorderDashOffset: [2],
                  },
                },
              ],
              yAxes: [
                {
                  display: true,
                  scaleLabel: {
                    display: false,
                    labelString: "Value",
                  },
                  gridLines: {
                    borderDash: [2],
                    drawBorder: false,
                    borderDashOffset: [2],
                    color: "rgba(33, 37, 41, 0.2)",
                    zeroLineColor: "rgba(33, 37, 41, 0.15)",
                    zeroLineBorderDash: [2],
                    zeroLineBorderDashOffset: [2],
                  },
                },
              ],
            },
          },
        };
        ctx = document.getElementById("bar-chart").getContext("2d");
        window.myBar = new Chart(ctx, config);
      })();
    </script>
  </body>
</html>
