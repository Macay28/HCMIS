// Updated Chart.js Bar Chart Example
const ctx = document.getElementById("myBarChart").getContext('2d');

// Bar chart data
const data = {
  labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
  datasets: [{
    label: "Cases",
    backgroundColor: "#4e73df",
    hoverBackgroundColor: "#2e59d9",
    borderColor: "#4e73df",
    data: [100, 2000, 6251, 7841, 9821, 4444, 3000, 4200, 5100, 6100, 7200, 8300], // Adjusted data array to match labels
  }],
};

// Bar chart options
const options = {
  maintainAspectRatio: false,
  layout: {
    padding: {
      left: 10,
      right: 25,
      top: 25,
      bottom: 0
    }
  },
  scales: {
    x: { // Updated x-axis configuration
      grid: {
        display: false,
        drawBorder: false
      },
      ticks: {
        maxTicksLimit: 6
      },
    },
    y: { // Updated y-axis configuration
      ticks: {
        beginAtZero: true,
        maxTicksLimit: 5,
        padding: 10,
        callback: function(value) {
          return number_format(value); // Use number_format for formatting
        }
      },
      grid: {
        color: "rgb(234, 236, 244)",
        zeroLineColor: "rgb(234, 236, 244)",
        borderDash: [2],
        zeroLineBorderDash: [2]
      }
    },
  },
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem) {
          return tooltipItem.dataset.label + ': ' + number_format(tooltipItem.raw);
        }
      }
    }
  }
};

// Initialize Chart.js Bar Chart
new Chart(ctx, {
  type: 'bar',
  data: data,
  options: options
});

// Number formatting function
function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
  number = (number + '').replace(',', '').replace(' ', '');
  let n = !isFinite(+number) ? 0 : +number;
  let prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
  let sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
  let dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
  let s = '';
  const toFixedFix = (n, prec) => {
    const k = Math.pow(10, prec);
    return '' + Math.round(n * k) / k;
  };
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}
