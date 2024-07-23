
<?php
class Usuarios_ctrl
{


    
    public $M_Usuarios = null;
    public function __construct()
    {
        $this->M_Usuarios = new M_Usuarios();
    }


    //Registrar usuario
    public function registrarUsuario($f3)
    {


        $usuario = new M_Usuarios();
        $mensaje = "";
        $newId = 0;
        $retorno = 0;

        //Los campos son, cedula, telefono, nombres, apellidos, correo, usuario, clave, rol_id
        $cedula = $f3->get("POST.cedula");
        $telefono = $f3->get("POST.telefono");
        $nombres = $f3->get("POST.nombres");
        $apellidos = $f3->get("POST.apellidos");
        $correo = $f3->get("POST.correo");
        $nombreUsuario = $f3->get("POST.usuario");
        $clave = $f3->get("POST.clave");
        $rol_id = $f3->get("POST.rol_id");

        //verificar si el usuario ya existe
        $usuario->load(['cedula=?', $cedula]);
        if ($usuario->loaded() > 0) {
            $mensaje = "El usuario ya existe";
            $retorno = 0;
        } else {
            //Insertar usuario

            // Encriptar la clave
            $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);



            $usuario->cedula = $cedula;
            $usuario->telefono = $telefono;
            $usuario->nombres = $nombres;
            $usuario->apellidos = $apellidos;
            $usuario->correo = $correo;
            $usuario->usuario = $nombreUsuario;
            $usuario->clave = $claveEncriptada;
            $usuario->rol_id = $rol_id;
            $usuario->save();

            $mensaje = "Usuario registrado exitosamente";
            $newId = $usuario->id; // Obtener el ID del nuevo usuario
            $retorno = 1;
        }

        $response = [
            'mensaje' => $mensaje,
            'id' => $newId,
            'retorno' => $retorno
        ];

