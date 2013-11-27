<div class="priceLists form">
<?php echo $this->Form->create('PriceList');?>
	<fieldset>
		<legend><?php __('Editar Lista De Precios'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Enviar', true));?>
</div>

