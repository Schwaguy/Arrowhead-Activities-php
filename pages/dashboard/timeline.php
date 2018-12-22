<?php
	
include('ajax/timeline.php');
$timeline = '<div id="timeline" class="panel panel-default">
	<div class="panel-heading clearfix">
       	<h2 class="pull-left"><i class="fa fa-clock-o fa-fw"></i><span class="title-dynamic">'. $panelTitle .' <span class="totalHours">'. $totalHours .'</span></span></h2>
			<div class="pull-right">
              	<div class="btn-group">
					<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"><i class="fa fa-chevron-down"></i></button>
                   	<ul class="dropdown-menu pull-right slidedown" role="menu">
                       	<li><a href="#" class="timechange" data-admintype="update" data-filter="today" data-page="dash" data-section="timeline">Today\'s Hours</a></li>
						<li><a href="#" class="timechange" data-admintype="update" data-filter="yesterday" data-page="dash" data-section="timeline">Yesterday\'s Hours</a></li>
						<li><a href="#" class="timechange" data-admintype="update" data-filter="this-week" data-page="dash" data-section="timeline">This Week\'s Hours</a></li>
						<li><a href="#" class="timechange" data-admintype="update" data-filter="last-week" data-page="dash" data-section="timeline">Last Week\'s Hours</a></li>
						<li><a href="#" class="timechange" data-admintype="update" data-filter="this-month" data-page="dash" data-section="timeline">This Months\'s Hours</a></li>
						<li><a href="#" class="timechange" data-admintype="update" data-filter="last-month" data-page="dash" data-section="timeline">Last Months\'s Hours</a></li>
                   	</ul>
               	</div>
          	</div>
       	</div>
       	<!-- /.panel-heading -->
      	<div class="panel-body">
          	<ul class="timeline content-dynamic">
				'. $timelineItems .'
			</ul>
        </div>
        <!-- /.panel-body -->
	</div>
   	<!-- /.panel -->';

// See Dashboard Page (pages/dashboard.php) for Content Change Scripts

?>