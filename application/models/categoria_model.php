 <?php
if (! defined("BASEPATH"))
    exit('No direct script access allowed');

class Categoria_model extends CI_Model
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
     * $data ->   idCategoria, nombre, codigo, descripcion,anuncio, revisado
     * @return id fila insertada
     */
    public function addCategoria($data){
        
        $this->db->insert('categoria', $data);        
        return $this->db->insert_id();
        
    } 
    
}