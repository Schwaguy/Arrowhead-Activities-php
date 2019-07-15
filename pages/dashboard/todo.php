<?php
	
	// Get Active To-Dos
	$query = "SELECT todo.*, c.clientName, c.clientAbbr, p.projectName FROM todo_list todo LEFT JOIN clients c ON (todo.clientID = c.clientID) LEFT JOIN projects p ON (todo.projectID = p.projectID) WHERE todo.todoDone=0 AND todo.display=1 ORDER BY todo.dueDate ASC, todo.todoID ASC";
	$result = $con->query($query);
	$num_rows = $result->num_rows;
	$variables = getVariables('todo','dash');
	
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
										<li><a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="todo" data-adminact="new" data-editid="" data-page="dash" data-section="todo-current"><i class="fa fa-plus-square"></i> New To-Do</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">'; 
	
	if ($num_rows > 0) {
		$todoList .= '<form id="todoForm" class="adminForm" method="post">
		<input type="hidden" name="adminAct" value="todo">
		<input type="hidden" name="page" value="dash">
		<input type="hidden" name="section" value="todo-current">
		<input type="hidden" name="op" value="complete-multi" />'; 
		$todoList .= '<ul class="list-group checked-list-box" id="todo-current" data-section="todo-current">';
		
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			foreach ($variables['displayArray'] as $value) 
				$$value = $row[$value];
			include('inc/list-item/todo.php');
			$todoList .= $listItem;
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