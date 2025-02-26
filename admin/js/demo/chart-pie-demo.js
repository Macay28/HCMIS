// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Fever", "Cold", "Cough"],
    datasets: [{
      data: [],
      backgroundColor: ['#FF0000', '#008000', '#0000FF'], // Colors for the 3 slices
      hoverBackgroundColor: ['#ff5353', '#00FF00', '#00FFFF'],
    
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "black",
      bodyFontColor: "black",
      borderColor: 'black',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: true // Display legend for the three pieces of info
    },
    cutoutPercentage: 80, // Adjust for doughnut thickness
  },
});


