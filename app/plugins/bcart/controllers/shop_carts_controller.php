<?php
class ShopCartsController extends BcartAppController {

	var $name = 'ShopCarts';

	function beforeFilter() {
		parent::beforeFilter();
		$this -> Auth -> allow('addToCart', 'removeFromCart', 'removeAllFromCart', 'checkoutCart', 'viewCart', 'getCart', 'updateShopCartItem', 'getResume', 'refresh', 'setCoupon', 'loginTransition');
	}

	/**
	 * ---------------------------------------------------------------------------------------------
	 * 									METODOS PARA MANEJO DEL CARRITO
	 *
	 * El carrito debe de poder ser accedido por cualquiera.
	 * ---------------------------------------------------------------------------------------------
	 */

	function loginTransition($user_id = null) {
		$this -> autoRender = false;
		if ($user_id) {
			$shopCart = $this -> getCart();
			// Leer el carrito de la sesion
			if (!empty($shopCart['ShopCartItem'])) {
				$user_shopCart = $this -> ShopCart -> find('first', array('conditions' => array('ShopCart.user_id')));
				if (!empty($user_shopCart)) {
					// Luego de encontrar el carrito del usuario
					$cantidad = count($shopCart['ShopCartItem']);
					for ($i = 0; $i < $cantidad; $i += 1) {
						$this -> ShopCart -> ShopCartItem -> recursive = -1;
						$this -> ShopCart -> ShopCartItem -> read(null, $shopCart['ShopCartItem'][$i]['id']);
						$this -> ShopCart -> ShopCartItem -> saveField('shop_cart_id', $user_shopCart['ShopCart']['id']);
					}
					$this -> loginTransitionClearCart($shopCart['ShopCart']['id']);
				} else {
					// No se encontró carrito del usuario
				}
			}
		}
	}

	private function loginTransitionClearCart($shop_cart_id = null) {
		$this -> autoRender = false;
		$shopping_cart = null;
		if ($shop_cart_id) {
			$shopping_cart = $this -> ShopCart -> read(null, $shop_cart_id);
		} else {
			$shopping_cart = $this -> getCart();
		}
		if (empty($shopping_cart)) {
			// No hay carrito; hacer algo?
		} else {
			// Hay carrito, borrar el ítem acorde su id
			$this -> ShopCart -> ShopCartItem -> deleteAll(array('shop_cart_id' => $shopping_cart['ShopCart']['id']));
		}
	}

	function getResume() {
		$this -> autoRender = false;
		$this -> layout = "ajax";
		// precio total
		// cantidad de items
		$shop_cart = $this -> getCart();
		if ($shop_cart) {
			// items y total
			$info = array();
			$total_items = 0;
			$total_price = 0.0;
			foreach ($shop_cart['ShopCartItem'] as $item) {
				$total_items++;
				$this -> loadModel('Product');
				$product = $this -> Product -> read(null, $item['foreign_key']);
				$value = $product['Product']['price'];
				$quantity = $item['quantity'];
				$total_price += $quantity * $value;
			}
			$info['ShopCart']['items'] = $total_items;
			$info['ShopCart']['total'] = "$" . number_format($total_price, 0, ' ', '.');
			return json_encode($info);
		} else {
			return false;
		}
		exit(0);
	}

	function setCoupon($coupon_serial = null) {
		$this -> autoRender = false;
		$this -> layout = "ajax";
		// Verificar que el cupon existe
		if ($coupon_serial) {
			if ($coupon = $this -> ShopCart -> Coupon -> findBySerial($coupon_serial)) {
				// El cupon existe -> validar que no este en otro carrito
				if (($this -> ShopCart -> findByCouponId($coupon['Coupon']['id'])) || ($this -> ShopCart -> User -> Order -> findByCouponId($coupon['Coupon']['id']))) {
					// Ya esta el cupon en otro carrito
					echo json_encode(array('result' => false, 'message' => 'El cupon ya ha sido previamente asignado a otro carrito de compras'));
				} else {
					// El cupon no ha sido asignado en otro carrito
					$batch = $this -> ShopCart -> Coupon -> CouponBatch -> read(null, $coupon['Coupon']['coupon_batch_id']);
					$shop_cart = $this -> getCart();
					$shop_cart['ShopCart']['coupon_id'] = $coupon['Coupon']['id'];
					$shop_cart['ShopCart']['coupon_discount'] = $batch['CouponBatch']['value'];
					if ($this -> ShopCart -> save($shop_cart)) {
						echo json_encode(array('result' => true, 'message' => 'Se aplicó el cupon', 'value' => $batch['CouponBatch']['value']));
					} else {
						echo json_encode(array('result' => false, 'message' => 'Ocurrió un error al aplicar el cupon'));
					}
				}
			} else {
				// El cupon no existe
				echo json_encode(array('result' => false, 'message' => 'El cupon ingresado no existe'));
			}
		} else {
			echo json_encode(array('result' => false, 'message' => 'No ha ingresado un serial de cupon'));
		}
		exit(0);
	}

