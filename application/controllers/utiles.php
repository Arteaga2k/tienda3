<?php
require_once APPPATH . 'third_party/phpexcel/Classes/PHPExcel/IOFactory.php';

/**
 *
 * @author Carlos
 *        
 */
class Utiles extends CI_Controller
{

    private $form = array();

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

    /**
     * Muestra formulario para subir un archivo
     */
    public function importa_datos()
    {
        $this->form["form_upload"] = form_open_multipart('utiles/do_upload', array(
            "class" => "form-horizontal",
            "name" => "procesaFormUsuario"
        ));
        
        echo $this->twig->render('ficheros/upload_formulario.twig', array(
            'form' => $this->form
        ));
    }

    /**
     * Importación listado categorías desde un fichero excel
     * a la bbdd
     */
    public function importa_categorias_excel($nombre)
    {
        $categorias = array();
        $inputFileName = 'uploads/' . $nombre;
        
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
     * Importación listado categorías desde un fichero excel
     * a la bbdd
     */
    public function importa_productos_excel($nombre)
    {
        $categorias = array();
        $inputFileName = 'uploads/' . $nombre;
        
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        } catch (PHPExcel_Reader_Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
        
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        
        // recorremos por fila excel
        foreach ($sheetData as $data) {
            $id = $this->producto_model->addProducto(array(
                'idProducto' => $data['A'],
                'nombre' => $data['B'],
                'codigo' => $data['C'],
                'precio' => $data['D'],
                'imagen' => $data['E'],
                'revisado' => $data['F'],
                'descuento' => $data['G'],
                'anuncio' => $data['H'],
                'descripcion' => $data['I'],
                'iva' => $data['J'],
                'stock' => $data['K'],
                'idCategoria' => $data['L']
            ));
        }
    }

    /**
     * Importa listado categorias contenido en categorias.xml
     * a la bbdd
     */
    public function importa_categorias_xml($nombre)
    {
        $categorias = simplexml_load_file('uploads/' . $nombre);
        foreach ($categorias as $categoria) {
            $id = $this->categoria_model->addCategoria(array(
                'idCategoria' => $categoria->idCategoria,
                'nombre' => $categoria->nombre,
                'codigo' => $categoria->codigo,
                'descripcion' => $categoria->descripcion,
                'anuncio' => $categoria->anuncio,
                'revisado' => $categoria->revisado
            ));
        }
    }

    /**
     * Importa lista productos desde un fichero xml
     * a la bbdd
     */
    public function importa_productos_xml($nombre)
    {
        $productos = simplexml_load_file('uploads/' . $nombre);
        
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
            ));
        }
    }

    /**
     * Llamada a funciones exportadatos
     * categorias y productos
     */
    public function exporta_xml()
    {
        $this->exporta_categorias_xml();
        $this->exporta_productos_xml();
    }

    /**
     * Exporta la tabla Categorias de la bbdd
     * a un fichero xml, categorias.xml
     */
    public function exporta_categorias_xml()
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
        $sxe->asXML('uploads/categorias.xml');
    }

    /**
     * Exporta la tabla productos de la bbdd
     * en un fichero xml, productos.xml
     */
    public function exporta_productos_xml()
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
        $sxe->asXML('uploads/productos.xml');
    }

    /**
     * Procesa y valida la subida de un fichero xml o excel
     * Si todo es correcto, realiza la importanción del mismo
     */
    function do_upload()
    {
        // configuramos parámetros libreria upload
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'xml|xlsx';
        $config['max_size'] = '2000';
        $config['max_width'] = '2024';
        $config['max_height'] = '2008';
        
        $this->load->library('upload', $config);
        
        if (! $this->upload->do_upload('filename')) {
            $this->form['error'] = $this->upload->display_errors();
            $this->importa_xml();
        } else {
            
            // obtenemos info del archivo subido
            $file_info = $this->upload->data();
            $nombre = $file_info['file_name'];
            $tipo = $file_info['file_type'];
            
            $pos = strpos($tipo, 'sheet');
            
            if ($pos === false) {
                // inicia la importación del fichero subido según contenido
                $this->identifica_contenido_xml($nombre);
                $icono = 'xml.png';
            } else {
                // inicia la importación del fichero subido según contenido
                $this->identifica_contenido_excel($nombre);
                $icono = 'xlsx.png';
            }
            
            // mostramos mensaje subida correcta
            echo $this->twig->render('ficheros/uploads_vista.twig', array(
                'tipo' => $icono,
                'nombre' => $nombre
            ));
        }
    }

    /**
     * Identifica tipo (productos, categorias...) del fichero xml
     * y lee contenido correspondiente
     *
     * @param unknown $nombre            
     */
    function identifica_contenido_xml($nombre)
    {
        $productos = simplexml_load_file('uploads/' . $nombre);
        
        switch ($productos->getName()) {
            case 'productos':
                $this->importa_productos_xml($nombre);
                break;
            case 'categorias':
                $this->importa_categorias_xml($nombre);
                break;
            default: // TODO
        }
    }

    /**
     * Identifica tipo (productos, categorias...) del fichero excel
     * y lee contenido correspondiente
     *
     * @param unknown $nombre            
     */
    function identifica_contenido_excel($nombre)
    {
        switch ($nombre) {
            case 'productos':
                $this->importa_productos_excel($nombre);
                break;
            case 'categorias':
                $this->importa_categorias_excel($nombre);
                break;
            default: // TODO
        }
    }
}