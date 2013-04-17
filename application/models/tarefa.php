<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tarefa
 * 
 * Modelo para as informações de uma tarefa
 *
 * Copyright (c) 2013, Blum TI <http://blum.eti.br>
 *
 * @filesource
 * @package CodeIgniter
 * @subpackage Rest Server
 * @category Model
 * @copyright Copyright (c) 2013, Blum TI
 * @author Pablo S Blum de Aguiar <scorphus@gmail.com>
 * @link http://blog.blum.eti.br/
 *
 * Created on Apr 11, 2013
 */

class Tarefa extends CI_Model {

	/**
	 * ID da tarefa
	 * @var integer
	 */
	public $id;

	/**
	 * Texto da tarefa
	 * @var string
	 */
	public $texto;

	/**
	 * Tarefa feita?
	 * @var boolean
	 */
	public $feita;

	/**
	 * Data e hora de criação da tarefa no banco de dados
	 * @var Timestamp
	 */
	public $created;

	/**
	 * Timestamp da úiltima modificação da tarefa no banco de dados
	 * @var datetime
	 */
	public $modified;

	/**
	 * Método contrutor
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Encontra todas as tarefas tarefas
	 * 
	 * @param string $texto Filtro para o texto
	 * @return array Array de tarefas
	 */
	public function findAll($texto=null) {
		if (!is_null($texto)) {
			$this->db->like('texto', $texto);
		}
		$query = $this->db->get('tarefas');
		if ($query->num_rows() >= 1) {
			$tarefas = array();
			foreach ($query->result() as $row) {
				$row->feita = intval($row->feita) !== 0;
				$tarefas[] = $row;
			}
			return $tarefas;
		}
		return null;
	}

	/**
	 * Encontra uma tarefa específica
	 * 
	 * @param integer $id ID da tarega
	 * @return object Tarefa
	 */
	public function findOne($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('tarefas');
		if ($query->num_rows() == 1) {
			$row = array_pop($query->result());
			$row->feita = intval($row->feita) !== 0;
			foreach ($row as $field => $value) {
				$this->$field = $value;
			}
			return $row;
		}
		return null;
	}

	/**
	 * Cria ou altera uma tarefa
	 * 
	 * @return integer ID da tarefa
	 */
	public function save() {
		if (isset($this->id)) {
			$this->modified = date('Y-m-d H:i:s', time());
			$this->db->where('id', $this->id);
			if ($this->db->update('tarefas', $this)) {
				return $this;
			}
			return false;
		} else {
			$this->created = date('Y-m-d H:i:s', time());
			$this->modified = date('Y-m-d H:i:s', time());
			if ($this->db->insert('tarefas', $this)) {
				$this->id = $this->db->insert_id();
				return $this;
			}
			return false;
		}
	}

	/**
	 * Exclui uma tarefa
	 * 
	 * @return boolean Resultado da exlusão
	 */
	public function delete() {
		if (!empty($this->id)) {
			$this->db->where('id', $this->id);
			$this->db->delete('tarefas');
			return true;
		}
		return null;
	}

}

/* End of file tarefa.php */
/* Location: ./system/application/models/tarefa.php */
