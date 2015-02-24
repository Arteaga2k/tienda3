<?php
if (! defined("BASEPATH"))
    exit('No direct script access allowed');

class Producto_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Inserta en la bbdd una nueva categoria
     *
     * @param array $data
     * $data ->   idProducto, nombre, codigo, precio,imagen, descuento, anuncio, descripcion, iva, stock
     * idCategoria
     * @return id fila insertada
     */
    public function addProducto($data){

        $this->db->insert('producto', $data);
        return $this->db->insert_id();

    }
    
    /**
     * Devuelve listado de productos
     * revisados
     */
    public function  getProductos(){
        $productos = $this->db->where('revisado', true)->get('producto');
        
        return $productos->result_array();
    }

}