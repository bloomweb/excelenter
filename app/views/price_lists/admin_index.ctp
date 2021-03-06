
<div class="priceLists index">
	<h2><?php __('Listas De Precios');?></h2>
	<table cellpadding="0" cellspacing="0" >
	<tr  >
		<th><?php echo $this->Paginator->sort('Nombre', 'name');?></th>
		<th><?php echo $this->Paginator->sort('Creada', 'created');?></th>
		<th><?php echo $this->Paginator->sort('Actualizada', 'updated');?></th>
		<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($priceLists as $priceList):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?> id='<?php echo $priceList['PriceList']['id'] ?>'>
		<td><?php echo $priceList['PriceList']['name']; ?>&nbsp;</td>
		<td><?php echo $priceList['PriceList']['created']; ?>&nbsp;</td>
		<td><?php echo $priceList['PriceList']['updated']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $priceList['PriceList']['id']),array('target' => '_BLANK', 'class'=>'view icon','title'=>__('View',true))); ?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $priceList['PriceList']['id']),array('class'=>'edit icon','title'=>__('Edit',true))); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $priceList['PriceList']['id']), array('class'=>'delete icon','title'=>__('Delete',true)), sprintf(__('Are you sure you want to delete # %s?', true), $priceList['PriceList']['id'])); ?>
			<?php if(isset($priceList['PriceList']['active'])&& $priceList['PriceList']['active']){
			 echo $this->Html->link(__(' ', true), array('action' => 'setInactive', $priceList['PriceList']['id']), array('class'=>'setInactive icon','title'=>__('Set Inactive',true)), sprintf(__('Are you sure you want to set inactive # %s?', true), $priceList['PriceList']['id']));
}?>
			<?php if(isset($priceList['PriceList']['active'])&& !$priceList['PriceList']['active']){
			 echo $this->Html->link(__(' ', true), array('action' => 'setActive', $priceList['PriceList']['id']), array('class'=>'setActive icon','title'=>__('Set Active',true)), sprintf(__('Are you sure you want to set active # %s?', true), $priceList['PriceList']['id'])); 
}?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
		<?php
			echo $this->Paginator->counter(array(
				                               'format' => __('Página %page% de %pages%, mostrando %current% registros de un total de %count%, se inicia en el registro %start%, se termina en el %end%', true)
			                               ));
		?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previo', true), array(), null, array('class'=>'disabled'));?>
		| 	<?php echo $this->Paginator->numbers();?>
		|
		<?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
	<div class="actions">
		<ul>
			<li>	<?php echo $this->Html->link(__('Add', true), array('action' => 'add'),array('class'=>'add')); ?>
</li>
		</ul>
	</div>
</div>
