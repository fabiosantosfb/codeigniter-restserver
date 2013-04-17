<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tarefas
 *
 * Recurso para tarefas
 *
 * Copyright (c) 2013, Blum TI <http://blum.eti.br>
 *
 * @filesource
 * @package CodeIgniter
 * @subpackage Rest Server
 * @category Controller
 * @copyright Copyright (c) 2013, Blum TI
 * @author Pablo S Blum de Aguiar <scorphus@gmail.com>
 * @link http://blog.blum.eti.br/
 *
 * Created on Apr 11, 2013
 */

require APPPATH.'/libraries/REST_Controller.php';

class Tarefas extends REST_Controller {

	/**
	 * Action para tratar requisições GET às URIs:
	 *  - /api/tarefas
	 *  - /api/tarefas/{texto}
	 *  - /api/tarefas?{texto=texto}
	 * 
	 * @param string $texto Filtro de tarefas pelo atributo texto
	 */
	public function index_get($texto=null) {
		$this->load->model('tarefa');
		if ($tarefas = $this->tarefa->findAll($texto)) {
			$this->response($tarefas, 200); // 200 Ok
		} else {
			$this->response(null, 204); // 204 No Content
		}
	}
	
	/**
	 * Action para tratar requisições GET à URI /api/tarefas/{id}
	 * 
	 * @param int $id ID da tarefa
	 */
	public function id_get($id) {
		$this->load->model('tarefa');
		if ($tarefa = $this->tarefa->findOne($id)) {
			$this->response($tarefa, 200); // 200 Ok
		} else {
			$this->response(array('error' => 'Tarefa inexistente'), 404); // 404 Not Found
		}
	}
	
	/**
	 * Action para tratar requisições POST à URI /api/tarefas
	 */
	public function index_post() {
		$this->load->model('tarefa');
		$this->tarefa->texto = $this->post('texto');
		$this->tarefa->feita = $this->post('feita') === 'true' or $this->post('feita') === '1';
		if ($tarefa = $this->tarefa->save()) {
			$this->response($tarefa, 201); // 201 Created
		} else {
			$this->response(array('error' => 'Erro ao inserir'), 500); // 500 Internal Server Error
		}
	}
	
	/**
	 * Action para tratar requisições POST à URI /api/tarefas/{id}
	 * 
	 * @param int $id ID da tarefa
	 */
	public function id_put($id) {
		$this->load->model('tarefa');
		if ($this->tarefa->findOne($id)) {
			$this->tarefa->texto = $this->put('texto');
			if ($feita = $this->put('feita')) {
				$this->tarefa->feita = $feita === 'true' or $feita === '1';
			}
			if ($tarefa = $this->tarefa->save()) {
				$this->response($tarefa, 202); // 202 Accepted
			} else {
				$this->response(array('error' => 'Erro ao atualizar'), 500); // 500 Internal Server Error
			}
		} else {
			$this->response(array('error' => 'Tarefa inexistente'), 404); // 404 Not Found
		}
	}
	
	/**
	 * Action para tratar requisições DELETE à URI /api/tarefas/{id}
	 * 
	 * @param int $id ID da tarefa
	 */
	public function id_delete($id) {
		$this->load->model('tarefa');
		if ($this->tarefa->findOne($id)) {
			if ($this->tarefa->delete()) {
				$this->response(null, 204); // 204 No Content
			} else {
				$this->response(array('error' => 'Erro ao excluir'), 500); // 500 Internal Server Error
			}
		} else {
			$this->response(array('error' => 'Tarefa inexistente'), 404); // 404 Not Found
		}
	}
	
}