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

<script type="text/javascript">
	$(function () {
    var chart;
    $(document).ready(function() {
    	
    	// Radialize the colors
		// Highcharts.getOptions().colors = $.map(Highcharts.getOptions().colors, function(color) {
		    // return {
		        // radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
		        // stops: [
		            // [0, color],
		            // [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		        // ]
		    // };
		// });
		
		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: '<?php echo $container; ?>',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
				backgroundColor:'transparent'
				
            },
			exporting: {
                         url: 'http://export.highcharts.com/index-utf8-encode.php'
                      },
            title: {
                text: '<?php echo $libchart; ?>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
				percentageDecimals: 1
            	
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#ccc',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Répartition',
                data: [
                   <?php
					for($i=0;$i<sizeof($values);$i++) 
					{ 
						$k=sizeof($values);
						$k=$k-1;
						if ($i==$k) echo "['$xnom[$i]', $values[$i]]"; else echo "['$xnom[$i]', $values[$i]],";
					} 
					?>
                ]
            }]
        });
    });
    
});
</script>
