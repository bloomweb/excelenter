<?php 
	$selectedId1 = $selectedId2 = null; // los seleccionados en a variable pc
	if(isset($selected_id_1)) {
		$selectedId1 = $selected_id_1;
	}
	if(isset($selected_id_2)) {
		$selectedId2 = $selected_id_2;
	}
?>
<div class="exclusivo">
	<div class="col"  rel="Memory">
		<h2>Memoria (1 módulo):</h2>
		<?php echo $form -> radio('memory_id1', $items, array('legend'=>false,'value'=>$selectedId1, 'rel'=>'1')); ?>	
	</div>	
	<div class="col"  rel="Memory">
		<h2>Memoria (2 módulos):</h2>
		<?php echo $form -> radio('memory_id2', $items, array('legend'=>false,'value'=>$selectedId2, 'rel'=>'2')); ?>	
	</div>
</div>