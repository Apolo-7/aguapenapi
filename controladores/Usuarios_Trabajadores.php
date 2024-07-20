
<?php
class Usuarios_Trabajadores
{
    public $M_UsuariosTrabajadores = null;
    public function __construct()
    {
        $this->M_UsuariosTrabajadores = new M_UsuariosTrabajadores();
    }

    //verUsuariosTrabajadores
    public function viewUsuariosTrabajadores($f3)
    {
        $usuariosTrabajadores = new M_UsuariosTrabajadores();
        $items = $usuariosTrabajadores->find();

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

    public function newUsuarioTrabajador($f3)
    {
        $usuarioTrabajador = new M_UsuariosTrabajadores();
        $mensaje = "";
        $newId = 0;
        $retorno = 0;

        // Campos
        $tx_nombre = $f3->get("POST.tx_nombre");
        $tx_cedula = $f3->get("POST.tx_cedula");
        $tx_area = $f3->get("POST.tx_area");
        $tx_cargo = $f3->get("POST.tx_cargo");
        $tx_vehiculo = $f3->get("POST.tx_vehiculo");
        $tx_vehiculo_descripcion = $f3->get("POST.tx_vehiculo_descripcion");

        // Verificar si el usuario ya existe
        $usuarioTrabajador->load(['tx_cedula=?', $tx_cedula]);
        if ($usuarioTrabajador->loaded() > 0) {
            $mensaje = "El usuario trabajador ya existe";
            $retorno = 0;
        } else {
            // Insertar usuario trabajador
            $usuarioTrabajador->tx_nombre = $tx_nombre;
            $usuarioTrabajador->tx_cedula = $tx_cedula;
            $usuarioTrabajador->tx_area = $tx_area;
            $usuarioTrabajador->tx_cargo = $tx_cargo;
            $usuarioTrabajador->tx_vehiculo = $tx_vehiculo;
            $usuarioTrabajador->tx_vehiculo_descripcion = $tx_vehiculo_descripcion;
            $usuarioTrabajador->save();

            $mensaje = "Usuario trabajador registrado exitosamente";
            $newId = $usuarioTrabajador->id_usuario; // Obtener el ID del nuevo usuario
            $retorno = 1;
        }

        $response = [
            'mensaje' => $mensaje,
            'id' => $newId,
            'retorno' => $retorno
        ];

        echo json_encode($response);
    }
 // Editar Usuario Trabajador
 public function editUsuarioTrabajador($f3)
 {
     $usuarioTrabajador = new M_UsuariosTrabajadores();
     $mensaje = "";
     $retorno = 0;

     // Campos
     $id_usuario = $f3->get("POST.id_usuario");
     $tx_nombre = $f3->get("POST.tx_nombre");
     $tx_cedula = $f3->get("POST.tx_cedula");
     $tx_area = $f3->get("POST.tx_area");
     $tx_cargo = $f3->get("POST.tx_cargo");
     $tx_vehiculo = $f3->get("POST.tx_vehiculo");
     $tx_vehiculo_descripcion = $f3->get("POST.tx_vehiculo_descripcion");

     // Verificar si el usuario existe
     $usuarioTrabajador->load(['id_usuario=?', $id_usuario]);
     if ($usuarioTrabajador->loaded() > 0) {
         // Modificar usuario trabajador
         $usuarioTrabajador->tx_nombre = $tx_nombre;
         $usuarioTrabajador->tx_cedula = $tx_cedula;
         $usuarioTrabajador->tx_area = $tx_area;
         $usuarioTrabajador->tx_cargo = $tx_cargo;
         $usuarioTrabajador->tx_vehiculo = $tx_vehiculo;
         $usuarioTrabajador->tx_vehiculo_descripcion = $tx_vehiculo_descripcion;
         $usuarioTrabajador->save();

         $mensaje = "Usuario trabajador modificado exitosamente";
         $retorno = 1;
     } else {
         $mensaje = "El usuario trabajador no existe";
         $retorno = 0;
     }

     $response = [
         'mensaje' => $mensaje,
         'retorno' => $retorno
     ];

     echo json_encode($response);
 }
}