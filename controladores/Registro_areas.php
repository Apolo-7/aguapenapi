
<?php
class Registro_areas
{


    public $M_Registro_AREAS = null;
    public $M_registroDestalle_AREAS = null;
    public $M_Productos = null;

    public function __construct()
    {
        $this->M_Registro_AREAS = new M_Registro_AREAS();
        $this->M_registroDestalle_AREAS = new M_registroDestalle_AREAS();
        $this->M_Productos = new M_Productos();
    }

 //Registrar un registro
    public function registrarRegistrode_Area($f3)
    {   
        $registro = new M_Registro_AREAS();
        $mensaje = "";
        $newId = 0;
        $retorno = 0;

// Establecer la zona horaria
        date_default_timezone_set('America/Guayaquil');

        $id_area = $f3->get("POST.id_area");
        $observacion = $f3->get("POST.observacion");
            // Fecha y hora actuales
        $fecha_registro = date('Y-m-d');
        $hora_registro = date('H:i:s');
        //Insertar registro
        $registro->id_area = $id_area;
        $registro->fecha_registro = $fecha_registro;
        $registro->hora_registro = $hora_registro;
        $registro->observacion = $observacion;
        $registro->save();
    
        $mensaje = "Registro creado exitosamente";
        $newId = $registro->id_registro_a; // Obtener el ID del nuevo registro
        $retorno = 1;
    
        $response = [
            'mensaje' => $mensaje,
            'id' => $newId,
            'retorno' => $retorno
        ];
    
        echo json_encode($response);
    }
   
    // Registrar detalles del registro
    public function RegDetalles_areas($f3)
    {
        $M_registroDestalle_AREAS = new M_registroDestalle_AREAS();
        $producto = new M_Productos(); // Crear instancia del modelo de productos
        $mensaje = "";
        $retorno = 0;

        //campos
        $id_registro_a = $f3->get("POST.id_registro_a");
        $id_producto = $f3->get("POST.id_producto");
        $cantidad = $f3->get("POST.cantidad"); 
        // Validar si el registro existe

        $registro = $this->M_Registro_AREAS->load(['id_registro_a = ?', $id_registro_a]);
        if (!$registro) {
            $mensaje = "El registro no existe";
            $response = [
                'mensaje' => $mensaje,
                'retorno' => $retorno
            ];
            echo json_encode($response);
            return;
        }
 // Validar si el producto existe
        $producto = $this->M_Productos->load(['id = ?', $id_producto]);
        if (!$producto) {
            $mensaje = "El producto no existe";
            $response = [
                'mensaje' => $mensaje,
                'retorno' => $retorno
            ];
            echo json_encode($response);
            return;
        }
 // Validar si hay suficiente stock del producto
        if ($producto->stock_producto < $cantidad) {
            $mensaje = "Stock insuficiente para el producto";
            $response = [
                'mensaje' => $mensaje,
                'retorno' => $retorno
            ];
            echo json_encode($response);
            return;
        }
        // Insertar detalle del registro
        $M_registroDestalle_AREAS->id_registro_a = $id_registro_a;
        $M_registroDestalle_AREAS->id_producto = $id_producto;
        $M_registroDestalle_AREAS->cantidad = $cantidad;
        $M_registroDestalle_AREAS->save();

        // Actualizar el stock del producto
        $nuevoStock = $producto->stock_producto - $cantidad;
        $producto->stock_producto = $nuevoStock;
        $producto->save();

        $mensaje = "Detalle registrado exitosamente";
        $retorno = 1;
    
        $response = [
            'mensaje' => $mensaje,
            'retorno' => $retorno
        ];
    
        echo json_encode($response);
    

}

}