	/**
	 * Encontrar el carrito
	 */
	function getCart() {
		// Datos con los que podría existir el carrito
		$user_id = $this -> Session -> read('Auth.User.id');
		$identifier = $this -> Session -> read('carrito');

		// Verificar si existe un carro con estos datos
		$shopping_cart = null;
		if ($user_id) {
			// Está registrado el usuario
			$shopping_cart = $this -> ShopCart -> find('first', array('conditions' => array('ShopCart.user_id' => $user_id)));
		} else {
			// No esta registrado el usuario
			if ($identifier) {
				// Hay "user_agent"
				$shopping_cart = $this -> ShopCart -> find('first', array('conditions' => array('ShopCart.identifier' => $identifier)));
			} else {
				// No hay "user_agent", no se puede buscar un carrito
			}
		}

		// Verificar si se encontró o no un carrito
		if (empty($shopping_cart)) {
			// Crear un carrito porque no lo hay
			$this -> ShopCart -> create();
			if ($user_id) {
				$this -> ShopCart -> set('user_id', $user_id);
			} else {
				$time = (float)(vsprintf('%d.%06d', gettimeofday()));
				$this -> ShopCart -> set('identifier', $time);
			}
			if ($this -> ShopCart -> save()) {
				// Se creo el carrito, guardar la info
				$this -> Session -> write('carrito', $time);
			}
		}

		// Limpiar carros viejos
		$this -> ShopCart -> cleanCarts();

		return $shopping_cart;
	}

	/**
	 * Añadir ítems al carrito
	 **/
	function addToCart() {
		$this -> autoRender = false;
		$this -> layout = "ajax";
		$this -> loadModel('Product');
		$shopping_cart = $this -> getCart();
		$cart_id = $shopping_cart['ShopCart']['id'];
		$this -> Product -> recursive = 0;
		$product = $this -> Product -> read(null, $this -> data['ShopCartItem']['foreign_key']);

		// Verificar si el ítem ya esta dentro del carrito
		$cart_item = $this -> ShopCart -> ShopCartItem -> find('first', array('conditions' => array('ShopCartItem.shop_cart_id' => $cart_id, 'ShopCartItem.foreign_key' => $this -> data['ShopCartItem']['foreign_key'], 'ShopCartItem.is_gift' => $this -> data['ShopCartItem']['is_gift'])));
		if ($cart_item) {
			$cart_item['ShopCartItem']['quantity'] = $cart_item['ShopCartItem']['quantity'] + 1;
			if ($this -> ShopCart -> ShopCartItem -> save($cart_item)) {
				echo json_encode($cart_item);
			} else {
				echo false;
			}
		} else {
			// No está el ítem
			$this -> ShopCart -> ShopCartItem -> create();
			$this -> data['ShopCartItem']['shop_cart_id'] = $cart_id;
			if ($cart = $this -> ShopCart -> ShopCartItem -> save($this -> data)) {
				echo json_encode($cart);
			} else {
				echo false;
			}
		}
		exit(0);
	}

	/**
	 * Añadir ítems al carrito
	 **/
	function addPCToCart() {
		$this -> autoRender = false;
		$this -> layout = "ajax";
		$shopping_cart = $this -> getCart();
		$cart_id = $shopping_cart['ShopCart']['id'];

		$myPC = $this -> requestAction('/make_pc/getMyPC');
		$this -> ShopCart -> ShopCartItem -> create();
		$this -> ShopCart -> ShopCartItem -> set('shop_cart_id', $cart_id);
		$this -> ShopCart -> ShopCartItem -> set('pc_order', json_encode($myPC));
		if ($this -> ShopCart -> ShopCartItem -> save()) {
			echo json_encode($this -> getCart());
		} else {
			echo 'false';
		}
		exit(0);
	}

	/**
	 * Remover ítems del carrito
	 */
	function removeFromCart($item_id) {
		$this -> autoRender = false;
		$shopping_cart = $this -> getCart();
		if (empty($shopping_cart)) {
			// No hay carrito; hacer algo?
			echo false;
		} else {
			// Hay carrito, borrar el ítem acorde su id
			$this -> ShopCart -> ShopCartItem -> delete($item_id);
			echo json_encode($this -> getCart());
		}
		exit(0);
	}

