
<?php
class Productos_ctrl
{
    public $M_Productos = null;
    public function __construct()
    {
        $this->M_Productos = new M_Productos();
    }


    //verProductos
    public function viewProducts($f3)
    {


        $productos = new M_Productos();
        $items = $productos->find();


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

    //Registrar producto
    public function registerProducts($f3)
    {
        $producto = new M_Productos();
        $mensaje = "";
        $newId = 0;
        $retorno = 0;

        //Los campos son, nombre_producto, fecha_producto, hora_producto, stock_producto
        $nombre_producto = $f3->get("POST.nombre_producto");
        $fecha_producto = $f3->get("POST.fecha_producto");
        $hora_producto = $f3->get("POST.hora_producto");
        $stock_producto = $f3->get("POST.stock_producto");

        //verificar si el producto ya existe
        $producto->load(['nombre_producto=?', $nombre_producto]);
        if ($producto->loaded() > 0) {
            $mensaje = "El producto ya existe";
            $retorno = 0;
        } else {
            //Insertar producto
            $producto->nombre_producto = $nombre_producto;
            $producto->fecha_producto = $fecha_producto;
            $producto->hora_producto = $hora_producto;
            $producto->stock_producto = $stock_producto;
            $producto->save();

            $mensaje = "Producto registrado exitosamente";
            $newId = $producto->id; // Obtener el ID del nuevo producto
            $retorno = 1;
        }

        $response = [
            'mensaje' => $mensaje,
            'id' => $newId,
            'retorno' => $retorno
        ];

        echo json_encode($response);
    }


    //Modificar producto
    public function editProducts($f3)
    {
        $producto = new M_Productos();
        $mensaje = "";
        $retorno = 0;

        //Los campos son, id_producto, nombre_producto, fecha_producto, hora_producto, stock_producto
        $id = $f3->get("POST.id");
        $nombre_producto = $f3->get("POST.nombre_producto");
        $fecha_producto = $f3->get("POST.fecha_producto");
        $hora_producto = $f3->get("POST.hora_producto");
        $stock_producto = $f3->get("POST.stock_producto");

        //verificar si el producto existe
        $producto->load(['id=?', $id]);
        if ($producto->loaded() > 0) {
            //Modificar producto
            $producto->nombre_producto = $nombre_producto;
            $producto->fecha_producto = $fecha_producto;
            $producto->hora_producto = $hora_producto;
            $producto->stock_producto = $stock_producto;
            $producto->save();

            $mensaje = "Producto modificado exitosamente";
            $retorno = 1;
        } else {
            $mensaje = "El producto no existe";
            $retorno = 0;
        }

        $response = [
            'mensaje' => $mensaje,
            'retorno' => $retorno
        ];

        echo json_encode($response);
    }

    //Eliminar producto
    public function deleteProducts($f3)
    {
        $producto = new M_Productos();
        $mensaje = "";
        $retorno = 0;

        //Los campos son, id_producto
        $id = $f3->get("POST.id");

        //verificar si el producto existe
        $producto->load(['id=?', $id]);
        if ($producto->loaded() > 0) {
            //Modificar producto
            $producto->erase();

            $mensaje = "Producto eliminado exitosamente";
            $retorno = 1;
        } else {
            $mensaje = "El producto no existe";
            $retorno = 0;
        }

        $response = [
            'mensaje' => $mensaje,
            'retorno' => $retorno
        ];

        echo json_encode($response);
    }           
}
