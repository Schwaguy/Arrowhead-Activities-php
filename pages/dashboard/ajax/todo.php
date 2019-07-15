<?php
	
	// Get Active To-Dos
	$query = "SELECT *, todo.clientID AS todoClientID FROM todo_list todo LEFT JOIN clients c ON (todo.clientID = c.clientID) LEFT JOIN projects p ON (todo.projectID = p.projectID) WHERE todoDone=0 ORDER BY todo.dueDate ASC, todo.todoID ASC";
	$result = $con->query($query);
	$num_rows = $result->num_rows;
	
	$todoList = '<div class="panel panel-default">
						<div class="panel-heading dash-panel clearfix">
                            <h2 class="pull-left"><i class="fa fa-check-square-o"></i> To-Do List</h2>
							
							<div class="pull-right">
                                <div class="btn-group">
									<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
										<i class="fa fa-chevron-down"></i>
									</button>
                                    <ul class="dropdown-menu pull-right slidedown" role="menu">
										<li><a href="?op=admin&amp;type=todo" class="active">To-Do Admin</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="todo" data-adminact="new" data-editid=""><i class="fa fa-plus-square"></i> New To-Do</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">'; 
	
	if ($num_rows > 0) {
		$todoList .= '<form id="todoForm" class="adminForm" method="post">
		<input type="hidden" name="adminAct" value="todo">
		<input type="hidden" name="op" value="complete-multi" />'; 
		$todoList .= '<ul class="list-group checked-list-box">';
		
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$client = $row['clientName'];
			if (!empty($row['clientAbbr'])) 
				$client .= ' ('. $row['clientAbbr'] . ')';
			$dueDate = '--/--/----'; 
			if ( (!empty($row['dueDate'])) || ($row['dueDate'] != '0000-00-00') )	
				$dueDate = Date::convert($row['dueDate'], 'Y-m-d', 'n/d/Y');
			$todoList .= '<li class="list-group-item" id="record-'. $row['todoID'] .'">';
			$todoList .= '<input type="checkbox" name="completeToDos[]" value="'. $row['todoID'] .'" class="todoCheck" /> ';
			$todoList .= '<a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="todo" data-adminact="edit" data-editid="' . $row['todoID'] . '" title="Edit To-Do">' . $row['todoName'];
			$todoList .= '<span class="pull-right text-muted small"><em>'. $dueDate .'</em></span>';
			if (!empty($client))
				$todoList .= '<br><span class="clientName"><em>'. $client .'</em></span>';
			$todoList .= '</a>';
			$todoList .= '</li>';
		}
		$todoList .= '</ul>';
		$todoList .= '<input type="submit" value="Mark Checked To-Do\'s as Complete" class="btn btn-default btn-block" />';
		$todoList .= '</form>';
	} else {
		$todoList .= '<p class="text-center">No To-Do Items</p>';
	}
	$todoList .= '</div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->';
	
// See Dashboard Page (pages/dashboard.php) for To-Do Update Scripts	
	
?>