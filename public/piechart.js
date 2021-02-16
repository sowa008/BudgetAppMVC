      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Category', 'Amount'],
		  <?php foreach($result as $key=>$val)		{  ?>
		  ['<?php echo $val['name']?>', <?php echo $val['sum']?>],
		  <?php } ?>
		  ]);
        var options = {
          title: 'Your expenses'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
