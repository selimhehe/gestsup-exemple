<?php
/*
Author: Flox
File: stat_line.php
Description: display line graphic
Version: 1.1
Creation date: 06/10/2012
Last update: 05/11/2012
*/
?>

<script type="text/javascript" src="./js/jquery.js"></script>
<script src="components/Highcharts-2.3.3/js/highcharts.js"></script>
<script src="components/Highcharts-2.3.3/js/modules/exporting.js"></script>
<script type="text/javascript">
	$(function () {
		var chart1;
		$(document).ready(function() {
			chart1 = new Highcharts.Chart({
				chart: {
					renderTo: 'container1',
					type: 'line',
					marginRight: 130,
					marginBottom: 25,
					backgroundColor:'transparent'
				},
				title: {
					text: '<?php echo $libchart; ?>',
					x: -20 //center
				},
				subtitle: {
					text: "<?php echo "Total de la periode: $count"; ?>",
					x: -20
				},
				xAxis: {
					categories: [
					<?php
					for($i=0;$i<sizeof($xnom);$i++) 
					{ 
						$k=sizeof($values);
						$k=$k-1;
						if ($i==$k) echo "\"$xnom[$i]\""; else echo "\"$xnom[$i]\"".','; 
					} 
					?>
					]
				},
				yAxis: {
					title: {
						text: '<?php echo $liby; ?>'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					formatter: function() {
							return '<b>'+ this.series.name +'</b><br/>'+
							this.x +': '+ this.y +' Tickets';
					}
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'top',
					x: -10,
					y: 100,
					borderWidth: 0
				},
				series: [{
					name: 'Tickets ouvert',
					data: [
					<?php
					for($i=0;$i<sizeof($values);$i++) 
					{ 
						$k=sizeof($values);
						$k=$k-1;
						if($i==$k) echo $values[$i]; else echo "$values[$i],"; 
					} 
					?>
					]
				}]
			});
		});
	});
</script>
					
<div id="container1" style="min-width: 400px; height: 400px; margin: 0 auto"></div>