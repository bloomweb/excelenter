<?php
class PriceListsController extends AppController {

	var $name = 'PriceLists';

	function beforeFilter() {
		parent::beforeFilter();
		//$this->Auth->allow('*');
	}

	public function download() {
		$priceList = $this -> PriceList -> find('first');
		if(!empty($priceList)) {
			$this -> view = 'Media';
			$fileParts = pathinfo($priceList['PriceList']['path']);
			//debug($priceList);
			//debug($fileParts);
			//debug(APP.'webroot/img/uploads/'.$fileParts['basename'].DS );
			$params = array(
				'id' => $fileParts['basename'],
				'name' => $fileParts['filename'],
				'download' => true, 
				'extension' => $fileParts['extension'], // must be lower case
				'path' => APP.'webroot/files/uploads'.DS  // don't forget terminal 'DS'
			);
			$this -> set($params);
		} else {
			// TODO : que hacer si no hay archivos?
			$this -> Session -> setFlash('Actualmente no hay listado de precios.');
			$this -> redirect($this -> referer());
		}
	}

	function admin_index() {
		$this -> PriceList -> recursive = 0;
		$this -> set('priceLists', $this -> paginate());
	}

	function admin_add() {
		if (!empty($this -> data)) {
			$this -> autoRender = false;
			$this -> PriceList -> create();
			if ($this -> PriceList -> save($this -> data)) {
				$this -> Session -> setFlash(__('Lista de precio subida con éxito', true));
				echo 1;
			} else {
				echo 0;
			}
			exit(0);
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this -> data)) {
			$this -> Session -> setFlash(__('Invalid price list', true));
			$this -> redirect(array('action' => 'index'));
		}
		if (!empty($this -> data)) {
			if ($this -> PriceList -> save($this -> data)) {
				$this -> Session -> setFlash(__('Se ha modificado la lista de precios', true));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('No se pudo modificar la lista de precios. Por favor, intente de nuevo.', true));
			}
		}
		if (empty($this -> data)) {
			$this -> data = $this -> PriceList -> read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('ID no válido para lista de precios', true));
			$this -> redirect(array('action' => 'index'));
		}
		if ($this -> PriceList -> delete($id)) {
			$this -> Session -> setFlash(__('Ha eliminado la lista de precios', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash(__('No se pudo eliminar la lista de precios', true));
		$this -> redirect(array('action' => 'index'));
	}

	function admin_view($id = null) {

		$this -> view = 'Media';

		$priceList = $this -> PriceList -> read(null, $id);

		$this->set(compact('priceList'));

		$fileParts = pathinfo($priceList['PriceList']['path']);

		//debug(APP.'webroot/img/uploads/'.$fileParts['basename'].DS );

		$params = array(
			'id' => $fileParts['basename'],
			'name' => $fileParts['filename'],
			'download' => false,
			'extension' => isset($fileParts['extension']) ? strtolower($fileParts['extension']) : '', // must be lower case
			'path' => APP . 'webroot/files/uploads' . DS, // don't forget terminal 'DS'
			'cache' => false
		);

		$this -> set($params);

	}

	function view($id = null) {

		$this -> view = 'Media';

		$priceList = $this -> PriceList -> read(null, $id);

		$this->set(compact('priceList'));

		$fileParts = pathinfo($priceList['PriceList']['path']);

		//debug(APP.'webroot/img/uploads/'.$fileParts['basename'].DS );

		$params = array(
			'id' => $fileParts['basename'],
			'name' => $fileParts['filename'],
			'download' => false,
			'extension' => isset($fileParts['extension']) ? strtolower($fileParts['extension']) : '', // must be lower case
			'path' => APP . 'webroot/files/uploads' . DS, // don't forget terminal 'DS'
			'cache' => false
		);

		$this -> set($params);

	}

}
