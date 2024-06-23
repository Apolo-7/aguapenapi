
<?php
class Productos_ctrl
{
    public $M_Productos = null;
    public function __construct()
    {
        $this->M_Productos = new M_Productos();
    }

  
    //verProductos
    public function verProductos($f3){
        
        $cadenaSql = "SELECT * FROM productos";
       
        //Ejecutar la consulta SQL
        $items = $f3->get('DB')->exec($cadenaSql);

        $response = [
            'cantidad' => count($items),
            'data' => $items
        ];

        echo json_encode($response);


    }


}