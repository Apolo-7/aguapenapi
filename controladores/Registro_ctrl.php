<?php
class Registro_ctrl
{
    public $M_Registro = null;
    public $M_RegistroDetalle = null;
    public $M_Productos = null;

    public function __construct()
    {
        $this->M_Registro = new M_Registro();
        $this->M_RegistroDetalle = new M_RegistroDetalle();
        $this->M_Productos = new M_Productos();
    }

    //Registrar un registro
    public function registerRegistro($f3)
    {
        $registro = new M_Registro();
        $mensaje = "";
        $newId = 0;
        $retorno = 0;

        //Los campos son, id_usuario, fecha_registro, hora_registro, observacion
        $id_usuario = $f3->get("POST.id_usuario");
        $observacion = $f3->get("POST.observacion");

        // Fecha y hora actuales
        $fecha_registro = date('Y-m-d');
        $hora_registro = date('H:i:s');

        //Insertar registro
        $registro->id_usuario = $id_usuario;
        $registro->fecha_registro = $fecha_registro;
        $registro->hora_registro = $hora_registro;
        $registro->observacion = $observacion;
        $registro->save();

        $mensaje = "Registro creado exitosamente";
        $newId = $registro->id_registro; // Obtener el ID del nuevo registro
        $retorno = 1;

        $response = [
            'mensaje' => $mensaje,
            'id' => $newId,
            'retorno' => $retorno
        ];

        echo json_encode($response);
    }

    //Registrar detalles del registro
    public function registerRegistroDetalle($f3)
    {
        $registroDetalle = new M_RegistroDetalle();
        $producto = new M_Productos(); // Crear instancia del modelo de productos
        $mensaje = "";
        $retorno = 0;

        //Los campos son, id_registro, id_producto, cantidad
        $id_registro = $f3->get("POST.id_registro");
        $id_producto = $f3->get("POST.id_producto");
        $cantidad = $f3->get("POST.cantidad");

        //Insertar detalle del registro
        $registroDetalle->id_registro = $id_registro;
        $registroDetalle->id_producto = $id_producto;
        $registroDetalle->cantidad = $cantidad;
        $registroDetalle->save();

        // Actualizar el stock del producto
        $nuevoStock = $producto->get('stock_producto') - $cantidad;
        $producto->set('stock_producto', $nuevoStock);
        $producto->save();

        $mensaje = "Detalle del registro creado y stock actualizado exitosamente";
        $retorno = 1;

        $response = [
            'mensaje' => $mensaje,
            'retorno' => $retorno
        ];

        echo json_encode($response);
    }

    //Ver registros
    public function viewRegistros($f3)
    {
        $registros = new M_Registro();
        $items = $registros->find();

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

    //Ver detalles de registro
    public function viewRegistroDetalles($f3)
    {
        $registroDetalle = new M_RegistroDetalle();
        $items = $registroDetalle->find();

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
?>