        echo json_encode($response);
    }

    // Login de usuario
    public function login($f3)
    {
        $usuario = new M_Usuarios();
        $mensaje = "";
        $retorno = 0;
        $usuario_id = null;

        // Los campos son usuario y clave
        $nombreUsuario = $f3->get("POST.usuario");
        $clave = $f3->get("POST.clave");

        // Verificar si el usuario existe
        $usuario->load(['usuario=?', $nombreUsuario]);
        if ($usuario->loaded() > 0) {
            // Verificar la clave
            if (password_verify($clave, $usuario->clave)) {
                $mensaje = "Inicio de sesión exitoso";
                $retorno = 1;
                $usuario_id = $usuario->id; // Obtener el ID del usuario
            } else {
                $mensaje = "Contraseña incorrecta";
                $retorno = 0;
            }
        } else {
            $mensaje = "El usuario no existe";
            $retorno = 0;
        }

        $response = [
            'mensaje' => $mensaje,
            'retorno' => $retorno,
            'usuario_id' => $usuario_id,
            'nombres' => $usuario->nombres,
        ];

        echo json_encode($response);
    }


    public function editarUsuario($f3)
    {
        $usuario = new M_Usuarios();
        $mensaje = "";
        $retorno = 0;
    
        // Los campos son id, cedula, telefono, nombres, apellidos, correo, usuario, clave (opcional)
        $id = $f3->get("POST.id");
        $cedula = $f3->get("POST.cedula");
        $telefono = $f3->get("POST.telefono");
        $nombres = $f3->get("POST.nombres");
        $apellidos = $f3->get("POST.apellidos");
        $correo = $f3->get("POST.correo");
        $nombreUsuario = $f3->get("POST.usuario");
        $rol_id = $f3->get("POST.rol_id");
        $clave = $f3->get("POST.clave"); // La clave puede ser vacía
    
        // Verificar si la cédula ya existe en otro usuario
        $usuario->load(['cedula=? AND id!=?', $cedula, $id]);
        if ($usuario->loaded() > 0) {
            $mensaje = "La cédula ya existe en otro usuario";
            $retorno = 0;
        } else {
            // Cargar el usuario por ID
            $usuario->load(['id=?', $id]);
            if ($usuario->loaded() > 0) {
                // Actualizar los datos del usuario
                $usuario->cedula = $cedula;
                $usuario->telefono = $telefono;
                $usuario->nombres = $nombres;
                $usuario->apellidos = $apellidos;
                $usuario->correo = $correo;
                $usuario->usuario = $nombreUsuario;
                $usuario->rol_id = $rol_id;
    
                // Solo actualizar la clave si se proporciona una nueva
                if (!empty($clave)) {
                    // Encriptar la nueva clave
                    $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);
                    $usuario->clave = $claveEncriptada;
                }
    
                $usuario->save();
    
                $mensaje = "Usuario actualizado exitosamente";
                $retorno = 1;
            } else {
                $mensaje = "Usuario no encontrado";
                $retorno = 0;
            }
        }
    
        $response = [
            'mensaje' => $mensaje,
            'retorno' => $retorno
        ];
    
        echo json_encode($response);
    }
    

    public function recuperarClave($f3)
    {
        $usuario = new M_Usuarios();
        $mensaje = "";
        $retorno = 0;

        // Los campos son, cedula y nueva_clave
        $cedula = $f3->get("POST.cedula");
        $nuevaclave = $f3->get("POST.nueva_clave");

        // Verificar si la cédula existe
        $usuario->load(['cedula=?', $cedula]);
        if ($usuario->loaded() > 0) {
            // Encriptar la nueva clave
            $nuevaclaveEncriptada = password_hash($nuevaclave, PASSWORD_DEFAULT);

            // Actualizar la clave
            $usuario->clave = $nuevaclaveEncriptada;
            $usuario->save();

            $mensaje = "Contraseña actualizada exitosamente";
            $retorno = 1;
        } else {
            $mensaje = "Usuario no encontrado";
            $retorno = 0;
        }

        $response = [
            'mensaje' => $mensaje,
            'retorno' => $retorno
        ];

        echo json_encode($response);
    }



     // Método para verificar si la cédula existe
     public function verificarCedula($f3)
     {
         $usuario = new M_Usuarios();
         $cedula = $f3->get("POST.cedula");
         $retorno = 0;
         $mensaje = "La cédula no existe";
 
         // Verificar si la cédula existe en la base de datos
         $usuario->load(['cedula=?', $cedula]);
         if ($usuario->loaded() > 0) {
             $retorno = 1;
             $mensaje = "La cédula es válida";
         }
         
 
         $response = [
             'retorno' => $retorno,
             'mensaje' => $mensaje,
         ];
 
         echo json_encode($response);
     }




     public function viewDatosUsersSesion($f3)
     {
         $usuario = new M_Usuarios();
         $id = $f3->get('POST.id'); // Obtener el ID desde el cuerpo de la solicitud POST
         $mensaje = "";
         $retorno = 0;
         $datosUsuario = [];
 
         // Verificar si el usuario existe por ID
         $usuario->load(['id=?', $id]);
         if ($usuario->loaded() > 0) {
             // Usuario encontrado, obtener sus datos
             $datosUsuario = [
                 'id' => $usuario->id,
                 'cedula' => $usuario->cedula,
                 'telefono' => $usuario->telefono,
                 'nombres' => $usuario->nombres,
                 'apellidos' => $usuario->apellidos,
                 'correo' => $usuario->correo,
                 'usuario' => $usuario->usuario,
                 'rol_id' => $usuario->rol_id
             ];
             $retorno = 1;
             $mensaje = "Usuario encontrado";
         } else {
             $mensaje = "Usuario no encontrado";
         }
 
         $response = [
             'retorno' => $retorno,
             'mensaje' => $mensaje,
             'usuario' => $datosUsuario
         ];
 
         echo json_encode($response);
     } 

 
}
