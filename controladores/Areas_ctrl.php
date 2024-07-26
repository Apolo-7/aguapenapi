
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



    public function viewRegxAreas($f3)
    {
        $cadenaSql = "SELECT 
        r.id_registro_a,
        r.fecha_registro,
        r.hora_registro,
        a.nombre_area,  -- Añadido nombre del área
        p.nombre_producto,
        rd.cantidad,
        SUM(rd.cantidad) OVER (PARTITION BY r.id_registro_a) AS total_cantidades
    FROM 
        registro_area AS r
    JOIN 
        registro_detalle_areas AS rd ON r.id_registro_a = rd.id_registro_a
    JOIN 
        productos AS p ON rd.id_producto = p.id
    JOIN 
        areas AS a ON r.id_area = a.id  -- Join adicional para obtener el nombre del área
    ORDER BY 
        r.id_registro_a, p.nombre_producto";
    

        // Ejecutar la consulta
        $items = $f3->DB->exec($cadenaSql);
        echo json_encode([
            'cantidad' => count($items),
            'data' => $items
        ]);
    }
}