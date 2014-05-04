<?php

function fTranslationHandler($translationValue){

	return __($translationValue, 'Statistics');
}

	$date_format = 'Y-m-d';

	$min30days = mktime(0, 0, 0, date("m")  , date("d")-30, date("Y"));
	
	$lvEndDate = date('Y') . '-' . date('m') . '-' . date('d');
	$lvStartDate = date('Y'."-".'m'."-".'d',$min30days);

	if(isset($_GET["fromDate"])) 
		{
			$ArrStartDate = explode('-', $_GET['fromDate']);
			if (checkdate($ArrStartDate[1],$ArrStartDate[2],$ArrStartDate[0])){
				$lvStartDate = $ArrStartDate[0] ."-". $ArrStartDate[1] ."-". $ArrStartDate[2];
			}
		}


	if(isset($_GET["toDate"])) 
		{
			$ArrEndDate = explode('-', $_GET['toDate']);
			if (checkdate($ArrEndDate[1],$ArrEndDate[2],$ArrEndDate[0])){
				$lvEndDate =  $ArrEndDate[0] ."-". $ArrEndDate[1] ."-". $ArrEndDate[2];;
			}
		}
	
	
	$sql = "SELECT * FROM ".ipTable('statistics')." WHERE date >= '". $lvStartDate ."' AND date <= '". $lvEndDate ."' ORDER BY date DESC";
	$servers_result = ipDb()->fetchAll($sql,[]);
		
	$lvTotalVisits = 0;
	$lvTotalPageViews = 0;
	$lvJSMapPageViewData="";
	$lvHTMLTable="";
	$lvJSMapVisitorData="";
	foreach ($servers_result as $row){
		$lvVisits =  $row['visitors'];
		$lvPageviews = $row['pages'];
		$lvDate = $row['date'];
		$lvDateArr  = explode("-", $lvDate);
		$lvDateYear = $lvDateArr[0];
		$lvDateMonth = $lvDateArr[1];
		$lvDateDay = $lvDateArr[2];
		$lvHTMLTable = $lvHTMLTable . "<tr><td>" . $lvDateDay . ".". $lvDateMonth .".". $lvDateYear ."</td><td>" . $lvVisits . "</td><td>" . $lvPageviews . "</td></tr>";
		--$lvDateMonth; //für JS Datum -1
		$lvJSMapPageViewData .= "\n [new Date(" . $lvDateYear . ", " . intval($lvDateMonth) . " ," . $lvDateDay . "), " . $lvPageviews . "],"; 
		$lvJSMapVisitorData .= "\n [new Date(" . $lvDateYear . ", " . intval($lvDateMonth) . " ," . $lvDateDay . "), " . $lvVisits . "],";
		$lvTotalPageViews = $lvTotalPageViews + $lvPageviews;
		$lvTotalVisits = $lvTotalVisits + $lvVisits;
	}
	$lvJSMapPageViewData =  substr($lvJSMapPageViewData, 0, -1); 
	$lvJSMapVisitorData =  substr($lvJSMapVisitorData, 0, -1);		

