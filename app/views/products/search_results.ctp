Resultados de busqueda:

<?php echo $this->element("listado_producto",array('products' => $products));?>

<div class="ordenar">
	<label>Ver:</label>
	<select class='limite'>
		<option value = '12' <?php if(isset($this->params['named']['limite']) && $this->params['named']['limite'] == 12) echo 'selected="selected"' ?>>12 por página</option>
		<option value = '24'<?php if(isset($this->params['named']['limite']) && $this->params['named']['limite'] == 24) echo 'selected="selected"' ?>>24 por página</option>
	</select>
	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div> 
<div class="info_categoria primero">
	<h1> Somos distribuidores autorizados</h1>
	<p>
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.

	</p>
	<img src="/img/marcas.jpg" />
</div>
<div class="info_categoria">
	<h1>Pague de forma segura</h1>
	<p>
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada.
	</p>
	<img src="/img/tarjetas.jpg" />
</div>
<div style="clear: both"></div>