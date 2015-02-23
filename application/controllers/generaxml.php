<?php

/**
 *
 * @author Carlos
 *
 */
class Generaxml extends CI_Controller
{

    /**
     * Constructor clase Carro
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
    }
    
    public function importaDatosExcel(){
        
    }   

    public function importaDatosFromXml()
    {
        $this->importaCategoriasXml();
        $this->importaProductosXml();
    }


    public function exportaDatosXml()
    {
        $this->exportaCategoriasXml();
        $this->exportaProductosXml();
    }
    
    public function importaCategoriasExcel(){
        $file = dirname(__DIR__) . '/upload' . '/'.'test.xlsx';
        //load the excel library
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
            //header will/should be in row 1 only. of course this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }
        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data;
        
    }
    
    public function importaCategoriasXml(){
        $categorias = simplexml_load_file(dirname(__DIR__) . '/upload' . '/' . 'categorias.xml');
        foreach($categorias as $categoria){            
            echo $categoria->nombre;               
        }  
        
    }
    
    public function importaProductosXml(){
        $productos = simplexml_load_file(dirname(__DIR__) . '/upload' . '/' . 'categorias.xml');
        foreach($productos as $producto){
            echo $categoria->nombre;    
        }   
    }

    public function exportaCategoriasXml()
    {
        $categorias = $this->home_model->getCategorias();
        
        $xml = "<categorias>";
        foreach ($categorias as $categoria) {
            $xml .= "<categoria>";
            $xml .= "<id>" . $categoria['idCategoria'] . "</id>";
            $xml .= "<nombre>" . $categoria['nombre'] . "</nombre>";
            $xml .= "<codigo>" . $categoria['codigo'] . "</codigo>";
            $xml .= "<descripcion>" . $categoria['descripcion'] . "</descripcion>";
            $xml .= "<anuncio>" . $categoria['anuncio'] . "</anuncio>";
            $xml .= "<revisado>" . $categoria['revisado'] . "</revisado>";
            $xml .= "</categoria>";
        }
        
        $xml .= "</categorias>";
        $sxe = new SimpleXMLElement($xml);
        $sxe->asXML(dirname(__DIR__) . '/upload' . '/' . 'categorias.xml');
    }

    public function exportaProductosXml()
    {
        $categorias = $this->home_model->getCategorias();
        
        $xml = "<productos>";
        foreach ($categorias as $categoria) {
            $xml .= "<categoria>";
            $xml .= "<id>" . $categoria['idCategoria'] . "</id>";
            $xml .= "<nombre>" . $categoria['nombre'] . "</nombre>";
            $xml .= "<codigo>" . $categoria['codigo'] . "</codigo>";
            $xml .= "<descripcion>" . $categoria['descripcion'] . "</descripcion>";
            $xml .= "<anuncio>" . $categoria['anuncio'] . "</anuncio>";
            $xml .= "<revisado>" . $categoria['revisado'] . "</revisado>";
            $xml .= "</categoria>";
        }
        
        $xml .= "</productos>";
        $sxe = new SimpleXMLElement($xml);
        $sxe->asXML(dirname(__DIR__) . '/upload' . '/' . 'productos.xml');
    }
    
    public function to_excel($sql, $filename='exceloutput')
    {
        $headers = ''; // just creating the var for field headers to append to below
        $data = ''; // just creating the var for field data to append to below
    
        $obj =& get_instance();
    
        $query = $sql["query"];
    
        $fields = $sql["fields"];
    
        if ($query->num_rows() == 0) {
            echo '<p>The table appears to have no data.</p>';
        } else {
            foreach ($fields as $field) {
                $headers .= $field->name . "\t";
            }
    
            foreach ($query->result() as $row) {
                $line = '';
                foreach($row as $value) {
                    if ((!isset($value)) OR ($value == "")) {
                        $value = "\t";
                    } else {
                        $value = str_replace('"', '""', $value);
                        $value = '"' . $value . '"' . "\t";
                    }
                    $line .= $value;
                }
                $data .= trim($line)."\n";
            }
    
            $data = str_replace("\r","",$data);
    
            header("Content-type: application/x-msdownload");
            header("Content-Disposition: attachment; filename=$filename.xls");
            echo mb_convert_encoding("$headers\n$data",'utf-16','utf-8');
        }
}