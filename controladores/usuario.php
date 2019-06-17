<?php
    require 'model/usuariom.php';
    class Usuario{
        private $userModel;

        public function __construct(){
            
        }

        public function index(){
			echo 'index desde UsuarioController';
		}

        public function post($parametros){
            $this->userModel = new usuarioM();
            $oParam = (object)$parametros;
            if(!empty($parametros)){
                $opcion = $parametros['opcion'];
                switch($opcion){
                case 'all':
                    return ($this->userModel->allUser());
                break;
                case 'delete':
                    return $this->userModel->deleteUser($oParam);
                break;
                case 'update':
                    return $this->userModel->updateUser($oParam);
                break;
                case 'login':
                    return $this->userModel->verifyUser($oParam);
                break;

                case 'add':
                    return $this->userModel->addUser($oParam);
                break;

                case 'selectid':
                    return ($this->userModel->getUserById($oParam));
                break;

                case 'sistema':
                    require_once('site_media/json/index.php');
                break;
                case 'registrar_colaborador':
                    return $this->userModel->addUser($oParam);
                break;

                default:
                echo 'hola';
                break;
                }
            }
        }
    }
?>
