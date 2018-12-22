<?php

	$query = "SELECT p.projectID, p.projectName, p.projectType, p.projectTargetComp, c.clientName, c.clientAbbr FROM projects AS p LEFT JOIN clients AS c ON (p.clientID = c.clientID) WHERE p.projectDisp=1 AND p.projectStatus=1 AND p.projectCurrent=1 ORDER BY p.priority ASC, p.projectTargetComp, c.clientName ASC";
	$result = $con->query($query);
	
	$projectsCurrent = '<div class="panel panel-default">
                        <div class="panel-heading dash-panel clearfix">
                            <h2 class="pull-left"><i class="fa fa-list"></i> Current Projects</h2>
							
							<div class="pull-right">
                                <div class="btn-group">
									<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
										<i class="fa fa-chevron-down"></i>
									</button>
                                    <ul class="dropdown-menu pull-right slidedown" role="menu">
										'. $Projects .'
                                        <li class="divider"></li>
                                        <li><a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="project" data-adminact="new" data-editid="" data-page="dash" data-section="projects-current"><i class="fa fa-plus-square"></i> New Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">';

	if ($result) {
		$projectsCurrent .= '<ul class="list-group checked-list-box" id="projects-current" data-section="projects-current">';
		
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$projectType = $row['projectType'];
			$projectID = $row['projectID'];
			$projectName = $row['projectName'];
			$client = $row['clientName'];
			if (!empty($row['clientAbbr'])) 
				$client .= ' ('. $row['clientAbbr'] . ')';
			$projDue = '--/--/----'; 
			if ( (!empty($row['projectTargetComp'])) || ($row['projectTargetComp'] != '0000-00-00') )	
				$projDue = Date::convert($row['projectTargetComp'], 'Y-m-d', 'n/d/Y');
			include('inc/list-item/project.php');
		}
		$projectsCurrent .= '</ul>'; 
	} else {
		$projectsCurrent = '<p class="text-center">No Current Active Projects Found</p>'; 	
	}
	
	$projectsCurrent .= '</div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->';
?>