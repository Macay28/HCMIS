fetch('fetch_chart_data.php')
  .then(response => response.json())
  .then(data => {
    const labels = data.map(item => item.label);
    const values = data.map(item => item.value);

    // Render the chart with only 3 items
    var ctx = document.getElementById("myPieChart");
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: labels.slice(0, 3), // Limit to 3 labels
        datasets: [{
          data: values.slice(0, 3), // Limit to 3 values
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
          hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
          hoverBorderColor: "gray",
        }],
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "white",
          bodyFontColor: "black",
          borderColor: 'black',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
        },
        legend: {
          display: true
        },
        cutoutPercentage: 80,
      },
    });
  })
  .catch(error => console.error('Error fetching data:', error));
