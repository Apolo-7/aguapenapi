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

    // Registrar registro y registro detalle
    public function registerRegistro($f3)
    {
        $registro = new M_Registro();
        $registroDetalle = new M_RegistroDetalle();
        $productos = new M_Productos();
        $mensaje = "";
        $newId = 0;
        $retorno = 0;

        // Obtener datos del registro
        $id_usuario = $f3->get("POST.id_usuario");
        $fecha_registro = $f3->get("POST.fecha_registro");
        $hora_registro = $f3->get("POST.hora_registro");
        $observacion = $f3->get("POST.observacion");
        $detalles = $f3->get("POST.detalles"); // Array de detalles

        // Validaciones
        if (empty($id_usuario) || empty($fecha_registro) || empty($hora_registro) || empty($detalles)) {
            $mensaje = "Todos los campos son requeridos";
            $retorno = 0;
            echo json_encode(['mensaje' => $mensaje, 'retorno' => $retorno]);
            return;
        }

        if (!is_array($detalles) || count($detalles) == 0) {
            $mensaje = "Debe proporcionar al menos un detalle";
            $retorno = 0;
            echo json_encode(['mensaje' => $mensaje, 'retorno' => $retorno]);
            return;
        }

        // Validar detalles
        foreach ($detalles as $detalle) {
            if (empty($detalle['id_producto']) || empty($detalle['cantidad']) || !is_numeric($detalle['cantidad'])) {
                $mensaje = "Todos los detalles deben tener un id_producto y una cantidad válida";
                $retorno = 0;
                echo json_encode(['mensaje' => $mensaje, 'retorno' => $retorno]);
                return;
            }
        }

        // Insertar en la tabla registro
        $registro->id_usuario = $id_usuario;
        $registro->fecha_registro = $fecha_registro;
        $registro->hora_registro = $hora_registro;
        $registro->observacion = $observacion;
        $registro->save();

        $newId = $registro->id; // Obtener el ID del nuevo registro

        // Insertar detalles en la tabla registro_detalle y actualizar productos
        foreach ($detalles as $detalle) {
            // Insertar detalle
            $registroDetalle->reset();
            $registroDetalle->id_registro = $newId;
            $registroDetalle->id_producto = $detalle['id_producto'];
            $registroDetalle->cantidad = $detalle['cantidad'];
            $registroDetalle->save();

            // Actualizar cantidad del producto
            $productos->load(['id=?', $detalle['id_producto']]);
            if ($productos->loaded() > 0) {
                $productos->stock_producto -= $detalle['cantidad'];
                $productos->save();
            } else {
                $mensaje = "El producto con ID " . $detalle['id_producto'] . " no existe";
                $retorno = 0;
                echo json_encode(['mensaje' => $mensaje, 'retorno' => $retorno]);
                return;
            }
        }

        $mensaje = "Registro y detalles registrados exitosamente";
        $retorno = 1;

        $response = [
            'mensaje' => $mensaje,
            'id' => $newId,
            'retorno' => $retorno
        ];

        echo json_encode($response);
    }
}
