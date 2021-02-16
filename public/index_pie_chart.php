<?php

require_once 'database.php';

$sql = "SELECT ec.name AS name, SUM(e.amount) AS sum
FROM expenses AS e, expenses_category_assigned_to_users AS ec
WHERE e.user_id=1 AND ec.user_id=1
AND e.user_id=ec.user_id 
AND ec.id=e.expense_category_assigned_to_user_id
AND e.date_of_expense BETWEEN '2021-02-01' AND '2021-02-28'
GROUP BY expense_category_assigned_to_user_id
ORDER BY SUM(e.amount) DESC";

$stmt=$db->prepare($sql);
$stmt->execute();
$result=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

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
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>