<div class="brands view">
<h2><?php  __('Brand');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $brand['Brand']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $brand['Brand']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Image'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->image('uploads/100x100/'.$brand['Brand']['image']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Sort'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $brand['Brand']['sort']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Slug'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $brand['Brand']['slug']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $brand['Brand']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Updated'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $brand['Brand']['updated']; ?>
			&nbsp;
		</dd>
	</dl>
</div>


<div class="related">
	<h3><?php __('Related Products');?></h3>
	<?php if (!empty($brand['Product'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Product Type Id'); ?></th>
		<th><?php __('Architecture Id'); ?></th>
		<th><?php __('Brand Id'); ?></th>
		<th><?php __('Is Video Included'); ?></th>
		<th><?php __('Required Power'); ?></th>
		<th><?php __('Is Big Casing Required'); ?></th>
		<th><?php __('Is Power Supply Included'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Ref'); ?></th>
		<th><?php __('Price'); ?></th>
		<th><?php __('Image'); ?></th>
		<th><?php __('Slug'); ?></th>
		<th><?php __('Keywords'); ?></th>
		<th><?php __('Recommendations'); ?></th>
		<th><?php __('Is Gamers'); ?></th>
		<th><?php __('Is Active'); ?></th>
		<th><?php __('Is Featured'); ?></th>
		<th><?php __('Times Visited'); ?></th>
		<th><?php __('Visits'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Updated'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($brand['Product'] as $product):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $product['id'];?></td>
			<td><?php echo $product['product_type_id'];?></td>
			<td><?php echo $product['architecture_id'];?></td>
			<td><?php echo $product['brand_id'];?></td>
			<td><?php echo $product['is_video_included'];?></td>
			<td><?php echo $product['required_power'];?></td>
			<td><?php echo $product['is_big_casing_required'];?></td>
			<td><?php echo $product['is_power_supply_included'];?></td>
			<td><?php echo $product['name'];?></td>
			<td><?php echo $product['description'];?></td>
			<td><?php echo $product['ref'];?></td>
			<td><?php echo $product['price'];?></td>
			<td><?php echo $product['image'];?></td>
			<td><?php echo $product['slug'];?></td>
			<td><?php echo $product['keywords'];?></td>
			<td><?php echo $product['recommendations'];?></td>
			<td><?php echo $product['is_gamers'];?></td>
			<td><?php echo $product['is_active'];?></td>
			<td><?php echo $product['is_featured'];?></td>
			<td><?php echo $product['times_visited'];?></td>
			<td><?php echo $product['visits'];?></td>
			<td><?php echo $product['created'];?></td>
			<td><?php echo $product['updated'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'products', 'action' => 'view', $product['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'products', 'action' => 'edit', $product['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'products', 'action' => 'delete', $product['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $product['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Product', true), array('controller' => 'products', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