	/**
	 * Remover todos los  ítems del carrito
	 */
	function removeAllFromCart($shop_cart_id = null) {
		$this -> autoRender = false;
		$shopping_cart = null;
		if ($shop_cart_id) {
			$shopping_cart = $this -> ShopCart -> read(null, $shop_cart_id);
		} else {
			$shopping_cart = $this -> getCart();
		}
		if (empty($shopping_cart)) {
			// No hay carrito; hacer algo?
		} else {
			// Hay carrito, borrar el ítem acorde su id
			$this -> ShopCart -> ShopCartItem -> deleteAll(array('shop_cart_id' => $shopping_cart['ShopCart']['id']));
			echo json_encode($this -> getCart());
		}
		exit(0);
	}

	/**
	 * Actualizar la cantidad de un ítem
	 */
	function updateShopCartItem($item_id, $field_name, $value) {
		$this -> autoRender = false;
		$this -> ShopCart -> ShopCartItem -> read(null, $item_id);
		$this -> ShopCart -> ShopCartItem -> saveField($field_name, $value);

		echo json_encode($this -> getCart());

		exit(0);
	}

	function viewCart() {
		$this -> layout = 'bcart';
		$shopping_cart = $this -> getCart();
		$this -> set('shopping_cart', $shopping_cart);
		$this -> Session -> write('referer', $this -> referer());
	}

	function refresh() {
		$this -> layout = 'ajax';
		$shopping_cart = $this -> getCart();
		$this -> set('shopping_cart', $shopping_cart);
	}

	/**
	 * ---------------------------------------------------------------------------------------------
	 * 										CRUD
	 *
	 * 						REVISAR QUE METODOS QUEDAN PARA EL FINAL
	 * ---------------------------------------------------------------------------------------------
	 */

	function index() {
		$this -> ShopCart -> recursive = 0;
		$this -> set('shopCarts', $this -> paginate());
	}

	function admin_index() {
		$this -> ShopCart -> recursive = 0;
		$this -> set('shopCarts', $this -> paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid shop cart', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> set('shopCart', $this -> ShopCart -> read(null, $id));
	}

	function admin_add() {
		if (!empty($this -> data)) {
			$this -> ShopCart -> create();
			if ($this -> ShopCart -> save($this -> data)) {
				$this -> Session -> setFlash(__('The shop cart has been saved', true));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The shop cart could not be saved. Please, try again.', true));
			}
		}
		$users = $this -> ShopCart -> User -> find('list');
		$this -> set(compact('users'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this -> data)) {
			$this -> Session -> setFlash(__('Invalid shop cart', true));
			$this -> redirect(array('action' => 'index'));
		}
		if (!empty($this -> data)) {
			if ($this -> ShopCart -> save($this -> data)) {
				$this -> Session -> setFlash(__('The shop cart has been saved', true));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The shop cart could not be saved. Please, try again.', true));
			}
		}
		if (empty($this -> data)) {
			$this -> data = $this -> ShopCart -> read(null, $id);
		}
		$users = $this -> ShopCart -> User -> find('list');
		$this -> set(compact('users'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid id for shop cart', true));
			$this -> redirect(array('action' => 'index'));
		}
		if ($this -> ShopCart -> delete($id)) {
			$this -> Session -> setFlash(__('Shop cart deleted', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash(__('Shop cart was not deleted', true));
		$this -> redirect(array('action' => 'index'));
	}

	function admin_setInactive($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid id for shop cart', true));
			$this -> redirect(array('action' => 'index'));
		}
		$oldData = $this -> ShopCart -> read(null, $id);
		$oldData["ShopCart"]["active"] = false;
		if ($this -> ShopCart -> save($oldData)) {
			$this -> Session -> setFlash(__('Shop cart archived', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash(__('Shop cart was not archived', true));
		$this -> redirect(array('action' => 'index'));
	}

	function admin_setActive($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid id for shop cart', true));
			$this -> redirect(array('action' => 'index'));
		}
		$oldData = $this -> ShopCart -> read(null, $id);
		$oldData["ShopCart"]["active"] = true;
		if ($this -> ShopCart -> save($oldData)) {
			$this -> Session -> setFlash(__('Shop cart archived', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash(__('Shop cart was not archived', true));
		$this -> redirect(array('action' => 'index'));
	}

	function admin_requestFind($type, $findParams, $key) {
		if ($key == Configure::read("key")) {
			return $this -> ShopCart -> find($type, $findParams);
		} else {
			return null;
		}
	}

}
