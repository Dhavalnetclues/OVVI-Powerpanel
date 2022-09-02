<div class="bg_organization {{ $orgclass }}">
	<section>
		<div class="inner_pages cms management_team_section">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<div id="chart_div" class="chart_organization"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	var orgnizationsData = jQuery.parseJSON('{!!$orgdata!!}');
</script>
<!-- code for draw chart for Organization -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
	google.charts.load('current', {packages:["orgchart"]});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Name');
		data.addColumn('string', 'Manager');
		data.addColumn('string', 'ToolTip');
		// For each orgchart box, provide the name, manager, and tooltip to show.
		data.addRows(orgnizationsData);
		// Create the chart.
		var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
		// Draw the chart, setting the allowHtml option to true for the tooltips.
		chart.draw(data, {allowHtml:true});
	}
</script>    