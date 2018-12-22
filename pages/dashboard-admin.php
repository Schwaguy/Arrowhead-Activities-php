<?php

$pageTitle = 'Dashboard'; 
$page = 'dash'; 
$content .= '<div class="container main">'; 
//include('inc/doc-head.php');
//include('dashboard/project.php');
//include('dashboard/todo.php');
//include('dashboard/task.php');
//include('dashboard/timeline.php');


$content .= '
        	<div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">26</div>
                                    <div>New Comments!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">12</div>
                                    <div>New Tasks!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">124</div>
                                    <div>New Orders!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">13</div>
                                    <div>Support Tickets!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->';
			
			
$content .= '<div class="row">
                <div class="col-lg-8">
                    main
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4">
					aside
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->';

$content .= '<!--Card-->
<div class="card m-5" style="width: 22rem;">

  <!--Card image-->
  <div class="view overlay">
    <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20%287%29.jpg" class="img-fluid" alt="">
    <a href="#">
      <div class="mask rgba-white-slight"></div>
    </a>
  </div>

  <!--Card content-->
  <div class="card-body">
    <!--Title-->
    <h4 class="card-title">Card title</h4>
    <!--Text-->
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    <a href="#" class="btn btn-primary">Button</a>
  </div>

</div>
<!--/.Card-->'; 

/*$docReady .= "
			// Update Panel Content
			$(document).on('click', '.timechange', function (e) {
				var admintype = $(this).data('admintype');
				var filter = $(this).data('filter');
				var page = $(this).data('page');
				var section = $(this).data('section');
				var updateLink = 'pages/dashboard/ajax/' + section +'.php';
				$.ajax({
					type: 'POST',
					url: updateLink,
					data: 'admintype=' + admintype + '&filter=' + filter + '&page=' + page + '&section=' + section,
					dataType: 'json',
					async: false,
					cache: false,
					timeout: 10000,
					success: function(data) {
						if ((data.output.updateOP === 'update') && (data.output.updateString)) {
							if (data.output.updateTitle) {
								$('#' + data.output.section + ' .title-dynamic').html(data.output.updateTitle);	
							}
							$('#' + data.output.section + ' .content-dynamic').html(data.output.updateString);	
						}
					},
					error: function() {
						console.log('ERROR');
					}
				});
				e.preventDefault();
			});
			
			// To-Do Updates
			$('#todoForm').submit(function(e) {
				var url = 'admin/process/todo.php';
				var deleteItems = []; 
				if(confirm('Are you sure these items are complete?  They will be removed from your to-do list.')) {
					$.ajax({
						type: 'POST',
						url: url,
						data: $('#todoForm').serialize(),
						dataType: 'json',
						async: false,
						cache: false,
						timeout: 10000,
						success: function(data) {
							console.log('DELETE ARR: ' + data.output.update);
							if ($.isArray(data.output.update)) {
								console.log('Its an array!');
								$.each(data.output.update, function(key, value){
									console.log('DELETE: ' + value);
									deleteItems.push('todo-' + value);
								});
							} else {
								console.log('NOT an array!');
								console.log('DELETE: ' + value);
								deleteItems.push('todo-' + value);
							}
							$.each(deleteItems, function(key, value){
								console.log(value);
								var deleteme = '#' + value;
								console.log('DELETE ME: ' + deleteme);
								$(deleteme).animate({ backgroundColor: '#fbc7c7' }, 'fast').animate({ opacity: 'hide' }, 'slow');
							});
						},
						error: function() {
							console.log('ERROR');
						}
					});
					e.preventDefault();
				};
			});";*/
$content .= '</div><!-- /container-fluid -->'; 

//include('inc/scripts-modal-form.php');
//include('inc/modal.php');