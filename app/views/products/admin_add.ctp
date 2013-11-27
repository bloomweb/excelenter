<div class="products form2">
	<?php echo $this -> Form -> create('Product');?>
	<fieldset>
		<legend>
			<?php __('Agregar Producto');?>
		</legend>
		<?php
		echo $this -> Form -> input('product_type_id', array('label' => 'Tipo', "empty" => "Seleccione..."));
		echo $this -> Form -> hidden('image', array('label' => 'Imagen', 'id' => 'single-field'));
		e('<div id="ProductProductTypeInfo"></div>');
		echo $this -> Form -> input('brand_id', array('label' => 'Marca'));
		echo $this -> Form -> input('name', array('label' => 'Nombre'));
		echo $this -> Form -> input('description', array('label' => 'Descripción'));
		echo $this -> Form -> input('tech_specs', array('label' => 'Especificaciones'));
		echo $this -> Form -> input('ref', array('label' => 'Referencia'));
		echo $this -> Form -> input('price', array('label' => 'Precio'));
		echo $this -> Form -> input('keywords', array('label' => 'Palabras clave'));
		echo $this -> Form -> input('recommendations', array('label' => 'Recomendaciones'));
		echo $this -> Form -> input('is_gamers', array('label' => 'Gamers'));
		echo $this -> Form -> input('is_active', array('label' => 'Activo'));
		echo $this -> Form -> input('is_featured', array('label' => 'Destacado'));
		echo $this -> Form -> input('Tag', array('multiple'=>'checkbox'));
		//echo $this -> Form -> input('Tag', array('type'=>'radio'));
		?>
	</fieldset>
	<?php echo $this -> Form -> end(__('Enviar', true));?>
</div>
<div class="images">
	<h2>Imagen</h2>
	<div class="preview">
		<div class="wrapper">
			<?php echo $this -> Html -> image('preview.png');?>
		</div>
	</div>
	<div id="single-upload" controller="products"></div>
</div>