?>

		
		<h1 class="ipaHeadline"><?php _e('Statistics', 'Statistics') ?></h1>

	<style type="text/css">
		/* #1.2 Tabellen Format#*/
		table{
			margin: 0px 0px 12px 0px;
			width:100%;
			border-width:0px;
		}

		td,th{
			vertical-align:top;
			margin: 0px 0px 0px 0px;
			padding: 3px 6px 3px 0px;
		}

		th{
			background-color:#999999;
			text-align:left;
			font-size:12px;
		}

		.Table_Middle{
			margin-bottom:0px;
		}

		.Table_Middle td{
			vertical-align:middle;
		}

		.Farbige_Tabelle{
			background-color: #f6f6f6;
		}

		.Farbige_Tabelle td, th{
			padding: 3px 6px 3px 6px;
			border-right:1px solid #fff;
			border-bottom:1px solid #fff;
		}


		.Farbige_Tabelle tr{
			background-color: #f6f6f6;
		}

		table.Farbige_Tabelle tr.row_dark{
			background-color: #eeeeee;
		}


		/* #1.2 Function Classes# */
		.top{
			padding-top:0px;
			margin-top:0px;
		}
		.small{
			font-size:11px;
		}
		.print_only{
			display:none;
		}


		/* #1.2 Special Formating# */
		.ok_box,.error_box,.note_box{
			padding:5px 5px 5px 5px; 
			margin:10px 0 20px 0;
			border: 1px solid;
			letter-spacing:1px;
		}

		.note_box,.note{
			color:#486FBD;
			font-weight:bold;
		}
		.note_box{
			background-color:#D9EBF5;
			border-color:#6686c8;
		}

		.ok,.ok_box{
			color:#407837;
			font-weight:bold;
		}
		.ok_box{
			background-color:#F2F2F2;
			border-color: #529746;
		}

		.error_box,.error{
			color:#d70505;
			font-weight:bold;
		}
		.error_box{
			background-color:#FCFCFC;
			border-color: #d70505;
		}

		/* #6 Formular# */

		input,textarea,select{
			font-family: Arial, sans-serif;
			padding:3px;
			border:1px solid ccc;
			width:150px;
		}

		.inputradio{
			width:auto;
		}

		.resetbutton,.formtitle{
			display:none
		}

		.inputerror input,.inputerror textarea{
			border:1px solid #ccc;
			border-bottom:3px solid #d70505;
		}

		.submitbutton{
			width:150px;
			font-weight:bold;
		}

		.submitbutton:hover{
			cursor:pointer;
		}

		#row_178{
			display:none;
		}


		/* #7 Custom Style# */

		#content{
				padding:15px;
		}
		#visit_overview{
			width: 300px; 
			height: 400px; 
			overflow-y: scroll;
		}

		#need_help{
			width:200px;
			float:right;
			border:1px solid grey;
			padding:10px;
			position:relative;
			top:35px;
			background-color:#EAF2FF;
		}

		@media print {
			#visit_overview{
				width: 300px; 
				height: auto; 
				overflow-y: show;
			}

		}
		#ui-datepicker-div{
		z-index:20000;
		}
	</style>



	<script type='text/javascript' src='http://www.google.com/jsapi'></script>
	<script type='text/javascript'>
	  google.load('visualization', '1', {'packages':['annotatedtimeline']});
	  google.setOnLoadCallback(drawChart);
	  function drawChart() {
		var data = new google.visualization.DataTable();
		data.addColumn('date', '<?php _e('date', 'Statistics') ?>');
		data.addColumn('number', '<?php echo fTranslationHandler('visitors') ?>');
		data.addRows([
		<?php
			echo $lvJSMapVisitorData;
		?> 
		]);

		var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div'));
		chart.draw(data, {displayAnnotations: true,wmode:'opaque'});
	  }
	  
	  google.setOnLoadCallback(drawChart2);
	  function drawChart2() {
		var data = new google.visualization.DataTable();
		data.addColumn('date', '<?php echo fTranslationHandler('date') ?>');
		data.addColumn('number', '<?php echo fTranslationHandler('pageviews') ?>');
		data.addRows([
		<?php
			echo $lvJSMapPageViewData;
		?> 
		]);

		var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart2_div'));
		chart.draw(data, {displayAnnotations: true});
	  }
	  
	</script>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("table.Farbige_Tabelle tr:even").addClass("row_dark");		
			jQuery("#fromDate,#toDate").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true,maxDate: "+0D"  });
			});
	</script>

				<fieldset style="border:1px solid grey;padding:10px;">
					<legend><b><?php echo fTranslationHandler('filter') ?></b></legend>
					<table cellpadding="0" cellspacing="0" border="0" style="width:500px" class="Table_Middle">
						<tr>
						<td><?php echo fTranslationHandler('from') ?></td>
						<td><input type="text"  name="fromDate" id="fromDate" value="<?php echo $lvStartDate; ?>"></td>
						<td><?php echo fTranslationHandler('to') ?></td>
						<td><input type="text" name="toDate" id="toDate" value="<?php echo $lvEndDate; ?>"></td>
						<td><input class="submitbutton" type="button" value="<?php echo fTranslationHandler('show_statistic') ?>" onClick='location.href="?aa=Statistics&toDate=" + jQuery("#toDate").val() +"&fromDate="+jQuery("#fromDate").val()' /></td>
						</tr>
					</table>
				</fieldset>

				<br /><h2><?php echo fTranslationHandler('visitors') ?></h2>
				<p><?php echo fTranslationHandler('total') . " " . fTranslationHandler('visitors') ?>: <?php echo $lvTotalVisits ?><br /> <?php echo fTranslationHandler('average') ?>: <?php 
				if ($lvTotalVisits > 0){
					echo Round($lvTotalPageViews/$lvTotalVisits,2);
				}
				else{
					echo "0";
				}
				
				?></p>
				<div id='chart_div' style='width: 100%; height: 240px;'></div>
				<br /><br /><h2><?php echo fTranslationHandler('pageviews') ?></h2>
				<p><?php echo fTranslationHandler('total') . " " . fTranslationHandler('pageviews') ?>: <?php echo $lvTotalPageViews ?></p>
				
				<div id='chart2_div' style='width:100%; height: 240px;'></div>
			
				<br /><br /><h2><?php echo fTranslationHandler('titleoverviewtable') ?></h2>		

				<div id="visit_overview">
					<table cellpadding="0" cellspacing="0" border="0" class="Farbige_Tabelle">
					<tr>
						<th><?php echo fTranslationHandler('date') ?></th>
						<th><?php echo fTranslationHandler('visitors') ?></th>
						<th><?php echo fTranslationHandler('pageviews') ?></th>
					</tr>
					<?php
						echo $lvHTMLTable
					?>
					</table>
				</div>

