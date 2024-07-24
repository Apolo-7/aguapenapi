
<?php
class Areas_ctrl
{
    public $M_Areas = null;
    public function __construct()
    {
        $this->M_Areas = new M_Areas();
    }


    //verProductos
    public function viewAreas ($f3)
    {


        $estaciones = new M_Areas();
        $items = $estaciones->find();


        // Convertir los objetos en arrays
        $itemsArray = [];
        foreach ($items as $item) {
            $itemsArray[] = $item->cast();
        }

        $response = [
            'cantidad' => count($itemsArray),
            'data' => $itemsArray
        ];

        echo json_encode($response);
    }

   
}
