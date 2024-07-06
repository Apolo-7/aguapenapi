
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


}