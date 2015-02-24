<?php
require_once APPPATH . 'third_party/phpexcel/Classes/PHPExcel/IOFactory.php';

/**
 *
 * @author Carlos
 *        
 */
class Utiles extends CI_Controller
{

    /**
     * Constructor clase Carro
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('categoria_model');
        $this->load->model('producto_model');
    }

    public function importaDatosExcel()
    {}

    public function importaDatosFromXml()
    {
        $this->importaCategoriasXml();
        $this->importaProductosXml();
    }

    /**
     * Llamada a funciones exportadatos
     * categorias y productos
     */
    public function exportaDatosXml()
    {
        $this->exportaCategoriasXml();
        $this->exportaProductosXml();
    }

    /**
     * Importación listado categorías desde un fichero excel
     * a la bbdd
     */
    public function importaCategoriasExcel()
    {
        $categorias = array();
        $inputFileName = dirname(__DIR__) . '/upload' . '/' . 'categoria.xlsx';
        
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        } catch (PHPExcel_Reader_Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
        
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        
        // recorremos por fila excel
        foreach ($sheetData as $data) {
            $id = $this->categoria_model->addCategoria(array(
                'idCategoria' => $data['A'],
                'nombre' => $data['B'],
                'codigo' => $data['C'],
                'descripcion' => $data['D'],
                'anuncio' => $data['E'],
                'revisado' => $data['D']
            ));
        }
    }

    /**
     * Importa listado categorias contenido en categorias.xml
     * a la bbdd
     */
    public function importaCategoriasXml()
    {
        $categorias = simplexml_load_file(dirname(__DIR__) . '/upload' . '/' . 'categorias.xml');
        foreach ($categorias as $categoria) {
            echo $categoria->nombre;
        }
    }

    /**
     * Importa lista productos desde un fichero xml
     * a la bbdd
     */
    public function importaProductosXml()
    {
        $productos = simplexml_load_file(dirname(__DIR__) . '/upload' . '/' . 'categorias.xml');
        var_dump($productos);
        
        foreach ($productos as $producto) {
            $this->producto_model->addProducto(array(
                'idProducto' => $producto->idProducto,
                'nombre' => $producto->nombre,
                'codigo' => $producto->codigo,
                'precio' => $producto->precio,
                'imagen' => $producto->imagen,
                'revisado' => $producto->revisado,
                'descuento' => $producto->descuento,
                'anuncio' => $producto->anuncio,
                'descripcion' => $producto->descripcion,
                'iva' => $producto->iva,
                'stock' => $producto->stock,
                'idCategoria' => $producto->idCategoria
            )
            );
        }
    }

    /**
     * Exporta la tabla Categorias de la bbdd
     * a un fichero xml, categorias.xml
     */
    public function exportaCategoriasXml()
    {
        // obtenemos lista categorías
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
        // exportamos lista categorías a fichero xml
        $sxe->asXML(dirname(__DIR__) . '/upload' . '/' . 'categorias.xml');
    }

    /**
     * Exporta la tabla productos de la bbdd 
     * en un fichero xml, productos.xml
     */
    public function exportaProductosXml()
    {
        // obtenemos lista categorias
        $productos = $this->producto_model->getProductos();
        
        $xml = "<productos>";
        foreach ($productos as $producto) {
            $xml .= "<producto>";
            $xml .= "<idProducto>" . $producto['idProducto'] . "</idProducto>";
            $xml .= "<nombre>" . $producto['nombre'] . "</nombre>";
            $xml .= "<codigo>" . $producto['codigo'] . "</codigo>";
            $xml .= "<descripcion>" . $producto['precio'] . "</descripcion>";
            $xml .= "<imagen>" . $producto['imagen'] . "</imagen>";
            $xml .= "<revisado>" . $producto['revisado'] . "</revisado>";
            $xml .= "<descuento>" . $producto['descuento'] . "</descuento>";
            $xml .= "<descripcion>" . $producto['descripcion'] . "</descripcion>";
            $xml .= "<iva>" . $producto['iva'] . "</iva>";
            $xml .= "<stock>" . $producto['stock'] . "</stock>";
            $xml .= "<idCategoria>" . $producto['idCategoria'] . "</idCategoria>";
            $xml .= "</producto>";
        }
        
        $xml .= "</productos>";
        $sxe = new SimpleXMLElement($xml);
        // exportamos categorias a fichero formato xml
        $sxe->asXML(dirname(__DIR__) . '/upload' . '/' . 'productos.xml');
    }
}