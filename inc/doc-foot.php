</div><!-- /side-collapse-container -->

<?php if (!empty($thisPg) && ($thisPage!='logout')) : ?>
<footer id="site-footer">
	<div class="container">
		<div class="row">
			<div class="col-6"><?=$backBtn?></div>
			<div class="col-6"></div>	
		</div>
	</div>	
</footer>
<?php endif ?>

<div id="feedback"><div id="processing"><i class="fas fa-spinner fa-pulse fa-spin"></i></div><div id="response" class="container"></div></div>

<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.0.1.min.js" integrity="sha256-F0O1TmEa4I8N24nY0bya59eP6svWcshqX1uzwaWC4F4=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/additional-methods.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script type="text/javascript" src="/js/popper.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<!--<script type="text/javascript" src="/js/mdb.min.js"></script>-->
<script type="text/javascript" src="/js/addons/datatables.min.js"></script>
<script type="text/javascript" src="/js/redirect.jquery.js"></script>
<script type="text/javascript" src="/js/arrow-activities.js"></script>

<?php
require_once('globals/phpDebug.php');
$debug = new PHPDebug();
?>