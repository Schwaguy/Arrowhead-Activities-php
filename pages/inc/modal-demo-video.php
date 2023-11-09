<div id="demo-modal" class="modal video-modal" tabindex="-1" role="dialog">
  	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title">Scheduling Demonstration Video</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
       			<?php 
					$content = ''; 
					include('video-demo.php');
					echo $content;
				?>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>