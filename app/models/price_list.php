<?php
class PriceList extends AppModel {
	var $name = 'PriceList';
	var $isPicture = false;
	var $sluggable = false;
	var $sortable = false;
	var $activable = false;
	var $order = 'created DESC';

	var $validate = array(
		'name' => array(
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'El nombre debe de ser único',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'path' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	function beforeSave(){
		return true;	
	}
}
