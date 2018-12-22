<?php 

	$disableSort = ((!empty($noSortColumns)) ? "aoColumnDefs: [{'bSortable':false,'aTargets':[". $noSortColumns ."]}]," : '');

	$colDef = 'null,'; 
	$aoCols = str_repeat($colDef, $sortColumns);
	$aoCols = substr_replace($aoCols ,"",-1);
	if ($tabTable) {
		$docReady .= "
		$('#tabs').tabs( {
			'show': function(event, ui) {
				var table = $.fn.dataTable.fnTables(true);
				if ( table.length > 0 ) {
					$(table).dataTable().fnAdjustColumnSizing();
				}
			}
		} );";
	}
	$docReady .= "$('.dataTable').DataTable({
    	responsive: true,
		//scrollY:	'". $scrollHeight ."',
       	//scrollCollapse: true,
       	//paging:	false,
		". $disableSort ."
		stateSave: true
	});"; 
?>