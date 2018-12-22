<?php

	// Get Active Tasks
	$query = "SELECT *, p.clientID FROM tasks t LEFT JOIN projects p ON (t.projectID = p.projectID) LEFT JOIN taskStatus ts ON (t.taskStatus = ts.taskStatID) WHERE t.display=1 AND ts.quickDisplay=1 ORDER BY t.dueDate ASC, t.taskID ASC";
	$result = $con->query($query);
	$num_rows = $result->num_rows;
	
	$taskList = '<div class="panel panel-default">
						<div class="panel-heading dash-panel clearfix">
                            <h2 class="pull-left"><i class="fa fa-check-square-o"></i> Task List</h2>
							
							<div class="pull-right">
                                <div class="btn-group">
									<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
										<i class="fa fa-chevron-down"></i>
									</button>
                                    <ul class="dropdown-menu pull-right slidedown" role="menu">
										<li><a href="?op=admin&amp;type=task" class="active">Task Admin</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="task" data-adminact="new" data-page="dash" data-section="tasks-current" data-editid=""><i class="fa fa-plus-square"></i> New Task</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">';
	
	if ($num_rows > 0) {
		$taskList .= '<div class="list-group clearfix" id="tasks-current">'; 
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			include('inc/list-item/task.php');
			$taskList .= $listItem;
		}
		$taskList .= '</div>';
	}
	else {
		$taskList .= '<p class="text-center">No Current Tasks</p>';
	}
	$taskList .= '</div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->';
	
?>