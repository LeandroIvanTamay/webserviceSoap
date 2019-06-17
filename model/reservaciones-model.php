<?php
    require 'core/db_abstract_model.php';
    class UsuarioM extends DBAbstractModel
    {

        public function __construct(){

        }

        /**
         * Función para pedir todos los usuarios
         */

        public function allUser(){
            $this->query="SELECT * FROM reservaciones";
            $this->get_results_from_query();
            if(count($this->rows) > 0){
                return [
                    "datos"=>$this->rows
                ];
            }else{
                return [
                    "datos"=>"No se encontraron reservaciones"
                ];
            }
        }

        public function addUser($array){
            /**
             * Creación de la contraseña del usuario
             */
            //Definición de variables
            $contrasenaUsuario = "";
            $key_user = "";
            $hash;

            //Se comprueba que existe una contraseña para el colaborador asignada durante el registro
            if(isset($array->contrasenia_colaborador))
            {
                //Se realiza la encriptación del valor de la contraseña
                $hash = password_hash($array->contrasenia_colaborador, PASSWORD_BCRYPT);
            }
            else
            {
                //En caso contrario, se crea una nueva contraseña y se encripta
                $hash = password_hash($contrasenaUsuario, PASSWORD_BCRYPT);
            }

            //Vector para la clave
            $vectorClave = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9");
            //Generamos la clave con 10 caracteres
            for($k = 0; $k<10; $k++){
                $contrasenaUsuario .= $vectorClave[rand(0,35)];
                $key_user .= $vectorClave[rand(0,35)];
            }            
            $hash_key = password_hash($key_user, PASSWORD_BCRYPT);
            
            if(!empty($array)){
                //Consulta para realizar la inserción de datos a la tabla
                $consulta = "INSERT INTO `tb_usuario`(`usuario`,`nombre`, `primer_apellido`, `segundo_apellido`, `contrasena`, `correo`, `id_dependencia`, `dependencia_externa`, `municipio`, `categoria`, `nivel`,`key_user`) 
                VALUES (
                '$array->usuario',
                '$array->nombre',
                '$array->primer_apellido',
                '$array->segundo_apellido',
                '$hash',
                '$array->correo',
                $array->id_dependencia,
                '$array->dependencia_externa',
                '$array->municipio',
                '$array->categoria',
                $array->nivel,
                '$hash_key'
                );";
                $this->query = $consulta;
                $result = $this->execute_single_query();
                if ($result['mensaje'] == "Registrado"){
                    return [
                        "Contrasena" => $contrasenaUsuario,
                        "key" => $key_user
                    ];
                }else{
                    return $result;
                }
            }else{
                return [
                    "error"=>"Error en el JSON"
                ];
            }
        }

        /**
         * Método que verifica el acceso del usuario con usuario y contraseña
         */
        public function verifyUser($array){
            $usuario = $this->getUserByName($array->usuario);
            if($usuario['datos'] != "No se encontró el usuario"){
                $contrasenaUsuario = $usuario["datos"]['contrasena'];
                $tipoUsuario = $usuario["datos"]['nivel'];
                $idUsuario = $usuario["datos"]['id_usuario'];
                $idDependencia = $usuario["datos"]["id_dependencia"];

                if(password_verify($array->contrasena,$contrasenaUsuario)){
                    return [
                        "login"=>"Correcto",
                        "key"=>$usuario["datos"]["key_user"],
                        "nivelUsuario"=>$tipoUsuario,
                        "idUsuario"=>$idUsuario,
                        "idDependencia"=>$idDependencia
                    ];
                }else{
                    return [
                        "login"=>"Usuario no encontrado y/o contraseña incorrecta"
                    ];
                }
            }else{
                return [
                    "login" => "Usuario no encontrado y/o contraseña incorrecta"//$usuario['datos']    
                ];
            }
            
        }

        public function updateUser($array){
            if(!empty($array)){
                $this->query="UPDATE `tb_usuario` SET `usuario`='$array->usuario',`nombre`='$array->nombre',`primer_apellido`='$array->primer_apellido',`segundo_apellido`='$array->segundo_apellido',`correo`='$array->correo',`id_dependencia`=$array->id_dependencia WHERE `id_usuario`=$array->id_usuario";
                //echo  $this->query;
                $result = $this->execute_single_query();
                if ($result['mensaje'] == "Registrado"){
                    return [
                        "mensaje" =>"actualizado"
                    ];
                }else{
                    return $result;
                }
            }else{
                return [
                    "mensaje" =>"Error JSON"
                ];
            }
        }

        public function deleteUser($array){
            if(!empty($array)){
                $this->query="DELETE FROM `tb_usuario` WHERE `id_usuario`=$array->id_usuario";
                //echo  $this->query;
                $result = $this->execute_single_query();
                if ($result['mensaje'] == "Registrado"){
                    return [
                        "mensaje" =>"eliminado"
                    ];
                }else{
                    return $result;
                }
            }else{
                return [
                    "mensaje" =>"Error JSON"
                ];
            }
        }
        /**
         * Método para buscar a un usuario con correo
         */
        public function getUserByEmail($correo){
            $this->query="SELECT * FROM tb_usuario where tb_usuario.correo = '$correo'";
            $this->get_results_from_query();
            if(count($this->rows) > 0){
                return [
                    "datos"=>$this->rows[0]
                ];
            }else{
                return [
                    "datos"=>"No se encontró el usuario"
                ];
            }
        }
        /**
         * Método para buscar a un usuario con su nombre
         */
        public function getUserByName($nombre){
            $this->query="SELECT * FROM tb_usuario where tb_usuario.usuario = '$nombre'";
            $this->get_results_from_query();
            if(count($this->rows) > 0){
                return [
                    "datos"=>$this->rows[0]
                ];
            }else{
                return [
                    "datos"=>"No se encontró el usuario"
                ];
            }
        }

        
        /**
         * Método para buscar a un usuario con su id
         */
        public function getUserById($array){
            $this->query="SELECT * FROM establecimientos where reservaciones.id_reservacion = $array->id_reservacion";
            $this->get_results_from_query();
            if(count($this->rows) > 0){
                return [
                    "datos"=>$this->rows[0]
                ];
            }else{
                return [
                    "datos"=>"No se encontró el usuario"
                ];
            }
        }
    }
?>