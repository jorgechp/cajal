<?php

require_once (constant("FOLDER") . '/model/typesenum.php');

/**
 * Description of ModelGenerator
 *
 * @author jorge
 */
class ModelGenerator {

    /**
     * Guarda el tipo de modelo que se ha de cargar
     * @var TypesEnum
     */
    protected $tipoModelo;

    /**
     * Almacena el identificador del usuario
     * @var int 
     */
    protected $idUsuario;

    /**
     * 
     * @param TypesEnum $tipoModelo
     * @param int $tipoModelo
     */
    function __construct($tipoModelo, $idUsuario = null) {
        $this->tipoModelo = $tipoModelo;
        $this->idUsuario = $idUsuario;
    }

    /**
     * Lanza una excepción de error si el número de parámetros requeridos por el modelo es insuficiente
     * @throws Exception
     */
    protected function errorParametros() {
        throw new Exception('Error 0: Número de parámetros insuficiente para el modelo seleccionado', 0);
    }

    /**
     * Lanza una excepción si el mensaje especificado por el usuario
     * para ser visualizado o borrado no pertenece al usuario
     * que ha iniciado sesión en el sistema.
     * @throws Exception
     */
    protected function errorPropiedad() {
        throw new Exception('Error 1: El mensaje especificado no pertenece al usuario identificado', 1);
    }

    /**
     * Obtiene el listado de competencias aprobadas por un estudiante
     * @param CompetenceDAO $competenceDAO
     * @return Competence[]
     */
    private function getPassedCompetences($competenceDAO) {
        //Se comprueban las competencias que han sido aprobadas por el estudiante
        $listadoCompetencias = $competenceDAO->getAll();
        $listadoCompetenciasAprobadas = array();
        $evaluationReport = new EvaluationReport($GLOBALS["SYSTEM_CONNECTION"]);
        $evaluationReport->isStudentPassedCompetence($this->idUsuario, 15);

        foreach ($listadoCompetencias as $competencia) {
            if ($evaluationReport->isStudentPassedCompetence($this->idUsuario, $competencia->getIdCompetencia())) {
                $listadoCompetenciasAprobadas[] = $competencia;
            }
        }
        return $listadoCompetenciasAprobadas;
    }

    /**
     * Valida el DNI de un usuario
     * http://kiwwito.com/funcion-php-para-validar-dni-nie-espanol/
     * @param type $string
     * @return boolean
     */
    private function is_valid_dni_nie($string) {
        if (strlen($string) != 9 ||
                preg_match('/^[XYZ]?([0-9]{7,8})([A-Z])$/i', $string, $matches) !== 1) {
            return false;
        }

        $map = 'TRWAGMYFPDXBNJZSQVHLCKE';

        list(, $number, $letter) = $matches;

        return strtoupper($letter) === $map[((int) $number) % 23];
    }

    /**
     * Comprueba que la longitud de cada elemento del array $dataForm es correcta
     * en base al array $conditions. Este es un array de enteros con los tamaños máximos
     * de cada elemento de $dataForm.
     * 
     * Devuelve un array donde, para cada elemento de $dataForm
     *  -> el elemento homónimo vale igual que el de $dataform si el dato valida correctamente
     *  -> el elemento homónimo vale false si no ha validado
     * 
     * @param string[] $dataForm
     * @param int[] $conditions
     * @return string[]
     */
    protected function checkFormulario($dataForm, $conditions) {
        $contador = 0;
        $dataRes = array();
        $dataRes[0] = true;
        foreach ($dataForm as $dataKey => $dataValue) {
            $tamCampo = strlen($dataValue);
            if (strcmp($dataKey, 'check') != 0) {
                if ($tamCampo == 0 || $tamCampo > $conditions[$contador]) {

                    $dataRes[0] = false;
                    $dataRes[$dataKey] = false;
                } else {
                    $dataRes[$dataKey] = $dataValue;
                }
                ++$contador;
            }
        }
        return $dataRes;
    }

    /**
     *  Filtra la subida de un archivo
     * http://stackoverflow.com/questions/4908321/should-i-somehow-protect-my-file-user-input
     * @param String $source
     * @param String $destination
     * @param String $chmod
     * @return string
     */
    protected function upload($source, $destination, $nameDestination, $files = null, $chmod = null) {

        $result = array();
        if ($files == null) {
            $files = $_FILES;
        }

        if ((is_dir($destination) === true) && file_exists($source)) {

            $result = false;

            if ($files['error'] == UPLOAD_ERR_OK) {
                $file = $nameDestination;

                if (file_exists($destination . $file) === true) {
                    $file = substr_replace($file, '_' . md5_file($files['tmp_name']), strrpos($value, '.'), 0);
                    $result = false;
                }

                if (move_uploaded_file($files['tmp_name'], $destination . $file) === true) {
                    if (Chmod($destination . $file, $chmod) === true) {
                        $result = $destination . $file;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Genera aleatoriamente un cadena
     * http://stackoverflow.com/questions/4356289/php-random-string-generator
     * @param int $length
     * @return string
     */
    protected function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * Genera la vista que muestra información sobre un indicador. Requiere
     * como parámetro un vector con dos cadenas de texto, la primera debe ser
     * el identificador de la competencia. La segunda debe ser el identificador
     * del indicador.
     * @param String[] $params
     */
    protected function indicatorInfo($params) {
        if (count($params) != 4) {
            $this->errorParametros();
        }
        $idCompetencia = $params[0];
        $idIndicador = $params[1];
        $idStudent = $params[2];
        $idYear = $params[3];

        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $evaluacionDAO = new EvaluacionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $lugarDAO = new LugarMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $sessionDAO = new SessionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $evaluationReport = new EvaluationReport($GLOBALS["SYSTEM_CONNECTION"]);

        $indicador = $indicatorDAO->get($idCompetencia, $idIndicador);
        $competencia = $competenceDAO->get($idCompetencia);

        if (!isset($idYear)) {
            $idYear = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();
        }

        $evaluacionesIndicador = $evaluacionDAO->getLastEvaluationByIndicator($idCompetencia, $idIndicador, $idStudent, $idYear);

        $data = array();
        $data['CONTENT_MAIN']['competence']['id'] = $competencia->getIdCompetencia();
        $data['CONTENT_MAIN']['competence']['name'] = $competencia->getName();

        $data['CONTENT_MAIN']['indicator']['id'] = $indicador->getIdIndicator();
        $data['CONTENT_MAIN']['indicator']['name'] = $indicador->getName();
        $data['CONTENT_MAIN']['indicator']['code'] = $indicador->getCode();
        $data['CONTENT_MAIN']['indicator']['description'] = $indicador->getDescription();
        $data['CONTENT_MAIN']['indicator']['strictMean'] = $evaluationReport->getStudentEvaluationOnIndicator($idStudent, $idCompetencia, $idIndicador);


        if ($evaluacionesIndicador == false) {
            $data['CONTENT_MAIN']['diagram'] = null;
        } else {
            $contador = 0;
            foreach ($evaluacionesIndicador as $evaluacion) {

                $data['CONTENT_MAIN']['diagram'][$contador]['evaluation'] = $evaluacion;
                $data['CONTENT_MAIN']['diagram'][$contador]['activity'] = $activityDAO->get($evaluacion->getIdActivity());
                $session = $sessionDAO->get($evaluacion->getIdActivity(), $evaluacion->getIdSession());
                $data['CONTENT_MAIN']['diagram'][$contador]['place'] = $lugarDAO->get($session->getIdLugar())->getNombre();
                ++$contador;
            }
        }




        return $data;
    }

    /**
     * Elimina los mensajes enviados como parámetro
     * @param int[] $messages Identificadores de los mensajes que serán eliminados
     */
    protected function deleteMessages($messages) {
        $idUser = $this->idUsuario;
        $messagesDAO = new MessageMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        foreach ($messages as $message) {
            /**
             * Comprobación necesaria por seguridad. Para que el mensaje pueda ser eliminado
             * Se han de cumplir dos condiciones:
             * 
             *  1) Se ha iniciado sesión en el servidor
             *  2) El usuario de la sesión es el propietario real del mensaje que se va a eliminar
             * 
             *  Con esto se evita un intento de eliminar mensaje enviando falsas cabeceras POST
             *  por parte de un atacante malicioso.
             */
            if ($messagesDAO->isMessagePropertyOfUser($message, $idUser)) {
                $messagesDAO->delete($message, $idUser);
            } else {
                $this->errorPropiedad();
            }
        }
    }

    /**
     * Genera la vista que muestra información sobre los mensajes de un usuario.
     * 
     */
    protected function messagesInfo($params) {
        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $messagesDAO = new MessageMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $mensajes = array();
        $mensajesEnviados = false;

        if (isset($params['SENT_MESSAGES']) && $params['SENT_MESSAGES'] == true) {
            $mensajes = $messagesDAO->getAllMessagesFromUser($this->idUsuario);
            $mensajesEnviados = true;
        } else {

            $mensajes = $messagesDAO->getAllMessagesToUser($this->idUsuario);
        }
        $data = array();

        if ($mensajes != false) {
            $contador = 0;
            foreach ($mensajes as $mensaje) {

                $data['CONTENT_MAIN']['MESSAGES'][$contador]['object'] = $mensaje;
                $user = $userDAO->get($mensaje->getIdSender());
                $nombreUsuario = $user->getNombre() . ' ' . $user->getApellido1() . ' ' . $user->getApellido2();
                $data['CONTENT_MAIN']['MESSAGES'][$contador]['nombre'] = $nombreUsuario;
                if (!$mensajesEnviados) {
                    $data['CONTENT_MAIN']['MESSAGES'][$contador]['isRead'] = $messagesDAO->isMessageRead($this->idUsuario, $mensaje->getIdMessage());
                } else {
                    $data['CONTENT_MAIN']['MESSAGES'][$contador]['isRead'] = true;
                    $data['CONTENT_MAIN']['MESSAGES'][$contador]['isRemitente'] = true;
                    $destinatarios_mensajes = $messagesDAO->getDestinatarios($mensaje->getIdMessage());
                    $data['CONTENT_MAIN']['MESSAGES'][$contador]['remitentes'] = array();
                    if (is_array($destinatarios_mensajes)) {
                        foreach ($destinatarios_mensajes as $destinatario) {
                            $user = $userDAO->get($destinatario);
                            $nombreUsuario = $user->getNombre() . ' ' . $user->getApellido1() . ' ' . $user->getApellido2();
                            $data['CONTENT_MAIN']['MESSAGES'][$contador]['remitentes'][] = $nombreUsuario;
                        }
                    }
                }
                ++$contador;
            }
        }
        return $data;
    }

    /**
     * Obtiene la información relativa a un mensaje
     * @param int $params identificador del mensaje que se va a mostrar
     * 
     * Devuelve un array con la siguiente estructura:
     *      ['CONTENT_MAIN']['VIEWMESSAGE'] objeto mensaje con el mensaje seleccionado
     *      ['CONTENT_MAIN']['SenderNombre'] nombre del usuario que manda el mensaje
     *      
     * Marca el mensaje como leído
     */
    protected function viewMessage($params) {
        $idUser = $this->idUsuario;
        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $data = array();
        $messagesDAO = new MessageMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        /**
         * Comprobación necesaria por seguridad. Para que el mensaje pueda ser mostrado
         * se han de cumplir dos condiciones:
         * 
         *  1) Se ha iniciado sesión en el servidor
         *  2) El usuario de la sesión es el propietario real del mensaje que se va a visualizar (remitente o destinatario)
         * 
         *  Con esto se evita un intento de mostrar un mensaje a un usuario
         * al cual no va dirigido.
         *  
         */
        if ($messagesDAO->isMessagePropertyOfUser($params, $idUser)) {

            $mensaje = $messagesDAO->get($params);

            $data['CONTENT_MAIN']['VIEWMESSAGE'] = $mensaje;
            $user = $userDAO->get($mensaje->getIdSender());
            $data['CONTENT_MAIN']['Sender'] = $user;
            $messagesDAO->setMessageRead($mensaje->getIdMessage(), $this->idUsuario);

            $destinatarios = $messagesDAO->getDestinatarios($mensaje->getIdMessage());
            $listaDestinatarios = array();
            foreach ($destinatarios as $destinatario) {
                $listaDestinatarios = $userDAO->get($destinatario);
            }
            $data['CONTENT_MAIN']['destinatarios'][] = $listaDestinatarios;
            $data['CONTENT_MAIN']['CURRENT_USER'] = $_SESSION['sessionObject'];

            return $data;
        } else {
            $this->errorPropiedad();
        }
    }

    /**
     * Obtiene la id de un usuario a partir de su mail o de su DNI/pasaporte
     * Devuelve false si no se encontró una id para los datos introducidos
     * @param string $mailRegex
     * @param string $userInputId
     * @return int or false
     */
    protected function getIdFromMailOrRealID($userInputId) {

        $userId = null;
        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $mailRegex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        if (preg_match($mailRegex, $userInputId)) {
            $userId = $userDAO->getIdUserFromMail($userInputId);
        } else {
            $userId = $userDAO->getIdUserFromRealId($userInputId);
        }
        unset($userDAO); //Forzar la eliminación del objeto por motivos de eficiencia
        return $userId;
    }

    /**
     * Genera los datos necesario para la vista de un usuario que no
     * ha iniciado sesión en el sistema.
     */
    protected function noLogin($params) {
        require (ROOT . 'PasswordManager.php');
        $this->undoChangeTime();
        if (count($params) == 0) {
            $data['CONTENT_MAIN']['NO_LOGIN'] = true;
        } else {
            $data['CONTENT_MAIN']['NO_LOGIN'] = true;
            $data['CONTENT_MAIN']['LOGIN_TRY'] = true;

            $userInputId = $params[0];
            $userInputPassword = $params[1];
            $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $userId = $this->getIdFromMailOrRealID($userInputId);


            if ($userId != false) {
                $hash = $userDAO->getPasswordHashFromUser($userId);
                $resultado = PasswordManager::verifyHash($userInputPassword, $hash);
            }

            if ($userId == false || $resultado == false) {
                $data['CONTENT_MAIN']['RESULT'] = false;
            } else {
                $user = $userDAO->get($userId);
                $data['CONTENT_MAIN']['RESULT'] = true;
                $_SESSION['login'] = 1;
                $_SESSION['sessionObject'] = new UserSession();
                $_SESSION['sessionObject']->setIdUsuario($userId);
                $_SESSION['sessionObject']->setRol($user->getRol());
                $_SESSION['sessionObject']->setAvatar($user->getImagenPerfil());
            }
        }

        return $data;
    }

    /**
     * Destruye la sesión de usuario actual
     */
    protected function logout() {
        $_SESSION['login'] = 0;
        unset($_SESSION['sessionObject']);
        $data['LOGOUT'] = true;
        $this->undoChangeTime();
        return $data;
    }

    /**
     * Registro de usuario
     */
    protected function register($params) {
        $data['REGISTER'] = true;

        if ($params[0]) {
            //Generador de contraseñas
            require (ROOT . 'PasswordManager.php');
            //Generador de captcha
            require (ROOT . 'lib/securimage/securimage.php');

            $userName = $params[1];
            $userLastname1 = $params[2];
            $userLastname2 = $params[3];
            $userID = $params[4];
            $userMail = $params[5];
            $userPassword = $params[6];
            $userPasswordRetype = $params[7];
            $userPhone = $params[8];

            $registroOK = true;
            $tam = strlen($userName);
            //Comprobación del captcha
            $securimage = new Securimage();
            if ($securimage->check($_POST['captcha_code']) == false) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'ERRORCAPTCHA';
                $registroOK = false;
            }
            if (!isset($userName) || ($tam > 100 || $tam == 0)) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'ERRORNICK';
                $registroOK = false;
            } else {
                $data['CONTENT_MAIN']['CORRECTDATA']['USERNAME'] = $userName;
            }
            $tam = strlen($userLastname1);
            if (!isset($userLastname1) || ($tam > 100 || $tam == 0)) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'USERLASTNAME1';
                $registroOK = false;
            } else {
                $data['CONTENT_MAIN']['CORRECTDATA']['USERLASTNAME1'] = $userLastname1;
            }
            $tam = strlen($userLastname2);
            if (!isset($userLastname2) || ($tam > 100)) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'USERLASTNAME2';
                $registroOK = false;
            } else {
                $data['CONTENT_MAIN']['CORRECTDATA']['USERLASTNAME2'] = $userLastname2;
            }
            $tam = strlen($userID);
            $idregexp = '/^[XYZ]?([0-9]{7,8})([A-Z])$/i';
            if (!isset($userID) || ($tam > 20 || $tam <= 7) || !preg_match($idregexp, $userID)) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'USERID';
                $registroOK = false;
            } else {
                $data['CONTENT_MAIN']['CORRECTDATA']['USERID'] = $userID;
            }

            $tam = strlen($userMail);
            $mailRegex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            $domainRestrictedRegex = $GLOBALS["GENERAL_MAIL_DOMAIN_RESTRICTION"];
            if (!isset($userMail) || ($tam > 255 || $tam == 0) || !preg_match($mailRegex, $userMail)) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'USERMAIL';
                $registroOK = false;
            } else {
                $data['CONTENT_MAIN']['CORRECTDATA']['USERMAIL'] = $userMail;
            }

            if (!isset($userMail) || ($tam > 255 || $tam == 0) || !preg_match($domainRestrictedRegex, $userMail)) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'USERDOMAINMAIL';
                $registroOK = false;
            } else {
                $data['CONTENT_MAIN']['CORRECTDATA']['USERMAIL'] = $userMail;
            }

            $tam = strlen($userPassword);
            if (!isset($userPassword) || ($tam > 100 || $tam <= 5)) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'USERPASSWORD';
                $registroOK = false;
            }
            $tam = strlen($userPasswordRetype);
            if (!isset($userPasswordRetype) || ($tam > 100 || $tam <= 5)) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'USERPASSWORDRETYPE';
                $registroOK = false;
            }
            $tam = strlen($userPhone);
            if (!isset($userPhone) || ($tam > 100 || $tam == 0)) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'USERPHONE';
                $registroOK = false;
            } else {
                $data['CONTENT_MAIN']['CORRECTDATA']['USERPHONE'] = $userPhone;
            }

            if ($userPasswordRetype != $userPassword) {
                $data['CONTENT_MAIN']['REGISTERERROR'][] = 'USER_PASSWORD_NOT_EQUAL';
                $registroOK = false;
            }

            if ($registroOK) {
                $userDao = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                $studentDAO = new StudentMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                $professorDAO = new ProfessorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);


                $hash = PasswordManager::hash($userPassword);
                $rolDefecto = 2; // Por defecto, todo usuario registrado es estudiante.
                $nuevoUsuario = new User(null, $hash, $userName, $userLastname1, $userLastname2, $userID, null, $userMail, $userPhone, $rolDefecto);

                $resInsercion = $userDao->insert($nuevoUsuario); // Resultado de la inserción                
                $errno = $userDao->getErroNo();
                if ($resInsercion > 0 && $errno == 0) {
                    //Se añaden todos los tipos de usuarios salvo el de administrador a la base de datos
                    $nuevoUsuario->setIdUsuario($resInsercion);
                    $studentDAO->insert($nuevoUsuario, true);
                    $professorDAO->insert($nuevoUsuario, true);

                    //

                    $data['CONTENT_MAIN']['REGISTER_SUCCESS'] = true;
                } else {

                    if ($errno == 1062) { // DUPLICATE ENTRY error                        
                        $data['CONTENT_MAIN']['REGISTERERROR'][] = 'DUPLICATE_ENTRY';
                    } else {
                        throw new Exception('Error 2: Error al registrar a un usuario.');
                    }
                    $data['CONTENT_MAIN']['REGISTER_SUCCESS'] = false;
                }
            } else {
                $data['CONTENT_MAIN']['REGISTER_SUCCESS'] = false;
            }
        }

        return $data;
    }

    protected function login_only($params) {

        $data = array();
        $competenceDao = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceTypesDao = new CompetenceTypeMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $evaluationReport = new EvaluationReport($GLOBALS["SYSTEM_CONNECTION"]);
        $conjuntoTiposAsignaturas = array();
        $filtro = false;
        $data['filter'] = false;

        if (isset($params[0])) {
            $filtro = true;
            $data['filter'] = $params[0];
        }

        $contador = 0;
        $listaCompetencias = array();

        if (isset($params['passed']) && $params['passed']) {
            $listaCompetencias = $this->getPassedCompetences($competenceDao);
            $data['VIEW_PASSED'] = true;
        } else {
            $listaCompetencias = $competenceDao->getCompetenciasMatriculadas($this->idUsuario);
            $data['VIEW_PASSED'] = false;
        }
        if ($listaCompetencias != false) {
            foreach ($listaCompetencias as $competencia) {
                $conjuntoTiposAsignaturas[] = $competencia->getIdType();
                if ($filtro && $params[0] == $competencia->getIdType() || !$filtro) {
                    $data['CONTENT_MAIN'][$contador]['name'] = $competencia->getName();
                    $data['CONTENT_MAIN'][$contador]['id'] = $competencia->getIdCompetencia();
                    $data['CONTENT_MAIN'][$contador]['descripcion'] = $competencia->getDescription();
                    $data['CONTENT_MAIN'][$contador]['type'] = $competencia->getIdType();
                    $data['CONTENT_MAIN'][$contador]['code'] = $competencia->getCode();
                    $data['CONTENT_MAIN'][$contador]['currentMean'] = $evaluationReport->getStudentEvaluationOnCompetence($this->idUsuario, $competencia->getIdCompetencia());
                    $data['CONTENT_MAIN'][$contador]['progress'] = $evaluationReport->getProgressOnCompetence($this->idUsuario, $competencia->getIdCompetencia()) * 100;
                    ++$contador;
                }
            }

            $conjuntoTipoAsignaturasUnique = array_unique($conjuntoTiposAsignaturas);
            foreach ($conjuntoTipoAsignaturasUnique as $key => $idTipo) {
                $data['TIPOS'][$idTipo] = $competenceTypesDao->get($conjuntoTipoAsignaturasUnique[$key]);
                if ($filtro && $data['TIPOS'][$idTipo]->getIdTypeCompetence() == $params[0]) {
                    $data['filterName'] = $data['TIPOS'][$idTipo]->getName();
                }
            }
        } else {
            $data['CONTENT_MAIN']['NO_COMPETENCES_AVAILABLE'] = true;
        }
        return $data;
    }

    protected function competenceInfo($params) {

        $data = array();

        if (count($params) == 1) {
            $idCompetence = $params[0];
            $competenceDao = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $competenciaSeleccionada = $competenceDao->get($idCompetence);
            $yearDAO = new CursoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

            if (isset($competenciaSeleccionada) && $competenciaSeleccionada != false) {
                $data['CONTENT_MAIN']['id'] = $competenciaSeleccionada->getIdCompetencia();
                $data['CONTENT_MAIN']['name'] = $competenciaSeleccionada->getName();
                $data['CONTENT_MAIN']['code'] = $competenciaSeleccionada->getCode();
                $data['CONTENT_MAIN']['description'] = $competenciaSeleccionada->getDescription();
                $year = $yearDAO->get($competenciaSeleccionada->getIdYear());

                $data['CONTENT_MAIN']['year'] = $year->getInitialYear() . '/' . $year->getFinalYear();
                $data['CONTENT_MAIN']['observations'] = $competenciaSeleccionada->getObservations();
                $data['CONTENT_MAIN']['type'] = $competenciaSeleccionada->getIdType();
                $data['IS_COMPETENCE'] = true;



                $indicadores = $competenciaSeleccionada->getIndicators();
                $indicadorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                $contador = 0;
                foreach ($indicadores as $idIndicador) {
                    $indicador = $indicadorDAO->get($idCompetence, $idIndicador);
                    $data['CONTENT_MAIN']['indicators'][$contador]['id'] = $idIndicador;
                    $data['CONTENT_MAIN']['indicators'][$contador]['name'] = $indicador->getName();
                    $data['CONTENT_MAIN']['indicators'][$contador]['code'] = $indicador->getCode();
                    ++$contador;
                }
            } else {
                $data['IS_COMPETENCE'] = false;
            }
        } else {
            $this->errorParametros();
        }
        return $data;
    }

    /**
     * Envia un mensaje a uno o varios usuarios
     * 
     * @return string
     * 
     */
    protected function sendMessage($params) {
        $data = array();
        $usuarioDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        //Para cada usuario, se obtenen las actividades en las que participa tanto como profesor como estudiante    
        $currentYear = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();

        $actividadesEstudiante = $activityDAO->getActivityByStudent($this->idUsuario, $currentYear);
        $actividadesProfesor = $activityDAO->getActivityByProfessor($this->idUsuario, $currentYear);
        $data['IS_GROUPS'] = false;
        $data['GROUPS'] = null;

        if ($actividadesEstudiante != false) {
            $data['IS_GROUPS'] = true;
            foreach ($actividadesEstudiante as $actividad) {
                $data['GROUPS'][] = $actividad;
            }
        }

        if ($actividadesProfesor != false) {
            $data['IS_GROUPS'] = true;
            foreach ($actividadesProfesor as $actividad) {
                $data['GROUPS'][] = $actividad;
            }
        }
        if (!isset($params['isSending'])) { // No se ha intentado enviar un mensaje
            $data['CONTENT_MAIN']['sendTry'] = false;
            if (isset($params['idRecipient'])) {
                $data['CONTENT_MAIN']['desiredDecipient'] = $usuarioDAO->get($params['idRecipient'])->getMail();
            }
        } else {
            $data['CONTENT_MAIN']['sendTry'] = true;
            $idDestinatarios = array(); // Array de destinatarios de un mensaje
            //Comprobación de los parámetros introducidos por el usuarios
            $destinatariosInput = $params['recipients'];
            $asunto = $params['subject'];
            $mensaje = $params['message'];

            $data['CONTENT_MAIN']['isError'] = false;
            if (strlen($destinatariosInput) == 0 && $params['type_message'] == 1) { // equivale a mensaje grupal, 1 equivale a mensaje individual
                $data['CONTENT_MAIN']['isError'] = true;
                $data['CONTENT_MAIN']['ERRORS']['recipients'] = true;
            } else {
                $destinatariosCorrectos = array();
                if ($params['type_message'] == 0) { // equivale a mensaje grupal, 1 equivale a mensaje individual
                    $idActivity = $params['idGroup'];
                    $listadoProfesores = $activityDAO->getProfessorsByActivty($idActivity, $currentYear);
                    $listadoEstudiantes = $activityDAO->getStudentsByActivity($idActivity, $currentYear);
                    $idDestinatarios = array_merge($listadoProfesores, $listadoEstudiantes);
                } else {
                    //Descomponer lisa de usuarios. Primero se normaliza (suprimiendo espacios) y luego se descompone en destinatarios
                    $destinatariosInputTrimmed = trim($destinatariosInput);
                    $destinatarios = explode(',', $destinatariosInputTrimmed);


                    foreach ($destinatarios as $destinatario) {
                        $idUser = $this->getIdFromMailOrRealID($destinatario);
                        if ($idUser != false) {
                            $idDestinatarios[] = $idUser;
                            $destinatariosCorrectos[] = $destinatario;
                        } else {
                            $data['CONTENT_MAIN']['isError'] = true;
                            $data['CONTENT_MAIN']['ERRORS']['recipients'] = true;
                            $data['CONTENT_MAIN']['ERRORS']['recipientsId'][] = $destinatario;
                        }
                    }
                }

                $data['CONTENT_MAIN']['recipient'] = $destinatariosCorrectos;
            }
            if (strlen($asunto) == 0) {
                $data['CONTENT_MAIN']['isError'] = true;
                $data['CONTENT_MAIN']['ERRORS']['subject'] = true;
            } else {
                $data['CONTENT_MAIN']['subject'] = $asunto;
            }

            if (strlen($mensaje) == 0) {
                $data['CONTENT_MAIN']['isError'] = true;
                $data['CONTENT_MAIN']['ERRORS']['message'] = true;
            } else {
                $data['CONTENT_MAIN']['message'] = $mensaje;
            }

            //Si todo va bien
            if (!isset($data['CONTENT_MAIN']['isError']) || !$data['CONTENT_MAIN']['isError']) {
                $messagesDAO = new MessageMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                $idDestinatarios = array_unique($idDestinatarios);

                foreach ($idDestinatarios as $idDestinatario) {
                    $idInsercion = array($idDestinatario);
                    if (!$messagesDAO->sendMessage($this->idUsuario, $idInsercion, $asunto, $mensaje)) {
                        $data['CONTENT_ERROR'] = 'Error al enviar el mensaje';
                        throw new Exception('Error al enviar el mensaje', 4);
                    }
                }
            }
        }
        return $data;
    }

    protected function professorViewOnly($params) {
        $data = array();
        $actividadesDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $activityCategoryDAO=  new ActividadCategoriaMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        
        $data['ACTIVITIES_CATEGORY'] = $activityCategoryDAO->getAllOrderedBy('nombre');
        
        $actividades = $actividadesDAO->getActivityByProfessor(
                $this->idUsuario, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear()
        );

        if (count($actividades) != 0) {

            foreach ($actividades as $actividad) {
                $datosActividad = $actividadesDAO->get($actividad);
                $data['CONTENT_MAIN'][] = $datosActividad;
            }
            $data['no_activities'] = false;
        } else {
            $data['no_activities'] = true;
        }

        return $data;
    }

    /**
     * Muestra una actividad relacionada con un profesor
     * Requiere
     * $params[0] = idActividad
     * @param int[] $params
     */
    protected function professorActivityView($params) {
        $data = array();
        $actividadDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        //Primero se comprueba, a partir de la sesión actual, si el profesor 
        //tiene acceso a evaluar la actividad especificada.

        if ($actividadDAO->isActivityLinkedByProfessor($params[0], $this->idUsuario)) {

            $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $actividad = $actividadDAO->get($params[0]);
            $data['is_activity_available'] = true;

            $data['CONTENT_MAIN']['activity'] = $actividad;

            $listaCompetencias = $competenceDAO->getCompetencesByActivity($actividad->getIdActividad());

            if (count($listaCompetencias) > 0) {
                $data['CONTENT_MAIN']['isCompetencesAvailables'] = true;
                $data['CONTENT_MAIN']['competences'] = $listaCompetencias;
            } else {
                $data['CONTENT_MAIN']['isCompetencesAvailables'] = false;
            }
        } else {
            $data['is_activity_available'] = false;
        }
        return $data;
    }

    /**
     * Muestra la vista de evaluación de una competencia
     * 
     * Requiere: $params[0] = idCompetencia
     *           $params[1] = idActividad
     *           $params[2] = idYear (opcional)
     * @param int[] $params
     */
    protected function professorCompetenceEval($params) {

        $data = array();
        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $studentDAO = new StudentMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $sessionDAO = new SessionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $evaluacionDAO = new EvaluacionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        $data['CONTENT_MAIN']['competenceId'] = $params[0];
        $data['CONTENT_MAIN']['competenceName'] = $competenceDAO->get($params[0])->getName();

        $idYear = null;

        if (isset($params[2])) {
            $idYear = $params[2];
        } else {
            $idYear = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();
        }

        if (!isset($_SESSION['sessionObject'])) {
            $data['ERROR_CONTENT']['notAllowed'] = true;
            return $data;
        }
        //Se comprueba si idCompetencia forma parte de la lista de competencias asociadas a una actividad
        //Esto es necesario para evitar que se evaluen a estudiantes de competencias de actividades diferentes.
        if ($activityDAO->isActivityLinkedyByCompetence($params[1], $params[0])) {
            $listaEstudiantes = $activityDAO->getStudentsByActivity($params[1], $idYear);
            $listaIndicadores = $indicatorDAO->getIndicatorByCompetenceId($params[0]);


            $idSession = null;
            if (isset($params['evaluationInput']['competenceEvalSelectedSession'])) {
                $idSession = $params['evaluationInput']['competenceEvalSelectedSession'];
            }

            if (!isset($idSession)) {
                $firstSession = $sessionDAO->getFirstSession($params[1]);
                if (isset($firstSession) && $firstSession != false) {
                    $idSession = $firstSession->getIdSession();
                }
            }
            $data['CONTENT_MAIN']['selectedSession'] = $idSession;

            //Aquí se comprueba si se mandó una evaluación
            if ($params['evaluationSent']) {

                if (isset($params['evaluationInput']['competenceEvalSelectedSession'])) {


                    /**
                     * Guardamos las claves del array, necesario para obtener
                     * los estudiantes y el indicado evaluado, cada clave tiene
                     * el siguiente formato
                     * 
                     * 21_2 -> el estudiante id 21 tiene una evaluación en el indicador id 2
                     * 
                     * de manera que en el array $params['evaluationSent']
                     * $params['evaluationSent']['21_2'] = 2 
                     * 
                     * userid 21 tiene en idIndicador 2 la evaluación de 2
                     * 
                     * El primer elemento del array y el último no forman parte
                     * de los indicadores de evaluación
                     * 
                     */
                    $claves = array_keys($params['evaluationInput']);

                    $tamClaves = count($claves) - 1; //-1 para evitar la última clave

                    for ($index = 4; $index < $tamClaves; $index++) { //$index=5 para evitar los primeros elementos del array, ajenos a la evaluación de competencias
                        if (is_string($claves[$index])) {
                            $cadena = $claves[$index];
                            $cadenaSeparada = explode('_', $cadena);
                            if (isset($cadenaSeparada[1])) {
                                $idStudent = $cadenaSeparada[0];
                                $idIndicator = $cadenaSeparada[1];
                                $calificacion = $params['evaluationInput'][$cadena];

                                //Si la calificación es -1, no se realiza evaluación
                                if ($calificacion != -1) {
                                    $evaluacion = new Evaluacion(
                                            null, $calificacion, null, $idStudent, $params[1], $idSession, $params[0], $idIndicator, $this->idUsuario, $idYear
                                    );

                                    $res = $evaluacionDAO->insert($evaluacion);
                                }
                            }
                        }
                    }
                }
            }
            /**
             * EVALUACIÓN APLICADA A TODO EL GRUPO
             */ else if ($params['evaluationSentAll']) {

                $calificacion = $params['evaluationInput']['calification'];
                $filtro = $params['evaluationInput']['filter'];
                $idIndicator = $params['evaluationInput']['onIndicator'];

                //Almacena la lista de estudiantes a los que se le aplicará la calificación              
                //$listaEstudiantes contiene id de Estudiantes, no objetos Estudiante   
                $listaEstudiantesValida = array();
                if ($filtro == 1) { //Si solo se aplica a los que han asistido
                    foreach ($listaEstudiantes as $estudiante) {
                        if ($sessionDAO->isStudentAssisted($estudiante, $params[1], $idSession) == 1) {
                            $listaEstudiantesValida[] = $estudiante;
                        }
                    }
                } else if ($filtro == 2) { //Aplica solo a estudiantes seleccionados
                    if (isset($params['studentMark'])) {
                        $listaEstudiantesValida = $params['studentMark'];
                    } else {
                        $listaEstudiantesValida = array();
                    }
                } else { //Se aplica a todos los estudiantes
                    $listaEstudiantesValida = $listaEstudiantes;
                }


                //Una vez filtrado, se procede a evaluar
                if (count($listaEstudiantesValida) > 0) {
                    $evaluacion = null;

                    foreach ($listaEstudiantesValida as $estudianteValido) {
                        //Si se aplica a todos los indicadores...
                        if ($idIndicator != -1) {


                            $evaluacion = new Evaluacion(
                                    null, $calificacion, null, $estudianteValido, //id de estudiante
                                    $params[1], $idSession, $params[0], $idIndicator, //id del indicador
                                    $this->idUsuario, $idYear
                            );
                            $res = $evaluacionDAO->insert($evaluacion);
                        } else {

                            foreach ($listaIndicadores as $indicador) {
                                $evaluacion = new Evaluacion(
                                        null, $calificacion, null, $estudianteValido, //id de estudiante
                                        $params[1], $idSession, $params[0], $indicador->getIdIndicator(), //Es un objeto indicator
                                        $this->idUsuario, $idYear
                                );
                                $res = $evaluacionDAO->insert($evaluacion);
                            }
                        }
                    }
                }
            }

            $data['CONTENT_MAIN']['activityId'] = $params[1];
            $data['CONTENT_MAIN']['activityName'] = $activityDAO->get($params[1])->getNombre();
            //Aquí se visualizan las sesiones existentes
            $data['NO_COMPETENCE_ASSOCIATED'] = false;
            $listaSesiones = $sessionDAO->getSessionByActivity($params[1]);


            if ($listaEstudiantes != false && count($listaEstudiantes) > 0) {

                $data['NO_STUDENTS'] = false;
                if ($listaIndicadores != false && count($listaIndicadores) > 0) {

                    $data['NO_INDICATORS'] = false;
                    if ($listaSesiones != false && count($listaSesiones) > 0) {
                        $data['NO_SESSIONS'] = false;


                        $data['CONTENT_MAIN']['indicators'] = $listaIndicadores;
                        $data['CONTENT_MAIN']['sessions'] = $listaSesiones;
                        $data['CONTENT_MAIN']['students'] = array();
                        $data['CONTENT_MAIN']['evaluation'] = array();


                        $evaluacion = null;

                        foreach ($listaEstudiantes as $idEstudiante) {
                            $data['CONTENT_MAIN']['students'][] = $studentDAO->get($idEstudiante);
                        }

                        $contadorEstudiantes = count($listaEstudiantes);
                        $contadorIndicadores = count($listaIndicadores);



                        for ($contEst = 0; $contEst < $contadorEstudiantes; $contEst++) {
                            for ($contInd = 0; $contInd < $contadorIndicadores; $contInd++) {

                                $idUser = $data['CONTENT_MAIN']['students'][$contEst]->getIdUsuario();
                                $idIndicator = $data['CONTENT_MAIN']['indicators'][$contInd]->getIdIndicator();

                                $evaluacionNumerica = $evaluacionDAO->getEvaluationByIndicator
                                        (
                                        $params[0], $idIndicator, $idUser, $idSession, $idYear, $params[1]
                                );
                                if (isset($evaluacionNumerica[0])) {
                                    $evaluacion[$idUser] [$idIndicator] = $evaluacionNumerica[0]->getEvaluacion();
                                } else {
                                    $evaluacion[$idUser] [$idIndicator] = false;
                                }
                            }
                        }

                        $data['CONTENT_MAIN']['evaluation'] = $evaluacion;
                    } else {
                        $data['NO_SESSIONS'] = true;
                    }
                } else {
                    $data['NO_INDICATORS'] = true;
                }
            } else {
                $data['NO_STUDENTS'] = true;
            }
        } else {
            $data['NO_COMPETENCE_ASSOCIATED'] = true;
        }
        return $data;
    }

    /**
     * Genera el modelo correspondiente a las sesiones existentes para una actividad determinada
     * $params[0] es el identificador de la actividad
     * @param int[] $params
     */
    public function professor_sessions_list($params) {
        $data = array();
        $actividadDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $sessionDAO = new SessionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $LugaresDAO = new LugarMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);



        $idNuevaSesion = $sessionDAO->getMaxIdSession($params[0]) + 1;
        if (isset($params['remove'])) {
            $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $usuario = $userDAO->get($this->idUsuario);

            if ($actividadDAO->isProfessorLinkedtoActivity($params[0], $this->idUsuario) || $usuario->getRol() == 4) {

                $sessionDAO->delete($params[0], $params['remove']);
            }
        } else if (isset($params[1])) {
            $isError = false;

            if (strlen($params[2]) == 0) {
                $data['INSERT_ERROR']['dateStart'] = 1;
                $isError = true;
            } else {

                $data['INSERT_ERROR']['dateStart'] = $params[2];
            }

            if (strlen($params[3]) == 0 || $params[3] < 0 || $params[3] > 24) {
                $data['INSERT_ERROR']['dateStartHour'] = 1;
                $isError = true;
            } else {
                $data['INSERT_ERROR']['dateStartHour'] = $params[3];
            }
            if (strlen($params[4]) == 0 || $params[4] < 0 || $params[4] > 59) {
                $data['INSERT_ERROR']['dateStartMinute'] = 1;
                $isError = true;
            } else {
                $data['INSERT_ERROR']['dateStartMinute'] = $params[4];
            }
            if (strlen($params[5]) == 0 || $params[5] < 0) {
                $data['INSERT_ERROR']['dateLength'] = 1;
                $isError = true;
            } else {
                $data['INSERT_ERROR']['dateLength'] = $params[5];
            }

            if (!$isError) {

                $dateStart = $params[2] . ' ' . $params[3] . ':' . $params[4] . ':' . '00';
                $timestampStart = strtotime($dateStart);
                $timestampEnd = $timestampStart + (60 * $params[5]);
                $session = new Session(
                        $params[0], $idNuevaSesion, date('Y-m-d H:i:s', $timestampStart), date('Y-m-d H:i:s', $timestampEnd), $params[6], $params[7]
                );
                $res = $sessionDAO->insert($session);

                if ($res != false) {
                    $data['insertionCorrect'] = true;
                } else {

                    $data['insertionCorrect'] = false;
                }
                $data['CONTENT_ERROR'] = false;
            } else {
                $data['CONTENT_ERROR'] = true;
                $data['insertionCorrect'] = false;
            }
        }

        $sesiones = $sessionDAO->getSessionByActivity($params[0]);

        $listaLugares = $LugaresDAO->getAllByCentre($GLOBALS["SYSTEM_CENTRE"] = 1);
        if ($listaLugares != false && count($listaLugares) > 0) {

            $data['CONTENT_MAIN']['isSessions'] = true;
            $data['CONTENT_MAIN']['sessions'] = $sesiones;
            foreach ($listaLugares as $lugar) {
                $data['CONTENT_MAIN']['places'][$lugar->getIdLugar()] = $lugar->getNombre();
            }
        } else {
            $data['CONTENT_MAIN']['places'] = false;
        }

        $activity = $actividadDAO->get($params[0]);
        $data['activityID'] = $params[0];
        $data['activityNAME'] = $activity->getNombre();
        $data['activityCODE'] = $activity->getCodigo();
        return $data;
    }

    /**
     * Genera el modelo correspondiente al listado de estudiantes para una actividad
     * $params[0] es el identificador de la actividd
     * @param int[] $params
     */
    public function professor_students_list($params) {
        $data = array();
        $studentDAO = new StudentMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $actividadDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        //Se comprueba si el profesor está capacitado para administrar la lista de estudiantes de la actividad
        if (!$actividadDAO->isProfessorLinkedtoActivity($params[0], $this->idUsuario)) {
            $data['NO_ENOUGH_PRIVILEGES'] = true;
            return $data;
        }
        $data['NO_ENOUGH_PRIVILEGES'] = false;

        if (isset($params['remove'])) {
            $actividadDAO->removeStudentOfActivity($params[0], $params['remove'], $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());
        } else if (isset($params[1])) {
            $isError = false;

            $userID = $this->getIdFromMailOrRealID($params[2]);
            if ($userID == false) {
                $isError = true;
            }

            if (!$isError) {
                $res = $actividadDAO->insertStudentInActivity($params[0], $userID, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());
            }



            if (isset($res) && $res != false) {
                $data['insertionCorrect'] = true;
            } else {
                $data['insertionCorrect'] = false;
            }
        }

        $listaEstudiantes = $actividadDAO->getStudentsByActivity($params[0], $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());
        $contadorEstudiantes = count($listaEstudiantes);
        if ($contadorEstudiantes > 0 && $listaEstudiantes != false) {
            $data['CONTENT_MAIN']['isStudents'] = true;
            foreach ($listaEstudiantes as $idEstudiante) {
                $data['CONTENT_MAIN']['students'][] = $studentDAO->get($idEstudiante);
            }
        } else {
            $data['CONTENT_MAIN']['isSessions'] = false;
            $data['CONTENT_MAIN']['isStudents'] = false;
        }

        $activity = $actividadDAO->get($params[0]);
        $data['activityID'] = $params[0];
        $data['activityNAME'] = $activity->getNombre();
        $data['activityCODE'] = $activity->getCodigo();
        return $data;
    }

    protected function adminView($params) {
        $data = array();
        $notificationDAO = new ReportMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        if (isset($params['solve'])) {
            $notification = $notificationDAO->get($params['solve']);
            if ($notification != false && $notification->getStatus() == 0) {
                $notificationDAO->checkSolved($params['solve']);
            } else {
                $notificationDAO->checkSolved($params['solve'], 1);
            }
        }

        if (isset($params['delete'])) {

            $notificationDAO->delete($params['delete']);
        }

        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $notificaciones = $notificationDAO->getAll();



        $listaUsers = array();
        if ($notificaciones != false && count($notificaciones) > 0) {
            foreach ($notificaciones as $notification) {
                $user = $userDAO->get($notification->getIdSender());
                $listaUsers[$notification->getIdSender()] = $user->getNombre() . ' ' . $user->getApellido1() . ' ' . $user->getApellido2();
            }
            $data['CONTENT_MAIN']['notifications'] = $notificaciones;
        } else {
            $data['CONTENT_MAIN']['notifications'] = false;
        }
        $data['CONTENT_MAIN']['users'] = $listaUsers;
        return $data;
    }

    /**
     * Muestra la lista de actividades asociadas a un estudiante
     * @param type $params
     */
    protected function student_view_activities($params) {
        $data = array();
        $idYear = null;


        if (isset($params[0])) {
            $idYear = $params[0];
        } else {
            $idYear = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();
        }


        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $actividades = $activityDAO->getActivityByStudent($this->idUsuario, $idYear);

        if (count($actividades) > 0 && $actividades != false) {
            $data['CONTENT_MAIN']['activities_available'] = true;
        } else {
            $data['CONTENT_MAIN']['activities_available'] = false;
        }

        $data['CONTENT_MAIN']['activities'] = $actividades;

        return $data;
    }

    /**
     * Muestra información sobre una actividad
     * @param type $params
     */
    protected function student_view_activity($params) {
        $data = array();
        if ($params[0] != false) {
            $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $professorDAO = new ProfessorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);


            $actividad = $activityDAO->get($params[0]);
            $idProfessors = $activityDAO->getProfessorsByActivty($params[0], $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());
            $competencias = $competenceDAO->getCompetencesByActivity($params[0]);


            if ($actividad != false) {
                $data['CONTENT_MAIN']['activity'] = $actividad;

                if (count($competencias) > 0) {
                    $data['CONTENT_MAIN']['competence'] = $competencias;
                    if ($idProfessors != false && count($idProfessors) > 0) {
                        foreach ($idProfessors as $idProfessor) {
                            $data['CONTENT_MAIN']['professors'][] = $professorDAO->get($idProfessor);
                        }
                    } else {
                        $data['CONTENT_MAIN']['professors'] = false;
                    }
                } else {
                    $data['CONTENT_MAIN']['competence'] = false;
                }
            } else {
                $data['CONTENT_MAIN']['activity'] = false;
            }
        } else {
            $data['CONTENT_MAIN']['activity'] = false;
        }

        return $data;
    }

    protected function student_view_activitiesAssistance($params) {
        $data = array();
        $sessionDAO = new SessionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $professorDAO = new ProfessorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $sessions = $sessionDAO->getSessionByActivity($params[0]);
        $activity = $activityDAO->get($params[0]);


        $asistenciasRegistradas = array();
        if (isset($params[1])) { //Check session
            $session = $sessionDAO->get($params[0], $params[1]);
            $data['CHECK_TRY'] = true;
            if (strcmp($session->getPassword(), $params[2]) == 0) {
                if ($sessionDAO->checkSession($this->idUsuario, $params[0], $params[1], 1) != false) {
                    $data['TRY'] = true;
                } else {
                    $data['TRY'] = false;
                }
            } else {
                $data['TRY'] = false;
            }
        }

        if (count($sessions) > 0) {


            $data['SESSIONS'] = $sessions;
            $data['ACTIVITY_ID'] = $params[0];
            $data['ACTIVITY_NAME'] = $activity->getNombre();
            $data['ACTIVITY_CODE'] = $activity->getCodigo();
            
       
            
            if (isset($data['SESSIONS']) && $data['SESSIONS'] != false) {
                foreach ($data['SESSIONS'] as $session) {
                    $isAssisted = $sessionDAO->isStudentAssisted($this->idUsuario, $params[0], $session->getIdSession());
                    $data['SESSIONS_ASSISTANCE']['idSession'][] = $session->getIdSession();
                    $data['SESSIONS_ASSISTANCE']['isAssisted'][] = $isAssisted;

                    $timeInicial = strtotime($session->getDateStart());
                    $timeFinal = strtotime($session->getDateEnd());
                    $time = time();

                    if ($isAssisted == false) {
                        if ($timeInicial <= $time && $timeFinal >= $time) {
                            $data['CURRENT_SESSIONS'][] = $session;
                        }
                    }
                }
            }

            $idProfessors = $activityDAO->getProfessorsByActivty($params[0], $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());
            if ($idProfessors != false && count($idProfessors) > 0) {
                foreach ($idProfessors as $idProfessor) {
                    $data['CONTENT_MAIN']['professors'][] = $professorDAO->get($idProfessor);
                }
            } else {
                $data['CONTENT_MAIN']['professors'] = false;
            }
        } else {
            $data['SESSIONS'] = false;
        }
        return $data;
    }

    /**
     * Muestra el perfil de un usuario
     * Requiere $params[0] = identificador del usuario a mostrar
     * @param int[] $params
     */
    protected function view_user_profile($params) {
        $data = array();
        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $usuario = $userDAO->get($params[0]);
        $data['IS_USER'] = false;
        if ($usuario != false) {
            $data['IS_USER'] = true;
            $data['CONTENT_MAIN'] = $usuario;
        }
        return $data;
    }

    /**
     * Muestra la tabla de evaluación de un usuario
     * Requiere $params[0] = identificador de la actividad
     * @param int[] $params
     * @return string data
     */
    protected function view_professor_student_eval($params) {
        $data = array();

        $actitivtyDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);


        //En primer lugar, se comprueban los permisos de acceso
        $isProfessorAllowed = $actitivtyDAO->isActivityLinkedByProfessor($params[0], $this->idUsuario);

        if ($isProfessorAllowed) {

            $data['IS_ALLOWED'] = true;

            /**
             * Para mostra la tabla de evaluación, necesitaremos:
             *  1 -> Competencias de la actividad
             *  2 -> Para cada competencia, indicadores de la competencia
             *  3 -> Estudiantes matriculados en la actividad
             *  4 -> Evaluación, por indicador, de cada estudiante             * 
             *  5 -> Las sesiones prácticas
             */
            $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $studentDAO = new StudentMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $evaluacionDAO = new EvaluacionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $sessionDAO = new SessionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $evaluationReport = new EvaluationReport($GLOBALS["SYSTEM_CONNECTION"]);

            //También es necesario el año sobre el que se aplica la evaluación
            $idYear = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();

            //Se obtiene una lista con todas las competencias asociadas a una actividad

            $listaCompetencias = $competenceDAO->getCompetencesByActivity($params[0]); //$params[0] identifica a la actividad
            //Para cada competencia, se obtiene una lista de indicadores

            $listaIndicadores;
            foreach ($listaCompetencias as $competencia) {
                $idCompetencia = $competencia->getIdCompetencia();
                $listaIndicadores[$idCompetencia] = $indicatorDAO->getIndicatorByCompetenceId($idCompetencia);
            }

            //Ahora obtenemos a los estudiantes matriculados en la actividad

            $listaIdStudents = $actitivtyDAO->getStudentsByActivity($params[0], $idYear); //$params[0] identifica a la actividad
            //Esto solo obtiene una lista de identificadores de estudiantes, pero necesitamos todos los objetos Student

            $listaStudents = array();
            foreach ($listaIdStudents as $idStudent) {
                $listaStudents[] = $studentDAO->get($idStudent);
            }

            //Para cada estudiante, se obtiene la evaluación de cada competencia

            $listaEvaluacion = array();
            foreach ($listaIdStudents as $idStudent) {
                foreach ($listaCompetencias as $competencia) {
                    //$listaIndicadores almacenaba un array con indicadores de cada competencia
                    if (is_array($listaIndicadores[$competencia->getIdCompetencia()])) {
                        foreach ($listaIndicadores[$competencia->getIdCompetencia()] as $indicador) {
                            /**
                             * Hay que tener en cuenta que getLastEvaluationByIndicator obtiene el histórico de la evaluación
                             * de un estudiante en cada sesión.
                             */
                            $historico = $evaluacionDAO->getLastEvaluationByIndicator(
                                    $competencia->getIdCompetencia(), $indicador->getIdIndicator(), $idStudent, $idYear, $params[0]
                            );

                            $media = $evaluationReport->getStudentEvaluationOnIndicator($idStudent, $competencia->getIdCompetencia(), $indicador->getIdIndicator());
//                            $media = $evaluacionDAO->getMeanLastEvaluationByIndicator(
//                                    $competencia->getIdCompetencia(), $indicador->getIdIndicator(), $idStudent, $idYear, $params[0]
//                            );

                            $max = $evaluacionDAO->getMaxLastEvaluationByIndicator(
                                    $competencia->getIdCompetencia(), $indicador->getIdIndicator(), $idStudent, $idYear, $params[0]
                            );

                            $listaEvaluacion[$idStudent][$competencia->getIdCompetencia()][$indicador->getIdIndicator()] = $historico;
                            $listaEvaluacion[$idStudent]['MEAN'][$competencia->getIdCompetencia()][$indicador->getIdIndicator()] = $media;
                            $listaEvaluacion[$idStudent]['MAX'][$competencia->getIdCompetencia()][$indicador->getIdIndicator()] = $max;
                        }
                    }
                }
            }

            //Obtenemos el listado de sesiones

            $listaSesiones = $sessionDAO->getSessionByActivity($params[0]);

            /**
             * La lista de sesiones, tal cual, no nos sirve ya que necesitamos un modo de recuperar,
             * rápidamente, una sesión a través de su ID, así que necesitamos la siguiente estructura
             * 
             * array[idSession] = dateSession
             */
            $arraySession = array();
            if ($listaSesiones != false && isset($listaSesiones)) {
                foreach ($listaSesiones as $session) {
                    $arraySession[$session->getIdSession()] = $session->getDateStart();
                }
            }


            //Ya tenemos todo lo necesario para enviar a la vista
            $data['NO_DATA'] = false;
            if ($listaEvaluacion == false || count($listaEvaluacion) == 0) {
                $data['NO_DATA'] = true;
            }
            $data['evaluation'] = $listaEvaluacion;

            if ($listaStudents == false || count($listaStudents) == 0) {
                $data['NO_DATA'] = true;
            }
            $data['students'] = $listaStudents;

            if ($listaIndicadores == false || count($listaIndicadores) == 0) {
                $data['NO_DATA'] = true;
            }
            $data['indicators'] = $listaIndicadores;

            if ($arraySession == false || count($arraySession) == 0) {
                $data['NO_DATA'] = true;
            }
            $data['sessions'] = $arraySession;

            if ($listaCompetencias == false || count($listaCompetencias) == 0) {
                $data['NO_DATA'] = true;
            }
            $data['competences'] = $listaCompetencias;


            $activity = $actitivtyDAO->get($params[0]);
            $data['idActivity'] = $params[0];
            $data['nameActivity'] = $activity->getNombre();
            //Para ahorrar memoria, podemos eliminar algunos objetos

            unset($listaIdStudents);
            unset($listaSesiones);
        } else {
            $data['IS_ALLOWED'] = false;
        }


        return $data;
    }

    /**
     * Comprueba el listado de competencias a las que el profesor tiene acceso
     * @param type $params
     * @return array
     */
    protected function professor_competence_view($params) {
        $data = array();
        //En primer lugar, necesitamos ver las actividades e indicadoresque tutoriza un profesor
        $actividadDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        //También vamos a necesitas un DAO de competencias, para poder recuperarlas
        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        //Con la siguiente línea obtenemos el año actual
        $idYear = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();
        //Obtenemos el listado de Actividades del profesor.   getActivityByProfessor recupera un array con identificadores de actividad      
        $listaIdActividades = $actividadDAO->getActivityByProfessor($this->idUsuario, $idYear);

        //Guardamos el listado de actividades tras obtener los objetos Activity
        $listaActividades = null;
        if ($listaIdActividades != false && count($listaIdActividades) > 0) {
            foreach ($listaIdActividades as $idActividad) {
                $listaActividades[] = $actividadDAO->get($idActividad);
            }
        }

        $data['activities'] = $listaActividades;

        if ($listaIdActividades != false && count($listaIdActividades) > 0) {
            $data['NO_ACTIVITIES'] = false;
            foreach ($listaIdActividades as $idActividad) {

                //Obtenemos el listado de competencias
                $listaCompetencias = $competenceDAO->getCompetencesByActivity($idActividad);
                $data['competences'][$idActividad] = $listaCompetencias;
                if (is_array($listaCompetencias)) {
                    foreach ($listaCompetencias as $competencia) {
                        //Hacemos lo mismo para los indicadores
                        $listaIndicadores = $indicatorDAO->getIndicatorByCompetenceId($competencia->getIdCompetencia());

                        $data['indicators'][$competencia->getIdCompetencia()] = $listaIndicadores;
                    }
                }
            }
        } else {
            $data['NO_ACTIVITIES'] = true;
        }

        return $data;
    }

    /**
     * Carga información sobre una competencia
     * requiere params[0] = identificador de la vista a ragar
     * @param int[] $params
     * 
     */
    protected function view_user_viewCompetence($params) {
        $data = array();
        //En primer lugar, necesitaremos indicadores y competencias
        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        $competencia = $competenceDAO->get($params[0]);

        if (isset($competencia) && $competencia != false) {
            $data['COMPETENCE_NOT_EXISTS'] = false;
            $data['COMPETENCE'] = $competencia;

            $listadoIndicadores = $indicatorDAO->getIndicatorByCompetenceId($competencia->getIdCompetencia());
            if (count($listadoIndicadores) > 0) {
                $data['INDICATORS'] = $listadoIndicadores;
            } else {
                $data['INDICATORS'] = false;
            }
        } else {
            $data['COMPETENCE_NOT_EXISTS'] = true;
        }
        return $data;
    }

    /**
     * Carga la vista para el formulario de contacto
     * @param string[] $params
     */
    protected function view_user_contact($params) {
        $data = Array();

        //Comprobamos que el usuario ha iniciado sesión

        if (isset($_SESSION['login']) && $_SESSION['login']) {
            $data['USER_LOGIN'] = true;

            //$params[0] indica que se enviaron datos
            if ($params['check']) {
                $conditions = array(
                    0 => 1,
                    1 => 255,
                    2 => 5048
                );
                $array_validado = $this->checkFormulario($params, $conditions);

                $data['ERROR_TEXT'] = false;
                //Aunque el mensaje escrito por el usuario no valide, se añade
                if ($array_validado['CONTACT_TEXT'] == false) {
                    $data['ERROR_TEXT'] = true;
                    $array_validado['CONTACT_TEXT'] = substr($params['CONTACT_TEXT'], 0, 5047);
                }

                $esCorrecto = true;

                foreach ($array_validado as $validacion) {
                    if ($validacion == false) {
                        $esCorrecto = false;
                    }
                }

                if ($esCorrecto) {

                    $reportDAO = new ReportMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                    $reporte = new Report(0, $params['CONTACT_SUBJECT'], $params['CONTACT_TEXT'], date($GLOBALS["SYSTEM_DATABASE_TIMEFORMAT"]), $this->idUsuario, 0, $params['CONTACT_TYPE_NOTIFICATION'] - 1);
                    $reportDAO->insert($reporte);
                    $data['SENT_OK'] = true;
                } else {
                    $data['SENT_OK'] = false;
                    $data['VALIDATION'] = $array_validado;
                }
            }
        } else {
            $data['USER_LOGIN'] = false;
        }
        return $data;
    }

    /**
     * Carga el modelo necesario para ver la lista de asistencia a una actividad
     * Carga dos listas, una de presentados, y otra de no presentados
     * @param type $params
     */
    protected function professor_sessions_assistance($params) {
        $data = array();
        //Necesitamos cargar información sobre estudiantes, sesiones y actividades
        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $sessionDAO = new SessionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $studentDAO = new StudentMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        //Comprobamos si el profesor tiene permisos
        //$params[0] representa la actividad
        if ($activityDAO->isActivityLinkedByProfessor($params[0], $this->idUsuario)) {
            $data['NO_PRIVILEGES'] = false;

            //Si no se especificó sesión, suponemos la primera

            $idSession = null;
            if (!isset($params[1])) {
                $firstSession = $sessionDAO->getFirstSession($params[0]);
                if (isset($firstSession) && $firstSession != false) {
                    $idSession = $firstSession->getIdSession();
                }
            } else {
                $idSession = $params[1];
            }

            //Si existe $params[2], este contiene idStudiante y representa una solicitud de cambio de asistencia
            if (isset($params[2]) && $params[2]) {
                $assisted = $sessionDAO->isStudentAssisted($params[2], $params[0], $idSession);
                if ($assisted) {

                    $sessionDAO->checkSession($params[2], $params[0], $idSession, -1);
                } else {

                    $sessionDAO->checkSession($params[2], $params[0], $idSession, 1);
                }
            }

            $listaIdEstudiantes = $activityDAO->getStudentsByActivity($params[0], $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());

            $listaIdStudentAsisten = array();
            $listaIdStudentNoAsisten = array();

            if (isset($idSession) && is_array($listaIdEstudiantes)) {
                foreach ($listaIdEstudiantes as $idStudent) {
                    if ($sessionDAO->isStudentAssisted($idStudent, $params[0], $idSession)) {
                        $listaIdStudentAsisten[] = $idStudent;
                    } else {
                        $listaIdStudentNoAsisten[] = $idStudent;
                    }
                }



                $listaStudentAsisten = array();
                //Ahora recuperamos la información de cada estudiante
                foreach ($listaIdStudentAsisten as $idStudent) {
                    $listaStudentAsisten[] = $studentDAO->get($idStudent);
                }

                $listaStudentNoAsisten = array();
                foreach ($listaIdStudentNoAsisten as $idStudent) {
                    $listaStudentNoAsisten[] = $studentDAO->get($idStudent);
                }
                $data['STUDENTS_ASSISTED'] = $listaStudentAsisten;
                $data['STUDENTS_NOASSISTED'] = $listaStudentNoAsisten;
            } else {
                $data['STUDENTS_ASSISTED'] = false;
                $data['STUDENTS_NOASSISTED'] = false;
            }
            $actividad = $activityDAO->get($params[0]);



            $data['SESSIONS'] = $sessionDAO->getSessionByActivity($params[0]);

            $data['ACTIVITY_ID'] = $params[0];
            $data['ACTIVITY_NAME'] = $actividad->getNombre();
            $data['ACTIVITY_CODE'] = $actividad->getCodigo();
            $data['SESSIONS_ID'] = $idSession;


            //Borramos listas que ya no son necesarias            
            unset($listaIdStudentAsisten);
            unset($listaIdStudentNoAsisten);
            unset($listaIdEstudiantes);
        } else {
            $data['NO_PRIVILEGES'] = true;
        }
        return $data;
    }

    /**
     * Cambia el rol de un usuario y establece el cambio en la base de datos
     * como rol por defecto de un usuario.
     * 
     * si params[0]
     *                  1 = cambio a rol de estudiante
     *                  2 = cambio a rol de profesor
     *                  3 = cambio a rol de administrador
     * 
     * @param int $params
     */
    protected function userChangeRol($params) {
        $data = array();

        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        $newRol = $params[0] + 1;
        $userDAO->isStudentChangeRol($this->idUsuario, $newRol);

        if ($userDAO->isStudentChangeRol($this->idUsuario, $newRol)) {
            $userDAO->changeUserRol($this->idUsuario, $newRol);
            $data['changeROL'] = 1;
        } else {
            $data['changeROL'] = 0;
        }


        return $data;
    }

    /**
     * 
     * @param type $dataInput
     * @param type $fieldName
     * @return type
     */
    protected function adminActivitiesGetFields($dataInput, $fieldName) {
        //Buscar campos coincidentes
        $campos = array();
        foreach ($dataInput as $key => $value) {
            if (strpos($key, $fieldName) === 0) {
                $cadenaSplit = explode('__', $key);
                $idActividad = $cadenaSplit[1];
                $campos[$idActividad] = $value;
            }
        }
        return $campos;
    }

    /**
     * Obtiene la lista de actividades ordenadas alfabéticamente
     * @param int[] $params
     * @return Activity[]
     */
    protected function adminActivities($params) {
        $data = array();
        //Necesitamos datos de actividades
        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);        
        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $activitiesAreasDAO =new ActividadTipoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $activitiesCategoriasDAO =new ActividadCategoriaMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competencesAreasDAO =new CompetenciaTipoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $validadorCodigo = new ValidadorCodigoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        
        $data['ACTIVITIES_AREA'] = $activitiesAreasDAO->getAllOrderedBy('nombre');            
        $data['ACTIVITIES_CATEGORY'] = $activitiesCategoriasDAO->getAllOrderedBy('nombre');
        $data['COMPETENCES_AREA'] = $competencesAreasDAO->getAllOrderedBy('nombre');
        

        //Vemos si se produjo una entrada del usuario
        if ($params['input']) {
            
            //Hay que clasificar las diferentes entradas que se han podido producir


            /**
             * 1 Comprobar si se ha añadido una Actividad     
             *        
             */
            if (isset($params['dataInput']['ACTIVITY_INSERT'])) {
                $entradaActividad = array();
                $condiciones = array();

                $entradaActividad['ACTIVITY_INSERT_NAME'] = $params['dataInput']['ACTIVITY_INSERT_NAME'];
                $entradaActividad['ACTIVITY_INSERT_DESCRIPTION'] = $params['dataInput']['ACTIVITY_INSERT_DESCRIPTION'];
                $entradaActividad['ACTIVITY_INSERT_CODE'] = $params['dataInput']['ACTIVITY_INSERT_CODE'];

                $condiciones[] = 45; //El nombre de la actividad no debe exceder 45 caracteres
                $condiciones[] = 512; //La descripción de la actividad no debe exceder 512 caracteres
                $condiciones[] = 40;


                $validacion = $this->checkFormulario($entradaActividad, $condiciones);

                //Si hay algún elemento a false, la validación fracasó

                $esCorrecto = $validacion[0];
                
                $res = $validadorCodigo->validar($entradaActividad['ACTIVITY_INSERT_CODE']);
                
                if(!$res){
                    
                    $esCorrecto = false;
                }

                //Si la validación ha sido satisfactoria, insertamos la actividad
                if ($esCorrecto) {
                    
                    $idCategory = $params['dataInput']['ACTIVITY_INSERT_CATEGORY'];
                    $actividad = new Activity(
                            null, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear(), $entradaActividad['ACTIVITY_INSERT_DESCRIPTION'], $entradaActividad['ACTIVITY_INSERT_NAME'], null, 1,$entradaActividad['ACTIVITY_INSERT_CODE'],$idCategory
                    );
                    if ($activityDAO->insert($actividad) != false) {
                        $data['INSERT'] = true;
                    }
                } else {
                    $data['INSERT'] = false;
                    $data['VALIDATION_DATA'] = $validacion;
                }
            }
            /**
             * FIN DE LA PRIMERA COMPROBACIÓN
             */
            /**
             * 2 Comprobar si ha habido una solicitud de modificacion
             *  
             */
            if (isset($params['dataInput']['ACTIVITIES_MODIFY'])) {
                if (isset($params['dataInput']['selectedActivity'])) {
                    foreach ($params['dataInput']['selectedActivity'] as $idActividad) {
                        //Hay que validar los datos introducidos
                        $validacion = array();
                        $condiciones = array();

                       
                        $validacion['CHANGE_ACTIVITY_DESCRIPTION__' . $idActividad] = $params['dataInput']['CHANGE_ACTIVITY_DESCRIPTION__' . $idActividad];
                        $validacion['CHANGE_ACTIVITY_NAME__' . $idActividad] = $params['dataInput']['CHANGE_ACTIVITY_NAME__' . $idActividad];
                        $validacion['CHANGE_ACTIVITY_CODE__' . $idActividad] = $params['dataInput']['CHANGE_ACTIVITY_CODE__' . $idActividad];

                        $condiciones[] = 512; //El nombre de la actividad no debe exceder 45 caracteres
                        $condiciones[] = 45; //La descripción de la actividad no debe exceder 512 caracteres      
                        $condiciones[] = 17; //La descripción de la actividad no debe exceder 512 caracteres   

                        $datosValidados = $this->checkFormulario($validacion, $condiciones);

                        $esCorrecto = $datosValidados[0];
                        
                        $res = $validadorCodigo->validar($params['dataInput']['CHANGE_ACTIVITY_CODE__' . $idActividad]);
                        if(!$res){
                            $esCorrecto = false;
                        }

                        if ($esCorrecto) {
                            $nombre = $params['dataInput']['CHANGE_ACTIVITY_NAME__' . $idActividad];
                            $descripcion = $params['dataInput']['CHANGE_ACTIVITY_DESCRIPTION__' . $idActividad];
                                
                            $codigo = $params['dataInput']['CHANGE_ACTIVITY_CODE__' . $idActividad];
                          
                            $idCategory = $params['dataInput']['CHANGE_ACTIVITY_CATEGORY__' . $idActividad];
                            
                            $actividadModificada = new Activity(
                                    $idActividad, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear(), $descripcion, $nombre, null, 1,$codigo,$idCategory
                            );
                            
                            $activityDAO->update($actividadModificada);
                            $data['CHANGE'] = true;
                        } else {
                            $data['CHANGE'] = false;
                            $data['VALIDATION'] = $datosValidados;
                        }
                    }
                }
            }

            /**
             * FIN DE LA SEGUNDA COMPROBACIÓN
             * 
             */
            if (isset($params['dataInput']['ACTIVITIES_DELETE'])) {
                if (isset($params['dataInput']['selectedActivity'])) {
                    $resultado = true;
                    foreach ($params['dataInput']['selectedActivity'] as $idActividad) {
                        $res = $activityDAO->delete($idActividad);
                        if (!$res) {
                            $resultado = false;
                        }
                    }
                    $data['DELETE'] = $resultado;
                }
            }
            
            if (isset($params['uploadAssociation'] )) {
                
                if($params['uploadAssociation']){
                    if(isset($params['dataInput']) && isset($params['file'])){
                        $fichero = $params['file'];
                        require_once 'lib/PHPExcel/PHPExcel.php';                        
                        
          
                        $inputFileType = PHPExcel_IOFactory::identify($fichero['tmp_name']);
                        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

                        if (strpos($inputFileType, 'Excel') !== false) {

                            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                            $objPHPExcel = $objReader->load($fichero['tmp_name']);

                            $esFinArchivo = false;
                            $filaInicial = 2;


                            $hojaActual = $objPHPExcel->getActiveSheet();
                            $indiceFila = $filaInicial;
                            $indiceInserciones = 0;
                            $contadorInserciones = 0;
                            $RESULT['IS_ERRORS'] = false;
                            while (!$esFinArchivo) {


                                $actividad = $hojaActual->getCellByColumnAndRow(0, $indiceFila)->getValue();
                                $dniProfessor = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                                
                                if(strlen($actividad) == 0 || strlen($dniProfessor) == 0){
                                    $esFinArchivo = true;                                               
                                }
                                preg_match_all('!\d+!', $dniProfessor, $dniProfessor);
                                if(!isset($dniProfessor[0][0])){
                                    $esFinArchivo = true;
                                }
                                
                                if(!$esFinArchivo){               
                                    
                                    
                                    $dniProfessor = $dniProfessor[0][0];
                               
                                    $idActividad = $activityDAO->getActivityStartsWith($actividad);
                                    $idProfessor = $userDAO->getIdUserFromRealId($dniProfessor);
                                    
                                    $activityDAO->addProfessorToActivity($idActividad[0]->getIdActividad(), $idProfessor, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());    
                                    ++$indiceFila;
                                    ++$indiceInserciones;
                                }
                            }
                            
                            $data['ASSOCIATION_INSERTIONS'] = $indiceInserciones;
                        }
                    }
                }
            }            
        }
        
        $todo = true;
        if ($params['buscador']) {
            if (isset($params['activity_name']) && strlen($params['activity_name'])) {
 
                if(strcmp($params['activity_name'], 'TODO') == 0){   
                    $listaActividades = $activityDAO->getAllOrderedBy('nombre', 0);
                }else{
                    $listaActividades = $activityDAO->getActivityStartsWith($params['activity_name']);
                }
                
  
                $data['EXCEL_TITLE'] = $params['filter_title'];
                if($params['filter_title'] != -1){
                    
                    $areaEscogida = null;
                    foreach ($data['ACTIVITIES_AREA'] as $area){

                        if($area->getIdActividadTipo() == $params['filter_title']){
                            $areaEscogida = $area;   
                        }
                    }
                   
                    $listaActividadesFiltradas = array();
                    foreach ($listaActividades as $actividad) {    
                        if(stristr($actividad->getCodigo(),$areaEscogida->getCodigo()) != false){
                            $listaActividadesFiltradas[] = $actividad;
                           
                        }
                    }                    
                    $listaActividades = $listaActividadesFiltradas;

                }
                $data['EXCEL_TYPE'] = $params['filter_type'];
                if($params['filter_type'] != -1){
                    
                    $listaActividadesFiltradas = array();
                    foreach ($listaActividades as $actividad) {
                        if($actividad->getIdCategoria() == $params['filter_type']){
                            $listaActividadesFiltradas[] = $actividad;
                        }
                    }                    
                    $listaActividades = $listaActividadesFiltradas;
                }                
                
                
                $todo = false;
                 
                if(isset($params['competences']) && $params['competences']){
                    $data['INCLUDE_COMPETENCES'] = true;
                    foreach ($listaActividades as $actividad) {
                        $competencias = $competenceDAO->getCompetencesByActivity($actividad->getIdActividad());                        
                        $data['ACTIVITY_LIST']['COMPETENCES'][$actividad->getIdActividad()] = $competencias;
                    }
                }
                if(isset($params['full']) &&  $params['full']){
                    $data['INCLUDE_COMPETENCES'] = true;
                    $data['INCLUDE_INDICATORS'] = true;
                    foreach ($listaActividades as $actividad) {
                        $competencias = $competenceDAO->getCompetencesByActivity($actividad->getIdActividad()); 
                        if(count($competencias) > 0 && $competencias != null){            
                            foreach ($competencias as $competencia) {
                                $indicators = $indicatorDAO->getIndicatorByCompetenceId($competencia->getIdCompetencia());
                                $data['ACTIVITY_LIST']['INDICATORS'][$competencia->getIdCompetencia()] = $indicators;
                            }
                            $data['ACTIVITY_LIST']['COMPETENCES'][$actividad->getIdActividad()] = $competencias;
                        }
                    }
                }    
            }
            $data['SEARCH_CHAIN'] = $params['activity_name'];
      
            
        }

        //Obtenemos las actividades ordenadas de manera alfabética        
//        if($todo){
//            $listaActividades = $activityDAO->getAllOrderedBy('nombre', 0);
//        }

        if ($todo) {
            $listaActividades = array();
        }

        if (isset($listaActividades) && $listaActividades != false) {
            $data['CONTENT_MAIN'] = $listaActividades;
            $data['NO_ACTIVITIES'] = false;
        } else {
            $data['NO_ACTIVITIES'] = true;
        }
        
    

        return $data;
    }
    
    private function getCompetenceIndicators($idCompetence){
        
        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        
        $competencia = $competenceDAO->get($idCompetence); 
        
        $res['COMPETENCE'] = $competencia;
        $res['INDICATORS'] = $indicatorDAO->getIndicatorByCompetenceId($idCompetence);
        
        return $res;           
        
    }

    /**
     * Obtiene la lista de competencias asociadas a una actividad
     *  $params[0] es el identificador de la actividad
     * @param int[] $params
     * @return Activity[]
     */
    protected function adminCompetenceActivities($params) {
        $data = array();
        $data['NO_ACTIVITY'] = true;

        //Se comprueba si se introdujo una actividad por parámetro
        if (isset($params[0])) {
            $actividadDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

            $actividad = $actividadDAO->get($params[0]);

            //Solicitud de eliminación de una competencia
            if (isset($params['REMOVE'])) {
                $res = $actividadDAO->removeCompetenceFromActivity($params[0], $params['REMOVE']);
                $data['REMOVE_RESULT'] = $res;
            }


            //Solicitud de agregación de una competencia a la actividad
            if (isset($params['ADD'])) {
                $res = $actividadDAO->addCompetenceToActivity($params[0], $params['ADD']);
                $data['ADDING_RESULT'] = $res;
            }


            //Si la actividad existe, se continua
            if ($actividad != false) {
                $data['NO_ACTIVITY'] = false;
                $data['ACTIVITY'] = $actividad;
                $listaCompetencias = $competenceDAO->getCompetencesByActivity($params[0]);

                if ($listaCompetencias != false && count($listaCompetencias) > 0) {
                    $data['NO_COMPETENCES'] = false;
                    $data['COMPETENCES'] = $listaCompetencias;
                } else {
                    $data['NO_COMPETENCES'] = true;
                }
            }

            //Ahora se comprueba si se solicitó la búsqueda de competencias para añadir a la actividad
            if (isset($params['SEARCH'])) {
                $listaCompetenciasEncontradas = $competenceDAO->getCompetencesStartsWith($params['SEARCH'], $params[0]);

                if ($listaCompetenciasEncontradas != false && count($listaCompetenciasEncontradas) > 0) {
                    $data['NO_COMPETENCES_FOUND'] = false;
                    $data['COMPETENCES_FOUND'] = $listaCompetenciasEncontradas;
                } else {
                    $data['NO_COMPETENCES_FOUND'] = true;
                }
                $data['SEARCH_TEXT'] = $params['SEARCH'];
            }
        }

        return $data;
    }

    /**
     * Obtiene la lista de estudiantes asociadas a una actividad
     * @param int[] $params
     * @return Activity[]
     */
    protected function adminProfessorActivities($params) {
        $data = array();
        $data['NO_ACTIVITY'] = true;


        //Se comprueba si se introdujo una actividad por parámetro
        if (isset($params[0])) {
            $actividadDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $profesorDAO = new ProfessorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

            $actividad = $actividadDAO->get($params[0]);

            //Solicitud de eliminación de una competencia
            if (isset($params['REMOVE'])) {
                $res = $actividadDAO->removeProfessorFromActivity($params[0], $params['REMOVE'], $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());
                $data['REMOVE_RESULT'] = $res;
            }


            //Solicitud de agregación de una competencia a la actividad
            if (isset($params['ADD'])) {

                $res = $actividadDAO->addProfessorToActivity($params[0], $params['ADD'], $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());
                $data['ADDING_RESULT'] = $res;
            }


            //Si la actividad existe, se continua
            if ($actividad != false) {
                $data['NO_ACTIVITY'] = false;
                $data['ACTIVITY'] = $actividad;
                $listaProfesores = $profesorDAO->getProfessorsByActivity($params[0], $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());

                if ($listaProfesores != false && count($listaProfesores) > 0) {

                    $data['NO_PROFESSORS'] = false;
                    $data['PROFESSORS'] = $listaProfesores;
                } else {
                    $data['NO_PROFESSORS'] = true;
                }
            }

            //Ahora se comprueba si se solicitó la búsqueda de competencias para añadir a la actividad
            if (isset($params['SEARCH'])) {
                $listaProfesoresEncontrados = $profesorDAO->getProfessorsStartsWith($params['SEARCH'], $params[0]);

                if ($listaProfesoresEncontrados != false && count($listaProfesoresEncontrados) > 0) {
                    $data['NO_PROFESSORS_FOUND'] = false;
                    $data['PROFESSORS_FOUND'] = $listaProfesoresEncontrados;
                } else {
                    $data['NO_PROFESSORS_FOUND'] = true;
                }
                $data['SEARCH_TEXT'] = $params['SEARCH'];
            }
        }

        return $data;
    }

    /**
     * Obtiene la lista de competencias, permitiendo su gestión
     * @return Object[]
     */
    protected function adminCompetences($params) {
        $data = array();
        //Necesitamos datos de competencias
        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceTypeDAO = new CompetenceTypeMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceAreaDAO = new CompetenciaTipoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceMateriaDAO = new CompetenceAreaMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $validadorCodigo = new ValidadorCodigoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        
        
        $data['COMPETENCE_MATERIAS'] = $competenceMateriaDAO->getAllOrderedBy('nombre');
        $data['COMPETENCE_AREAS'] = $competenceAreaDAO->getAllOrderedBy('nombre');
        $data['COMPETENCE_TYPES'] = $competenceTypeDAO->getAll();
        
        //Vemos si se produjo una entrada del usuario
        if ($params['input']) {
            //Hay que clasificar las diferentes entradas que se han podido producir


            /**
             * 1 Comprobar si se ha añadido una Competencia     
             *        
             */
            if (isset($params['dataInput']['COMPETENCE_INSERT'])) {

                $entradaCompetencia = array();
                $condiciones = array();

                $entradaCompetencia['COMPETENCE_INSERT_NAME'] = $params['dataInput']['COMPETENCE_INSERT_NAME'];
                if (isset($params['dataInput']['COMPETENCE_INSERT_TYPE'])) {
                    $entradaCompetencia['COMPETENCE_INSERT_TYPE'] = $params['dataInput']['COMPETENCE_INSERT_TYPE'];
                } else {
                    $entradaCompetencia['COMPETENCE_INSERT_TYPE'] = 0;
                }
                $entradaCompetencia['COMPETENCE_INSERT_CODE'] = $params['dataInput']['COMPETENCE_INSERT_CODE'];
                if (strlen($params['dataInput']['COMPETENCE_INSERT_DESCRIPTION']) > 0) {
                    $entradaCompetencia['COMPETENCE_INSERT_DESCRIPTION'] = $params['dataInput']['COMPETENCE_INSERT_DESCRIPTION'];
                }

                $condiciones[] = 500; //El nombre de la Competencia no debe exceder 500 caracteres
                $condiciones[] = 1; //type 
                $condiciones[] = 17; //código
                if (strlen($params['dataInput']['COMPETENCE_INSERT_DESCRIPTION']) > 0) {
                    $condiciones[] = 512; //La descripción de la competencia no debe exceder 512 caracteres
                }


                
                $validacion = $this->checkFormulario($entradaCompetencia, $condiciones);
                


                //Si hay algún elemento a false, la validación fracasó

                $esCorrecto = $validacion[0];
                $res = $validadorCodigo->validar($entradaCompetencia['COMPETENCE_INSERT_CODE']);
                if(!$res){
                    $esCorrecto = false;         
                }

                //Si la validación ha sido satisfactoria, insertamos la competencia
                if ($esCorrecto) {

                    
                    
                    $competencia = new Competence(
                            null, $entradaCompetencia['COMPETENCE_INSERT_NAME'], $params['dataInput']['COMPETENCE_INSERT_DESCRIPTION'], $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear(), null, null, 1, null, $entradaCompetencia['COMPETENCE_INSERT_TYPE'], 'GII', $this->idUsuario, $entradaCompetencia['COMPETENCE_INSERT_CODE']
                    );

                    if ($competenceDAO->insert($competencia) != false) {
                        $data['INSERT'] = true;
                        $data['ALREADY_EXISTS'] = false;
                    } else {
                        $data['INSERT'] = false;
                        $data['ALREADY_EXISTS'] = true;
                        $data['VALIDATION_DATA'] = $validacion;
                    }
                } else {
                    $data['INSERT'] = false;
                    $data['VALIDATION_DATA'] = $validacion;
                }
            }
            /**
             * FIN DE LA PRIMERA COMPROBACIÓN
             */
            /**
             * 2 Comprobar si ha habido una solicitud de modificacion
             *  
             */
            if (isset($params['dataInput']['COMPETENCES_MODIFY'])) {
                if (isset($params['dataInput']['selectedCompetences'])) {
                    foreach ($params['dataInput']['selectedCompetences'] as $idCompetencia) {

                        $validacion['CHANGE_COMPETENCE_NAME__' . $idCompetencia] = $params['dataInput']['CHANGE_COMPETENCE_NAME__' . $idCompetencia];
                        $validacion['CHANGE_COMPETENCE_TYPE__' . $idCompetencia] = $params['dataInput']['CHANGE_COMPETENCE_TYPE__' . $idCompetencia];
                        $validacion['CHANGE_COMPETENCE_CODE__' . $idCompetencia] = $params['dataInput']['CHANGE_COMPETENCE_CODE__' . $idCompetencia];

                        $condiciones[] = 500; //El nombre de la competencia no debe exceder 500 caracteres                        
                        $condiciones[] = 1; //isActive
                        $condiciones[] = 17;

                        $datosValidados = $this->checkFormulario($validacion, $condiciones);
                        $esCorrecto = $datosValidados[0];
                        $res = $validadorCodigo->validar($entradaCompetencia['CHANGE_COMPETENCE_CODE__']);
                
                        if(!$res){
                            $esCorrecto = false;         
                        } 
                        if ($esCorrecto) {

                            $nombre = $params['dataInput']['CHANGE_COMPETENCE_NAME__' . $idCompetencia];
                            $descripcion = $params['dataInput']['CHANGE_COMPETENCE_DESCRIPTION__' . $idCompetencia];
                            $codigo = $params['dataInput']['CHANGE_COMPETENCE_CODE__' . $idCompetencia];

                            $competenciaModificada = new Competence(
                                    $idCompetencia, $nombre, $descripcion, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear(), null, null, 1, 'CURRENT_TIMESTAMP()', $params['dataInput']['CHANGE_COMPETENCE_TYPE__' . $idCompetencia], 'GII', $this->idUsuario, $codigo
                            );

                            $competenceDAO->update($competenciaModificada);
                            $data['CHANGE'] = true;
                        } else {
                            $data['CHANGE'] = false;
                            $data['VALIDATION'] = $datosValidados;
                        }
                    }
                }
            }


            /*
             * FIN DE LA QUINTA COMPROBACIÓN
             */

            /**
             * 6 Comprobar peticiones de borrado
             */
            if (isset($params['dataInput']['COMPETENCES_DELETE'])) {
                if (isset($params['dataInput']['selectedCompetences'])) {
                    $resultado = true;
                    foreach ($params['dataInput']['selectedCompetences'] as $idCompetencia) {
                        $res = $competenceDAO->delete($idCompetencia);
                        if ($res == false) {
                            $resultado = false;
                        }
                    }
                }
                $data['DELETE'] = $resultado;
            }
        }
        $todo = true;
        if ($params['buscador']) {
            if (isset($params['buscador_codigo']) && strlen($params['buscador_codigo']) > 0) {
                if(strcmp($params['buscador_codigo'], 'TODO') == 0){                    
                    $listaCompetencias = $competenceDAO->getAllOrderedBy('codigo', 0);
                    
                }else{
                    $listaCompetencias = $competenceDAO->getCompetencesStartsWith($params['buscador_codigo']);
                }
                
                $data['EXCEL_AREA'] = $params['filter_area'];
                
             
                if($params['filter_area'] != -1){
                    
                    $areaEscogida = null;
                    foreach ($data['COMPETENCE_AREAS'] as $area){

                        if($area->getIdTipo() == $params['filter_area']){
                            $areaEscogida = $area;   
                        }
                    }
                   
                    $listaCompetenciasFiltradas = array();
                    foreach ($listaCompetencias as $competencia) {    
                        if(stristr($competencia->getCode(),$areaEscogida->getCodigo()) != false){
                            $listaCompetenciasFiltradas[] = $competencia;
                           
                        }
                    }                    
                    $listaCompetencias = $listaCompetenciasFiltradas;

                }
                $data['EXCEL_MATERIA'] = $params['filter_materia'];
                if($params['filter_materia'] != -1){
                    $materiaEscogida = null;
                    foreach ($data['COMPETENCE_MATERIAS'] as $materia){

                        if($materia->getIdMateria() == $params['filter_materia']){
                            $materiaEscogida = $materia;   
                        }
                    }
                   
                    $listaCompetenciasFiltradas = array();
                    foreach ($listaCompetencias as $competencia) {    
                        if(stristr($competencia->getCode(),$materiaEscogida->getCodigo()) != false){
                            $listaCompetenciasFiltradas[] = $competencia;
                           
                        }
                    }                    
                    $listaCompetencias = $listaCompetenciasFiltradas;                    

                }                  
                
                
                
                
                $todo = false;
                $data['EXCEL_CHAIN'] = $params['buscador_codigo'];
                
                if(isset($params['indicators']) &&  $params['indicators']){              
                    $data['INCLUDE_INDICATORS'] = true;                    
                        
                        if(count($listaCompetencias) > 0 && $listaCompetencias != null){            
                            foreach ($listaCompetencias as $competencia) {
                                $indicators = $indicatorDAO->getIndicatorByCompetenceId($competencia->getIdCompetencia());
                                $data['ACTIVITY_LIST']['INDICATORS'][$competencia->getIdCompetencia()] = $indicators;
                            }
                            
                        }
                    
                }
            }
        }

        //Obtenemos las competencias ordenadas de manera alfabética      
//        if($todo){
//            $listaActividades = $competenceDAO->getAllOrderedBy('codigo', 0);
//        }
        if ($todo) {
            $listaUsuarios = array();
        }

        if (isset($listaCompetencias) && $listaCompetencias != false) {
            $data['CONTENT_MAIN'] = $listaCompetencias;
            $data['NO_COMPETENCES'] = false;
        } else {
            $data['NO_COMPETENCES'] = true;
        }
        return $data;
    }

    /**
     * Gestiona los indicadores de una competencia
     * @param $string[] $params
     * @return object[]
     */
    protected function adminCompetencesIndicator($params) {
        $data = array();
        $data['NO_ACTIVITY'] = true;


        //Se comprueba si se introdujo una actividad por parámetro
        if (isset($params[0])) {
            $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $validadorCodigo = new ValidadorCodigoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            
            $competence = $competenceDAO->get($params[0]);

            //Solicitud de eliminación de una competencia
            if (isset($params['REMOVE'])) {
                $res = $competenceDAO->removeIndicatorFromCompetence($params[0], $params['REMOVE']);
                $data['REMOVE_RESULT'] = $res;
            }

            //Solicitud de agregación de un indicador a la competencia
            if (isset($params['ADD'])) {
                $res = $validadorCodigo->validar($params['ADD_INDICATOR_CODE']);
                
               if($res){
                    if (strlen($params['ADD_INDICATOR_NAME']) > 0) {
                        $indicador = new Indicator($params[0], null, $params['ADD_INDICATOR_NAME'], $params['ADD_INDICATOR_DESCRIPTION'], $params['ADD_INDICATOR_CODE']);

                        $res = $indicatorDAO->insert($indicador);
                    }
                    $data['ADDING_RESULT'] = $res;
               }
            }


            //Si la actividad existe, se continua
            if ($competence != false) {
                $data['NO_COMPETENCE'] = false;
                $data['COMPETENCE'] = $competence;
                $listaIndicadores = $indicatorDAO->getIndicatorsByCompetence($params[0]);

                if ($listaIndicadores != false && count($listaIndicadores) > 0) {

                    $data['NO_INDICATORS'] = false;
                    $data['INDICATORS'] = $listaIndicadores;
                } else {
                    $data['NO_INDICATORS'] = true;
                }
            } else {
                $data['NO_COMPETENCE'] = true;
            }

            //Ahora se comprueba si se solicitó la búsqueda de competencias para añadir a la actividad
            if (isset($params['SEARCH'])) {
                $listaIndicadoresEncontrados = $indicatorDAO->getIndicatorsStartsWith($params['SEARCH'], $params[0]);

                if ($listaIndicadoresEncontrados != false && count($listaIndicadoresEncontrados) > 0) {
                    $data['NO_INDICATORS_FOUND'] = false;
                    $data['INDICATORS_FOUND'] = $listaIndicadoresEncontrados;
                } else {
                    $data['NO_INDICATORS_FOUND'] = true;
                }
                $data['SEARCH_TEXT'] = $params['SEARCH'];
            }
        }

        return $data;
    }

    /**
     * Recupera la contraseña de un usuario
     * @param String con el identificador (mail o DNI/pasaporte) de un usuario $params
     */
    protected function passwordReminder($params) {
        require (ROOT . 'PasswordManager.php');
        $data = array();
        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $mailRegex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $userId = null;
        if (preg_match($mailRegex, $params['USERID'])) {
            $userId = $userDAO->getIdUserFromMail($params['USERID']);
        } else {
            $userId = $userDAO->getIdUserFromRealId($params['USERID']);
        }
        $usuario = $userDAO->get($userId);
        if ($usuario != false) {
            $nuevaPassword = rand(10000, 99999);
            $cabeceras = "From: {$GLOBALS['GENERAL_MAIL_CONTACT']} " . "\r\n" .
                    "Reply-To: {$GLOBALS['GENERAL_MAIL_CONTACT']}" . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

            $res = mail($usuario->getMail(), 'PASSWORD_RECOVERY', $nuevaPassword, $cabeceras);

            if ($res) {

                $hash = PasswordManager::hash($nuevaPassword);
                $usuario->setPassword($hash);
                $userDAO->update($usuario);
                $data['PASSWORD_RECOVERY'] = true;
                $data['PASSWORD_RECOVERY_MAIL'] = true;
            } else {
                $data['PASSWORD_RECOVERY'] = true;
                $data['PASSWORD_RECOVERY_MAIL'] = false;
            }
        } else {

            $data['PASSWORD_RECOVERY'] = false;
            $data['PASSWORD_RECOVERY_MAIL'] = false;
        }

        return $data;
    }

    /**
     * Gestiona la vista del perfil de usuario
     * @param String[] $params
     * @return String[]
     */
    public function userProfile($params) {
        $data = array();
        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        //Se obtiene el identificador de usuario
        $usuario = $userDAO->get($this->idUsuario);
        //Guarda el objeto usuario en la matriz de datos de la vista
        if (isset($usuario) && $usuario != false) {
            $data['CONTENT_MAIN'] = $usuario;
            $data['IS_USER_VALID'] = true;
        } else {
            $data['IS_USER_VALID'] = false;
        }
        $data['UPLOAD_PHOTO'] = false;
        $data['CHANGE_PASSWORD'] = false;
        $data['USER_CURRENT_ROLE'] = $usuario->getRol();
        $data['IS_STUDENT'] = $userDAO->isStudentChangeRol($this->idUsuario, 2);
        $data['IS_PROFESSOR'] = $userDAO->isStudentChangeRol($this->idUsuario, 3);
        $data['IS_ADMIN'] = $userDAO->isStudentChangeRol($this->idUsuario, 4);



        $isModificaciones = false; //Controla si se produjeron modificaciones en los datos del usuario

        /**
         * Modificación de la imagen de perfil
         */
        if ($params['UPLOAD_PHOTO']) {
            $data['UPLOAD_PHOTO'] = true;
            $data['UPLOAD_PHOTO_ERROR'] = false;
            $data['UPLOAD_PHOTO_ERROR_TYPE'] = false;

            //Primero, se filtra por tamaño y extensión
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $temp = explode(".", $params['FILE']["name"]);
            $extension = end($temp);
            $destino = ROOT . 'avatarGenerator/images/';
            if ((($params['FILE']["type"] == "image/gif") ||
                    ($params['FILE']["type"] == "image/jpeg") ||
                    ($params['FILE']["type"] == "image/jpg") ||
                    ($params['FILE']["type"] == "image/pjpeg") ||
                    ($params['FILE']["type"] == "image/x-png") ||
                    ($params['FILE']["type"] == "image/png")) &&
                    ($params['FILE']["size"] < $GLOBALS["GENERAL_MAX_PHOTO_SIZE"]) &&
                    in_array($extension, $allowedExts)) {

                if ($params['FILE']["error"] > 0) {
                    $data['UPLOAD_PHOTO_ERROR'] = true;
                    $data['UPLOAD_PHOTO_ERROR_MESSAGE'] = $params['FILE']["error"];
                } else {

                    //Si supera el filtro de tamaño y extensión, se evalúa el nombre y el fichero subido
                    //Hay que generar un nombre aleatorio de 128 caracteres.
                    $nombre = $this->generateRandomString(128);
                    $idConversion = $this->generateRandomString(8);

                    //Aquí se realiza la evaluación para evitar colisiones de nombres y entradas "raras" del usuario

                    if ($this->upload($params['FILE']["tmp_name"], $destino, $idConversion, $params['FILE'], 0755)) {
                        $res = true;
                        //Se realiza la conversión a png (Si fuera necesaria
                        if ($params['FILE']["type"] != ($params['FILE']["type"] == "image/x-png") && $params['FILE']["type"] != ($params['FILE']["type"] == "image/png")) {
                            if (imagepng(imagecreatefromstring(file_get_contents($destino . $idConversion)), $destino . $nombre . '.png') == false) {
                                $res = false;
                            }
                            unlink($destino . $idConversion);
                        } else {
                            if (rename($destino . $idConversion, $destino . $nombre . '.png') == false) {
                                $res = false;
                            }
                        }
                        //Se modifica el tamaño de la imagen                        
                        include ROOT . 'lib/imageResize/smart_resize_image';
                        if (smart_resize_image($destino . $nombre . '.png', null, 220, 220, true, $destino . $nombre . '.png', false, false, 65) == false) {

                            $res = false;
                        }
                        //Se añade al usuario
                        if ($res) {
                            //Si el usuario ya tenía imagen, hay que borrarla                            
                            if (null !== $usuario->getImagenPerfil() && strlen($usuario->getImagenPerfil()) > 0) {
                                unlink($destino . $usuario->getImagenPerfil() . '.png');
                            }
                            $usuario->setImagenPerfil($nombre);
                            $data['UPLOAD_PHOTO_RESULT'] = true;
                        } else {
                            $data['UPLOAD_PHOTO_ERROR'] = true;
                            $data['UPLOAD_PHOTO_ERROR_MESSAGE'] = -2;
                        }
                    } else {
                        $data['UPLOAD_PHOTO_ERROR'] = true;
                        $data['UPLOAD_PHOTO_ERROR_MESSAGE'] = -1;
                    }
                }
            } else {
                //Si el erro es el 4º. el usuario quiere eliminar su imagen de perfil
                if ($params['FILE']["error"] == 4) {

                    if (null !== $usuario->getImagenPerfil() && strlen($usuario->getImagenPerfil()) > 0) {
                        unlink($destino . $usuario->getImagenPerfil() . '.png');
                    }
                    $usuario->setImagenPerfil(null);
                    $data['UPLOAD_PHOTO_REMOVED'] = true;
                } else {
                    $data['UPLOAD_PHOTO_ERROR_TYPE'] = true;
                }
            }
            $isModificaciones = true;
        }


        if ($params['CHANGE_DATA_NO_PASSWORD']) {

            if (strlen($params['NAME']) > 0) {
                $usuario->setNombre($params['NAME']);
            }
            if (strlen($params['LASTNAME1']) > 0) {
                $usuario->setApellido1($params['LASTNAME1']);
            }
            if (strlen($params['LASTNAME2']) > 0) {
                $usuario->setApellido2($params['LASTNAME2']);
            }
            if (strlen($params['MAIL']) > 0) {
                $usuario->setMail($params['MAIL']);
            }

            $isModificaciones = true;
        }
        /**
         * Modificación de datos del usuario
         */
        if ($params['CHANGE_DATA']) {

            $data['CHANGE_PASSWORD'] = true;
            require (ROOT . 'PasswordManager.php');
            //Se compara la nueva contraseña con la anterior


            $tam = strlen($params['PASSWORD_NEW']);

            if ($tam > 5 && $tam < 100 && strcmp($params['PASSWORD_NEW'], $params['PASSWORD_RETYPE']) == 0) {

                $data['INVALID_PASSWORD'] = false;
                $resultado = PasswordManager::verifyHash($params['PASSWORD_OLD'], $userDAO->getPasswordHashFromUser($this->idUsuario));
                if ($resultado) {
                    $data['PASSWORD_ERROR'] = false;
                    $hash = PasswordManager::hash($params['PASSWORD_NEW']);
                    $usuario->setPassword($hash);
                    $data['NEW_PASSWORD'] = true;
                } else {
                    $data['PASSWORD_ERROR'] = true;
                }
            } else {
                $data['INVALID_PASSWORD'] = true;
            }
            $isModificaciones = true;
        }

        //Se guardan los cambios realizados en el usuario      
        if ($isModificaciones) {
            $userDAO->update($usuario);
        }

        return $data;
    }

    /**
     * Genera un listado de las competencias aprobadas por un estudiante
     * @param type $params
     */
    private function studentDownloadReport($params) {
        $data = array();


        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $studentDAO = new StudentMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceTypeDAO = new CompetenceTypeMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenciaTipoDAO = new CompetenciaTipoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $evaluationReport = new EvaluationReport($GLOBALS["SYSTEM_CONNECTION"]);
        $evaluacionDAO = new EvaluacionMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $lugarDAO = new LugarMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $validadorCodigo = new ValidadorCodigoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        //Se comprueban las competencias que han sido aprobadas por el estudiante

        $listadoCompetenciasAprobadas = $this->getPassedCompetences($competenceDAO);


        $data['CONTENT_MAIN']['STUDENT'] = $studentDAO->get($this->idUsuario);
        $data['CONTENT_MAIN']['COMPETENCES'] = $listadoCompetenciasAprobadas;
        $data['CONTENT_MAIN']['COMPETENCE_TYPES'] = $competenceTypeDAO->getAll();      
        $data['CONTENT_MAIN']['COMPETENCE_AREAS'] = $competenciaTipoDAO->getAll();
        
        
        foreach ($listadoCompetenciasAprobadas as $competencia) {
            $data['CONTENT_MAIN']['COMPETENCES_CALIFICATION'][$competencia->getIdCompetencia()] = 
                    $evaluationReport->getStudentEvaluationOnCompetence($this->idUsuario, $competencia->getIdCompetencia());
            $data['CONTENT_MAIN']['INDICATORS'][$competencia->getIdCompetencia()] = array();
            foreach ($competencia->getIndicators() as $idIndicator) {
                $indicador = $indicatorDAO->get($competencia->getIdCompetencia(),$idIndicator);
                $is_passed = $evaluationReport->isStudentPassedIndicator($this->idUsuario, $competencia->getIdCompetencia(), $idIndicator);
                $calification = $evaluationReport->getStudentEvaluationOnIndicator($this->idUsuario, $competencia->getIdCompetencia(), $idIndicator);
                $data['CONTENT_MAIN']['INDICATORS'][$competencia->getIdCompetencia()][] = $indicador;
                $data['CONTENT_MAIN']['INDICATORS_PASSED'][$competencia->getIdCompetencia()][$idIndicator] = $is_passed;
                $data['CONTENT_MAIN']['INDICATORS_CALIFICATION'][$competencia->getIdCompetencia()][$idIndicator] = $calification;
                
                                
                $idPlace = $evaluacionDAO->getLastPlaceEvaluatedOnIndicator($competencia->getIdCompetencia(), $idIndicator, $this->idUsuario);
    
                $lugar = $lugarDAO->get($idPlace);
                
                $nombres = $validadorCodigo->getNombres($indicador->getCode());
        
                $data['CONTENT_MAIN']['INDICATORS_PLACE'][$competencia->getIdCompetencia()][$idIndicator]=$lugar;
              
                if(is_array($nombres) && count($nombres) == 1){
                    $data['CONTENT_MAIN']['INDICATORS_MATERY'][$competencia->getIdCompetencia()][$idIndicator]=$nombres[0];
                }else{
                    $data['CONTENT_MAIN']['INDICATORS_MATERY'][$competencia->getIdCompetencia()][$idIndicator]=null;
                }
            }
            $nombres = $validadorCodigo->getNombres($competencia->getCode());

            if(is_array($nombres) && count($nombres) == 2){
                $data['CONTENT_MAIN']['COMPETENCE_NAMES'][$competencia->getIdCompetencia()]['AREA'] = $nombres[0];
                $data['CONTENT_MAIN']['COMPETENCE_NAMES'][$competencia->getIdCompetencia()]['MATERIA'] = $nombres[1];
            }else{
                $data['CONTENT_MAIN']['COMPETENCE_NAMES'][$competencia->getIdCompetencia()]['AREA'] = null;
                $data['CONTENT_MAIN']['COMPETENCE_NAMES'][$competencia->getIdCompetencia()]['MATERIA'] = null;                
            }

        }
        

        return $data;
    }

    /**
     * Extrae los datos necesarios para la gestión de usuarios
     * @param type $params
     */
    private function adminUsers($params) {

        $data = array();
        require_once (ROOT . 'PasswordManager.php');
        //Necesitamos datos de competencias
        $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $areaDAO = new ProfessorAreaMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $centroDAO= new ProfessorCentreMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        
        $data['USER_AREAS'] = $areaDAO->getAll();
        $data['USER_CENTRES'] = $centroDAO->getAll();



        //Vemos si se produjo una entrada del usuario
        if ($params['input']) {
            //Hay que clasificar las diferentes entradas que se han podido producir


            /**
             * 1 Comprobar si se ha añadido un usuario
             *        
             */
            if (isset($params['dataInput']['USER_INSERT'])) {
                $entradaUsuario = array();
                $condiciones = array();

                $entradaUsuario['USER_INSERT_NAME'] = $params['dataInput']['USER_INSERT_NAME'];
                $entradaUsuario['USER_INSERT_LASTNAME1'] = $params['dataInput']['USER_INSERT_LASTNAME1'];

                $entradaUsuario['USER_INSERT_REALID'] = $params['dataInput']['USER_INSERT_REALID'];
                $entradaUsuario['USER_INSERT_MAIL'] = $params['dataInput']['USER_INSERT_MAIL'];
                $entradaUsuario['USER_INSERT_PASSWORD'] = $params['dataInput']['USER_INSERT_PASSWORD'];
                if (isset($params['dataInput']['USER_INSERT_PHONE']) && strlen($params['dataInput']['USER_INSERT_PHONE']) > 0) {
                    $entradaUsuario['USER_INSERT_PHONE'] = $params['dataInput']['USER_INSERT_PHONE'];
                }
                $entradaUsuario['USER_INSERT_ROL'] = $params['dataInput']['USER_INSERT_ROL'];



                $condiciones[] = 45; //El nombre del usuario no debe exceder 45 caracteres
                $condiciones[] = 90; //el no debe exceder 90 caracteres

                $condiciones[] = 9; //dni   
                $condiciones[] = 60; //mail  
                $condiciones[] = 60; //password   
              
                if (isset($params['dataInput']['USER_INSERT_PHONE']) && strlen($params['dataInput']['USER_INSERT_PHONE']) > 0) {
                    $condiciones[] = 13; //phone    
                }
                $condiciones[] = 1; //Rol 


                $validacion = $this->checkFormulario($entradaUsuario, $condiciones);




                //Si hay algún elemento a false, la validación fracasó

                $esCorrecto = $validacion[0];

                //Si la validación ha sido satisfactoria, insertamos la competencia
                if ($esCorrecto) {
                    $studentDAO = new StudentMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                    $professorDAO = new ProfessorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                    $hash = PasswordManager::hash($params['dataInput']['USER_INSERT_PASSWORD']);
                    if (isset($params['dataInput']['USER_INSERT_PHONE']) && strlen($params['dataInput']['USER_INSERT_PHONE']) == 0) {
                        $entradaUsuario['USER_INSERT_PHONE'] = null;
                    }
                    $usuario = new User(null, $hash, $entradaUsuario['USER_INSERT_NAME'], $entradaUsuario['USER_INSERT_LASTNAME1'], $params['dataInput']['USER_INSERT_LASTNAME2'], $entradaUsuario['USER_INSERT_REALID'], null, $entradaUsuario['USER_INSERT_MAIL'], $entradaUsuario['USER_INSERT_PHONE'], $entradaUsuario['USER_INSERT_ROL'],$params['dataInput']['USER_INSERT_AREA'],$params['dataInput']['USER_INSERT_CENTRE']);
                    $id = $userDAO->insert($usuario);
                    if ($id != false) {

                        $data['INSERT'] = true;
                        $usuario->setIdUsuario($id);
                        $studentDAO->insert($usuario, true);
                        $professorDAO->insert($usuario, true);
                    }
                } else {
                    $data['INSERT'] = false;
                    $data['VALIDATION_DATA'] = $validacion;
                }
            }
            /**
             * FIN DE LA PRIMERA COMPROBACIÓN
             */
            /**
             * 2 Comprobar si ha habido una solicitud de modificacion
             *  
             */
            if (isset($params['dataInput']['USERS_MODIFY'])) {
                if (isset($params['dataInput']['selectedUsers'])) {
                    foreach ($params['dataInput']['selectedUsers'] as $idUsuario) {
                        $validacion['CHANGE_USER_NAME__' . $idUsuario] = $params['dataInput']['CHANGE_USER_NAME__' . $idUsuario];
                        $validacion['CHANGE_USER_LASTNAME1__' . $idUsuario] = $params['dataInput']['CHANGE_USER_LASTNAME1__' . $idUsuario];
                        $validacion['CHANGE_USER_REALID__' . $idUsuario] = $params['dataInput']['CHANGE_USER_REALID__' . $idUsuario];
                        $validacion['CHANGE_USER_MAIL__' . $idUsuario] = $params['dataInput']['CHANGE_USER_MAIL__' . $idUsuario];
                        if ($params['dataInput']['CHANGE_USER_PASSWORD__' . $idUsuario] != null) {
                            $validacion['CHANGE_USER_PASSWORD__' . $idUsuario] = $params['dataInput']['CHANGE_USER_PASSWORD__' . $idUsuario];
                        }
                        $validacion['CHANGE_USER_PHONE__' . $idUsuario] = $params['dataInput']['CHANGE_USER_PHONE__' . $idUsuario];
                        $validacion['CHANGE_USER_ROL__' . $idUsuario] = $params['dataInput']['CHANGE_USER_ROL__' . $idUsuario];

                        $condiciones[] = 45; //El nombre del usuario no debe exceder 45 caracteres
                        $condiciones[] = 90; //el no debe exceder 90 caracteres

                        $condiciones[] = 9; //dni   
                        $condiciones[] = 60; //mail  
                        if ($params['dataInput']['CHANGE_USER_PASSWORD__' . $idUsuario] != null) {
                            $condiciones[] = 60; //password    
                        }
                        $condiciones[] = 13; //phone 
                        $condiciones[] = 1; //Rol 

                        $datosValidados = $this->checkFormulario($validacion, $condiciones);
                        $esCorrecto = $datosValidados[0];



                        if ($esCorrecto) {
                            $nombre = $params['dataInput']['CHANGE_USER_NAME__' . $idUsuario];
                            $apellido1 = $params['dataInput']['CHANGE_USER_LASTNAME1__' . $idUsuario];
                            $apellido2 = $params['dataInput']['CHANGE_USER_LASTNAME2__' . $idUsuario];
                            $DNI = $params['dataInput']['CHANGE_USER_REALID__' . $idUsuario];
                            $mail = $params['dataInput']['CHANGE_USER_MAIL__' . $idUsuario];
                            if ($params['dataInput']['CHANGE_USER_PASSWORD__' . $idUsuario] != null) {
                                $password = PasswordManager::hash($params['dataInput']['CHANGE_USER_PASSWORD__' . $idUsuario]);
                            } else {
                                $password = null;
                            }
                            $phone = $params['dataInput']['CHANGE_USER_PHONE__' . $idUsuario];
                            $rol = $params['dataInput']['CHANGE_USER_ROL__' . $idUsuario];
                            $idArea = $params['dataInput']['CHANGE_USER_AREA__' . $idUsuario];
                            $idCentro = $params['dataInput']['CHANGE_USER_CENTRE__' . $idUsuario];

                            $usuarioModificado = new User($idUsuario, $password, $nombre, $apellido1, $apellido2, $DNI, null, $mail, $phone, $rol,$idArea,$idCentro);

                            $userDAO->update($usuarioModificado);
                            $data['CHANGE'] = true;
                        } else {
                            $data['CHANGE'] = false;
                            $data['VALIDATION'] = $datosValidados;
                        }
                    }
                }
            }


            /*
             * FIN DE LA QUINTA COMPROBACIÓN
             */

            /**
             * 6 Comprobar peticiones de borrado
             */
            if (isset($params['dataInput']['USERS_DELETE'])) {
                if (isset($params['dataInput']['selectedUsers'])) {
                    $resultado = true;

                    foreach ($params['dataInput']['selectedUsers'] as $idUsuario) {

                        $res = $userDAO->delete($idUsuario);
                        if ($res == false) {
                            $resultado = false;
                        }
                    }
                }
                $data['DELETE'] = $resultado;
            }
        }
        $todos = true;
        if ($params['IS_BUSCADOR']) {
            
            if (isset($params['mail']) && strlen($params['mail']) > 0) {
                if(strcmp($params['mail'], 'TODO') == 0){
                    $listaUsuarios = $userDAO->getAllOrderedBy('lastname1', 0);
                    $todos = false;
                    $data['EXCEL_CHAIN'] = 'TODO';
                }else{                
                    $id = $userDAO->getIdUserFromMail($params['mail']);
                    $data['EXCEL_CHAIN'] = $params['mail'];
                }
            }
            if (isset($params['realid']) && strlen($params['realid']) > 0 ) {
                if(strcmp($params['realid'], 'TODO') == 0){
                    $listaUsuarios = $userDAO->getAllOrderedBy('lastname1', 0);
                    $todos = false;
                    $data['EXCEL_CHAIN'] = 'TODO';
                }else{
                    $id = $userDAO->getIdUserFromRealId($params['realid']);
                    $data['EXCEL_CHAIN'] = $params['realid'];
                }
            }
           
            if($params['IS_FILTRO']){
                
                
                $todos = false;    
                if($params['filter_area'] == -1 && $params['filter_centre'] == -1){                   
                    $listaUsuarios = $userDAO->getAllOrderedBy('lastname1', 0);      
                    $data['EXCEL_CHAIN'] = 'TODO';
                }else if($params['filter_area'] == -1){
                    $listaUsuarios = $userDAO->getAllByCentre($params['filter_centre']);    
                    $data['EXCEL_CHAIN'] = 'CENTRE';
                    $data['EXCEL_CHAIN_PARAM'] = $params['filter_centre'];
                }else if($params['filter_centre'] == -1){                   
                    $listaUsuarios = $userDAO->getAllByArea($params['filter_area']);   
                    $data['EXCEL_CHAIN'] = 'AREA';
                    $data['EXCEL_CHAIN_PARAM'] = $params['filter_area'];    
                    
                }else{
                    $listaUsuarios = $userDAO->getAllByAreaAndCentre($params['filter_area'], $params['filter_centre']);   
                    $data['EXCEL_CHAIN'] = 'FILTER';
                    $data['EXCEL_CHAIN_PARAM_AREA'] = $params['filter_area'];           
                    $data['EXCEL_CHAIN_PARAM_CENTRE'] = $params['filter_centre'];         
                }
           }
               

            

            if (isset($id)) {
                $usuario = $userDAO->get($id);
                if ($usuario != null) {
                    $listaUsuarios[] = $usuario;
                }
                $todos = false;
            }
            

        }

        //En uno de los comunes cambios de requisitos realizados por el cliente, 
        //ya no quieren que se vea una tabla con todo el mundo. Pero como se prevee
        //que querran tenerla otra vez en el futuro, se deja el codigo preparado.

        /*
          if($todos){
          //Obtenemos los usuarios ordenados de manera alfabética
          $listaUsuarios = $userDAO->getAllOrderedBy('lastname1', 0);
          }
         * */

        if ($todos) {

            $listaUsuarios = array();
        }


        if (isset($listaUsuarios) && $listaUsuarios != false) {
            
            $data['CONTENT_MAIN'] = $listaUsuarios;
            $data['NO_USERS'] = false;
        } else {
            $data['NO_USERS'] = true;
        }
        
        
        $data['AREAS']  = $areaDAO->getAll();
        $data['CENTROS'] = $centroDAO->getAll();        
        return $data;
    }

    /**
     * Muestra la vista en caso de que ocurra un error en modo de producción
     * @param String[] $params 
     * @return String[]
     */
    private function userError($params) {
        $data = array();
        return $data;
    }

    /**
     * Muestra la vista con ayuda al usuario, tan solo debe devolver el rol del usuario
     * @param String[] $params
     * @return String[]
     */
    private function userHelp($params) {
        $data = array();
        $data['USER_ROL'] = $params[0];


        //Comprobar argumentos introducidos por el usuario
        $data['CONTENT_MAIN'] = 0; // Por defecto
        if (isset($params['dataInput']['loginHelp'])) {
            $data['CONTENT_MAIN'] = 1;
        } else if (isset($params['dataInput']['registerHelp'])) {
            $data['CONTENT_MAIN'] = 2;
        }


        //ESTUDIANTE
        else if (isset($params['dataInput']['competencesHelp'])) {
            $data['CONTENT_MAIN'] = 3;
        } else if (isset($params['dataInput']['activitiesHelp'])) {
            $data['CONTENT_MAIN'] = 4;
        }


        //PROFESOR
        else if (isset($params['dataInput']['professor_activitiesHelp'])) {
            $data['CONTENT_MAIN'] = 5;
        } else if (isset($params['dataInput']['professor_competencesHelp'])) {
            $data['CONTENT_MAIN'] = 6;
        }


        //ADMINISTRADOR
        else if (isset($params['dataInput']['notificationsHelp'])) {
            $data['CONTENT_MAIN'] = 7;
        } else if (isset($params['dataInput']['admin_competencesHelp'])) {
            $data['CONTENT_MAIN'] = 8;
        } else if (isset($params['dataInput']['admin_activitiesHelp'])) {
            $data['CONTENT_MAIN'] = 9;
        } else if (isset($params['dataInput']['admin_usersHelp'])) {
            $data['CONTENT_MAIN'] = 10;
        }

        //COMUNES
        else if (isset($params['dataInput']['contactHelp'])) {
            $data['CONTENT_MAIN'] = 11;
        } else if (isset($params['dataInput']['messagesHelp'])) {
            $data['CONTENT_MAIN'] = 12;
        }


        return $data;
    }

    /**
     * Inserta en la base de datos alumnos extraidos desde una 
     * hoja de calculo y los relaciona con un grupo.
     * 
     * Si el alumno ya existe, lo anade al grupo. 
     * 
     * 
     * @param array $params array con el fichero de excel
     * @return boolean
     */
    protected function UserAddStudentsFromFile($params) {
        /**
         * Es necesario desactivar los limites de ejecucion del servidor 
         * para cargar listas de msa de 20 usuarios
         */
        ini_set('max_execution_time', 0);
        $coste = PasswordManager::getTiempo();
        $actividadDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        if ($params['IS_PROFESSOR']) {
            $condicion = $params['ID_SUBJECT'] != false && $actividadDAO->isProfessorLinkedtoActivity($params['ID_SUBJECT'], $this->idUsuario);
            $data['ID_ACTIVITY'] = $params['ID_SUBJECT'];
        } else {
            $condicion = true;
        }

        if ($condicion) {

            if ($params['IS_UPLOADED_FILE']) {

                require_once 'lib/PHPExcel/PHPExcel.php';
                require_once (ROOT . 'PasswordManager.php');



                $inputFileType = PHPExcel_IOFactory::identify($params['file']['tmp_name']);

                if (strpos($inputFileType, 'Excel') !== false) {

                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($params['file']['tmp_name']);

                    $esFinArchivo = false;
                    $filaInicial = 2;


                    $hojaActual = $objPHPExcel->getActiveSheet();
                    $indiceFila = $filaInicial;

                    
                    $isAllStudentAdded = true;
                    $contadorInserciones = 0;
                    $RESULT['IS_ERRORS'] = false;
                    $RESULT['ERROR_LIST'] = null;
                    $data['NO_USERS'] = true;
                    while (!$esFinArchivo) {
                        $nullCounter = 0;
                        $isEstudianteValido = true;

                        $nombre = $hojaActual->getCellByColumnAndRow(0, $indiceFila)->getValue();
                        $apellido1 = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                        $apellido2 = $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue();
                        $dni = $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue();
                        $mail = $hojaActual->getCellByColumnAndRow(4, $indiceFila)->getValue();
                        $centroClinico = $hojaActual->getCellByColumnAndRow(5, $indiceFila)->getValue();
                        $areaClinica = $hojaActual->getCellByColumnAndRow(6, $indiceFila)->getValue();
                        
                        
                        $idCentro = -1;
                        $idArea = -1;

                        if (is_null($nombre) || strlen($nombre) == 0) {
                            
                            $isEstudianteValido = false;
                            ++$nullCounter;
                        }
                        if (is_null($apellido1) || strlen($apellido1) == 0) {
                            
                            $isEstudianteValido = false;
                            ++$nullCounter;
                        }
                        if (is_null($dni) || strlen($dni) == 0) {
                           
                            $isEstudianteValido = false;
                            ++$nullCounter;
                        } else {
                            //Suprimir la letra final
                            if (!is_numeric(mb_substr($dni, -1))) {
                                $dni = mb_substr($dni, 0, -1);

                                //Suprimir - si fuera necesario
                                if (!is_numeric(mb_substr($dni, -1))) {
                                    $dni = mb_substr($dni, 0, -1);
                                }
                            }
                        }
                        if(strlen($mail) + strlen($dni) + strlen($nombre) + strlen($apellido1) == 0 ){
                            $esFinArchivo = true;
                        }                        
                        else if (is_null($mail) || strlen($mail) == 0 || !filter_var($mail,FILTER_VALIDATE_EMAIL)) {
                           
                            $isEstudianteValido = false;
                            ++$nullCounter;
                            $RESULT['IS_ERRORS'] = true;
                            $RESULT['ERROR_LIST'][] = $indiceFila;                            
                        }
                        if (is_null($centroClinico) || strlen($centroClinico) == 0) {
                            $idCentro = 1;                            
                        }
                        if (is_null($areaClinica) || strlen($areaClinica) == 0) {
                            $idArea = 1;                            
                        }

                        if ($isEstudianteValido) {
                            
                            
                            $inserted = false;
                            $res = $this->getIdFromMailOrRealID($dni);
                            $error = false;
                            if ($res == false) {// Si el usuario no existe, se añade
                                $userDAO = new UserMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                                $studentDAO = new StudentMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                                $professorDAO = new ProfessorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                                
                                
                                $areaDAO = new ProfessorAreaMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                                $centroDAO = new ProfessorCentreMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
                                      
                                $area = $areaDAO->getAreaByName($areaClinica);
                                $centro = $centroDAO->getCentroByName($centroClinico);
                        
                                if($idArea != 1){
                                    if(isset($area) && is_object($area)){
                                        $idArea = $area->getIdArea();
                                    }else{

                                        $area = new Area(null, $areaClinica);     
                                        $idArea = $areaDAO->insert($area);
                                    }          
                                }
                                if($idCentro != 1){
                                    if(isset($centro) && is_object($centro)){
                                        $idCentro = $centro->getIdCentre();
                                    }else{  
                                        $centro = new ProfessorCentre(null, $centroClinico);
                                        $idCentro = $centroDAO->insert($centro);                                                                                                                                                                                                  

                                    }                                
                                }
                                $password = PasswordManager::hash($dni,$coste);
                                $usuario = new User(null, $password, $nombre, $apellido1, $apellido2, $dni, null, $mail, null, 2,$idArea,$idCentro);
                                $res = $userDAO->insert($usuario);
                                if ($res != false) {
                                    $data['INSERT'] = true;
                                    $usuario->setIdUsuario($res);
                                    $studentDAO->insert($usuario, true);
                                    $professorDAO->insert($usuario, true);
                                    mail($mail, 'Alta en el sistema', 'Se te ha registrado en el sistema, tu password es ' . $dni);
                                    ++$contadorInserciones;
                                    $inserted = true;
                                } else {
                                    $isAllStudentAdded = false;
                                    $error = true;
                                }
                            }
                            //Si el usuario existe, se añade solo a la asignatura

                            if ($params['IS_PROFESSOR'] && !$error && $actividadDAO->insertStudentInActivity($params['ID_SUBJECT'], $res, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear()) != false) {
                                if (!$inserted) {
                                    ++$contadorInserciones;
                                }
                            } else {
                                $isAllStudentAdded = false;
                            }
                        }

                        if ($nullCounter >= 3) {
                            $esFinArchivo = true;
                        } else {
                            ++$indiceFila;
                        }


                        $data['CONTENT_MAIN']['INSERTIONS_OK'] = $isAllStudentAdded;
                        $data['CONTENT_MAIN']['INSERTIONS'] = $contadorInserciones;
                        $data['CONTENT_MAIN']['ERROR_LIST'] = $RESULT['ERROR_LIST'];
                        $data['CONTENT_MAIN']['UPLOADED_FILE'] = true;
                        $data['CONTENT_MAIN']['INVALID_FORMAT'] = false;
                        $data['CONTENT_MAIN']['INSERTION_DATA'] = true;
                    }
                } else {

                    $data['CONTENT_MAIN']['UPLOADED_FILE'] = true;
                    $data['CONTENT_MAIN']['INVALID_FORMAT'] = true;
                }
            } else {
                $data['CONTENT_MAIN']['UPLOADED_FILE'] = false;
            }
        } else {
            $data['CONTENT_MAIN']['NO_ID'] = true;
            $data['CONTENT_MAIN']['UPLOADED_FILE'] = false;
        }

        return $data;
    }

    /*
     * Modelo de la administración del sistema
     */

    private function adminSystem($params) {
        $data = null;
        $cursoDAO = new CursoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $programSettings = new ProgramSettingsMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        if ($params['is_new_year']) {
            if (strlen($params['yearFinalDate']) > 0 & strlen($params['yearInitialDate'])) {
                $new_year = new Curso(null, $params['yearInitialDate'], $params['yearFinalDate'], null);
                $new_year->setInitialYear($params['yearInitialDate']);
                $new_year->setFinalYear($params['yearFinalDate']);
                $cursoDAO->insert($new_year);
            }
        }
        if ($params['is_selected_year']) {
            if (strlen($params['yearSelection']) > 0 & strlen($params['yearSelection'])) {
                $res = $programSettings->setCurrentIdYear($params['yearSelection']);
                if ($res != false) {
                    $_SESSION['PROGRAM_SETTINGS']->setIdCurrentYear($params['yearSelection']);
                }
            }
        }

        $data['CONTENT_MAIN']['CURRENT_YEAR'] = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();
        $data['CONTENT_MAIN']['CURSOS'] = $cursoDAO->getAllOrderedBy('initialYear', 0);
        //Es necesario actualizar los datos de la sesión
        $_SESSION['UPDATE_REQUIRED'] = true;
        return $data;
    }

    /**
     * Sube actividades a partir de un fichero
     * @param type $ruta
     * @return int
     */
    private function uploadActivitiesFromFile($ruta) {
        $inputFileType = PHPExcel_IOFactory::identify($ruta);
        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $actividadTipoDAO = new ActividadTipoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $actividadCategoriaDAO = new ActividadCategoriaMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $validadorCodigo = new ValidadorCodigoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        
        if (strpos($inputFileType, 'Excel') !== false) {

            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($ruta);

            $esFinArchivo = false;
            $filaInicial = 2;


            $hojaActual = $objPHPExcel->getActiveSheet();
            $indiceFila = $filaInicial;

            $contadorInserciones = 0;
            $RESULT['IS_ERRORS'] = false;
            while (!$esFinArchivo) {


                $nombre = $hojaActual->getCellByColumnAndRow(0, $indiceFila)->getValue();
                $descripcion = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                $codigo = $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue();                
                $categoria = $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue();
                
                
                $idCategoria= $actividadCategoriaDAO->getActivityCategoryStartsWith($categoria);
                $res = $validadorCodigo->validar($codigo);

                
                if (strlen($nombre) != 0 && !empty($idArea) && !empty($idCategoria) && $res) {

                    $nuevaActividad = $actividad = new Activity(
                            null, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear(), $descripcion, $nombre, null, 1, $codigo,$idCategoria[0]->getIdCategoria()
                    );
                    
                 
                    
                    $actividades = $activityDAO->getActivityStartsWith($nombre);

                    if(is_array($actividades) && count($actividades) >0){
 
                        $actividad = $actividades[0];
                        $idActividad = $actividad->getIdActividad();
                        $nuevaActividad->setIdActividad($idActividad);
                        $activityDAO->update($nuevaActividad);
                        $contadorInserciones++;
                    }
                    else if ($activityDAO->insert($nuevaActividad)) {
                        $contadorInserciones++;
                    }
                    else{
                        $RESULT['IS_ERRORS'] = true;
                        $RESULT['ERROR_LIST'][] = $indiceFila;
                    }                   
                } else {
                    $esFinArchivo = true;
                }
                ++$indiceFila;
            }
            $RESULT['INSERTIONS'] = $contadorInserciones;
            return $RESULT;
        } else {
            return -1;
        }
    }
/**
 * Sube competencias y sus indicadores
 * @param type $ruta
 * @return int
 */
    private function uploadCompetencesFromFile($ruta) {
        $inputFileType = PHPExcel_IOFactory::identify($ruta);
        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceTipoDAO = new CompetenciaTipoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $validadorCodigo = new ValidadorCodigoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);

        if (strpos($inputFileType, 'Excel') !== false) {

            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($ruta);

            $esFinArchivo = false;
            $filaInicial = 2;


            $hojaActual = $objPHPExcel->getActiveSheet();
            $indiceFila = $filaInicial;

            $contadorInserciones = 0;
            $idUltimaCompetencia = -1;
            $RESULT['IS_ERRORS'] = false;
            while (!$esFinArchivo) {


                $insercion = $hojaActual->getCellByColumnAndRow(0, $indiceFila)->getValue();


                if (strcmp($insercion, 'competencia') == 0) {
                    $idUltimaCompetencia = -1;
                    $nombre = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                    $codigo = $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue();
                    $descripcion = $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue();
                    $tipoStr = $hojaActual->getCellByColumnAndRow(4, $indiceFila)->getValue();
                    $observaciones = $hojaActual->getCellByColumnAndRow(5, $indiceFila)->getValue();
                 
                    $tipo = -1;
                    
                    $res = $validadorCodigo->validar($codigo);
   

                    if (strcmp($tipoStr, 'Básica')) {
                        $tipo = 1;
                    } else if (strcmp($tipoStr, 'Intermedia')) {
                        $tipo = 2;
                    } else if (strcmp($tipoStr, 'Avanzada')) {
                        $tipo = 3;
                    }
                    if (strlen($nombre) != 0 && strlen($codigo) != 0 && $tipo != -1 && $res) {
                        $nuevaCompetencia = new Competence(
                                null, $nombre, $descripcion, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear(), null, $observaciones, 1, null, $tipo, 'GII', $this->idUsuario,$codigo
                        );

                        
                        $competencias = $competenceDAO->getCompetencesByCode($codigo);
                        
                        if(is_array($competencias) && count($competencias) > 0){
                            $competencia = $competencias[0];
                            $nuevaCompetencia->setIdCompetencia($competencia->getIdCompetencia());
                            
                            $competenceDAO->update($nuevaCompetencia);
                            $idUltimaCompetencia = $competencia->getIdCompetencia();
                            $contadorInserciones++;                            
                        }else{
                            $resultado = $competenceDAO->insert($nuevaCompetencia);
                            if ($resultado == null) {
                                $competencia = $competenceDAO->getCompetencesStartsWith($codigo);
                                $resultado = $competencia[0]->getIdCompetencia();
                            }
                            if ($resultado != -1) {
                                $contadorInserciones++;
                                $idUltimaCompetencia = $resultado;
                            }
                        }
                    } else {
                        if(strlen($nombre) + strlen($codigo) + strlen($tipo) == 0 ){
                            $esFinArchivo = true;
                        }else{
                            $RESULT['IS_ERRORS'] = true;
                            $RESULT['ERROR_LIST'][] = $indiceFila;                                
                        }
                    }
                } else if (strcmp($insercion, 'indicador') == 0) {

                    $nombre = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                    $codigo = $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue();
                    $descripcion = $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue();

                    $res = $validadorCodigo->validar($codigo);

                    if (strlen($nombre) != 0 && strlen($codigo) != 0 && $res) {
                        $nuevoIndicador = new Indicator($idUltimaCompetencia, null, $nombre, $descripcion, $codigo);
                        
                        $indicadores = $indicatorDAO->getIndicatorByCode($codigo);

                        if(is_array($indicadores) && count($indicadores) > 0){
                            $indicador = $indicadores[0];
                            $nuevoIndicador->setIdIndicator($indicador->getIdIndicator());    
                            
                            $indicatorDAO->update($nuevoIndicador);
                        }else{                        
                            $indicatorDAO->insert($nuevoIndicador);
                        }
                    }else{                     
                        $RESULT['IS_ERRORS'] = true;
                        $RESULT['ERROR_LIST'][] = $indiceFila;                     
                    }
                } else {
                    $esFinArchivo = true;
                }
                ++$indiceFila;
            }
            $RESULT['INSERTIONS'] = $contadorInserciones;
            return $RESULT;
        } else {
            return -1;
        }
    }

    /**
     *  Sube actividades, sus competencias y sus indicadores
     * @param type $ruta
     * @return int
     */
    private function uploadAllFromFile($ruta) {
        $inputFileType = PHPExcel_IOFactory::identify($ruta);
        $activityDAO = new ActividadMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceDAO = new CompetenceMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $indicatorDAO = new IndicadorMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $competenceTipoDAO = new CompetenciaTipoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $actividadTipoDAO = new ActividadTipoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $actividadCategoriaDAO = new ActividadCategoriaMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        $validadorCodigo = new ValidadorCodigoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
        
        if (strpos($inputFileType, 'Excel') !== false) {

            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($ruta);

            $esFinArchivo = false;
            $filaInicial = 2;


            $hojaActual = $objPHPExcel->getActiveSheet();
            $indiceFila = $filaInicial;

            $contadorInserciones = 0;
            $idUltimaCompetencia = -1;
            $idUltimaActividad = -1;
            $RESULT['IS_ERRORS'] = false;
            while (!$esFinArchivo) {


                $insercion = $hojaActual->getCellByColumnAndRow(0, $indiceFila)->getValue();

                if (strcmp($insercion, 'actividad') == 0) {
                    $idUltimaActividad = -1;
                    $nombre = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                    $codigo = $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue();
                    $descripcion = $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue();                   
                    $categoria = $hojaActual->getCellByColumnAndRow(4, $indiceFila)->getValue();
                    
                    
                      
                    $idCategoria= $actividadCategoriaDAO->getActivityCategoryStartsWith($categoria);
                    
                    if (strlen($nombre) != 0) {
                        
                        $res = $validadorCodigo->validar($codigo);

                        if(!empty($idCategoria) && $res){
                            $nuevaActividad = $actividad = new Activity(
                                    null, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear(), $descripcion, $nombre, null, 1,$codigo,$idCategoria[0]->getIdCategoria()
                            );

                            $actividades = $activityDAO->getActivitiesByCode($codigo);

                            if(is_array($actividades) && count($actividades) > 0){

                                $actividad = $actividades[0];
                                $nuevaActividad->setIdActividad($actividad->getIdActividad());
                                $activityDAO->update($nuevaActividad);
                                $contadorInserciones++;
                            }
                            else{
                                $resultado = $activityDAO->insert($nuevaActividad);
                                if ($resultado == null) {
                                    $actividad = $activityDAO->getActivityStartsWith($nombre);
                                    $resultado = $actividad[0]->getIdActividad();
                                }
                                if ($resultado != -1) {
                                    $contadorInserciones++;
                                    $idUltimaActividad = $resultado;
                                }
                            }
                        }else{
                            $RESULT['IS_ERRORS'] = true;
                            $RESULT['ERROR_LIST'][] = $indiceFila;                           
                        }
                    }else{
                        $RESULT['IS_ERRORS'] = true;
                        $RESULT['ERROR_LIST'][] = $indiceFila;
                    }
                } else if (strcmp($insercion, 'competencia') == 0) {
                    $idUltimaCompetencia = -1;
                    $nombre = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                    $codigo = $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue();
                    $descripcion = $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue();
                    $tipoStr = $hojaActual->getCellByColumnAndRow(4, $indiceFila)->getValue();
                    $observaciones = $hojaActual->getCellByColumnAndRow(5, $indiceFila)->getValue();
                    
                    $tipo = -1;
                   
                    
                    
                         
                   

                    if (strcmp($tipoStr, 'Básica')) {
                        $tipo = 1;
                    } else if (strcmp($tipoStr, 'Intermedia')) {
                        $tipo = 2;
                    } else if (strcmp($tipoStr, 'Avanzada')) {
                        $tipo = 3;
                    }
                    
                    $res = $validadorCodigo->validar($codigo);

                    if (strlen($nombre) != 0 && strlen($codigo) != 0 && $tipo != -1 && $res) {
                        $nuevaCompetencia = new Competence(
                                null, $nombre, $descripcion, $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear(), null, $observaciones, 1, null, $tipo, 'GII', $this->idUsuario, $codigo
                        );

                        $competencias = $competenceDAO->getCompetencesByCode($codigo);
                        if(is_array($competencias) && count($competencias) > 0){
                            $competencia = $competencias[0];
                            $nuevaCompetencia->setIdCompetencia($competencia->getIdCompetencia());
                            $competenceDAO->update($nuevaCompetencia);
                            $idUltimaCompetencia = $competencia->getIdCompetencia();
                        }else{
                            $resultado = $competenceDAO->insert($nuevaCompetencia);
                            if ($resultado == null) {
                                $competencia = $competenceDAO->getCompetencesStartsWith($codigo);
                                $resultado = $competencia[0]->getIdCompetencia();
                            }
                            if ($resultado != -1) {
                                $idUltimaCompetencia = $resultado;
                            }
                            if ($idUltimaActividad != -1) {

                                $activityDAO->addCompetenceToActivity($idUltimaActividad, $resultado);
                            }
                        }
                    } else {
                        $RESULT['IS_ERRORS'] = true;
                        $RESULT['ERROR_LIST'][] = $indiceFila;
                    }
                } else if (strcmp($insercion, 'indicador') == 0) {

                    $nombre = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                    $codigo = $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue();
                    $descripcion = $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue();


                    $res = $validadorCodigo->validar($codigo);
   
                    
                    if (strlen($nombre) != 0 && strlen($codigo) != 0 && $res) {
                        $nuevoIndicador = new Indicator($idUltimaCompetencia, null, $nombre, $descripcion, $codigo);
                        $indicadores = $indicatorDAO->getIndicatorByCode($codigo);
                        if(is_array($indicadores) && count($indicadores) > 0){
                            $indicador = $indicadores[0];
                            $nuevoIndicador->setIdIndicator($indicador->getIdIndicator());
                            $indicatorDAO->update($nuevoIndicador);
                        }else{
                            $indicatorDAO->insert($nuevoIndicador);
                        }
                    }else{
                     
                        $RESULT['IS_ERRORS'] = true;
                        $RESULT['ERROR_LIST'][] = $indiceFila;
                     
                    }
                } else {
                    $esFinArchivo = true;
                }
                ++$indiceFila;
            }
            $RESULT['INSERTIONS'] = $contadorInserciones;
            return $RESULT;
        } else {
            return -1;
        }
    }

    /**
     * Gestiona la inserción de competencias y actividades desde un fichero
     * @param type $params
     * @return null
     */
    private function adminUploadFiles($params) {
        $data = null;
        $data['IS_UPLOAD'] = false;
        if (count($params) > 0) {
            require_once 'lib/PHPExcel/PHPExcel.php';

            $data['IS_UPLOAD'] = true;

            $ruta = $params['file']['tmp_name'];
            $tipo = $params['type'];
            $data['TIPO'] = $tipo;

            if (strcmp($tipo, 'activities') == 0) {
                $data['RESULT'] = $this->uploadActivitiesFromFile($ruta);
            } else if (strcmp($tipo, 'competences') == 0) {
                $data['RESULT'] = $this->uploadCompetencesFromFile($ruta);
            } else if (strcmp($tipo, 'all') == 0) {
                $data['RESULT'] = $this->uploadAllFromFile($ruta);
            }
        }


        return $data;
    }
    
    /**
     * Elimina los parámetros de la sesión que establecen un curso actual virtual para el usuario.
     */
    private function undoChangeTime(){
        if(isset($_SESSION['VIRTUAL_YEAR'])){
            unset($_SESSION['VIRTUAL_YEAR']);
            $_SESSION['PROGRAM_SETTINGS']->setIdCurrentYear($_SESSION['REAL_YEAR']);
            $_SESSION['PROGRAM_SETTINGS']->setNameCurrentYear($_SESSION['REAL_YEAR_NAME']);
            unset($_SESSION['REAL_YEAR']);
            unset($_SESSION['REAL_YEAR_NAME']);  
        }
    }
    
    /**
     * Permite establecer un curso virtual para el usuario
     * 
     */
    private function professor_change_year($params) {
        $data = null;        
        $cursoDAO = new CursoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);  
        
        $data['CURRENT_MAIN']['YEARS'] = $cursoDAO->getAll();
        
        if(isset($params['newYear'])){
            if($params['newYear']==0){
                if(isset($_SESSION['VIRTUAL_YEAR'])){
                    $this->undoChangeTime();
                }
                $data['CURRENT_MAIN']['CURRENT_YEAR'] = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();
            }else{
               
                if(!isset($_SESSION['VIRTUAL_YEAR'])){
                    $_SESSION['VIRTUAL_YEAR'] = true;
                    
                    $curso = $cursoDAO->get($_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear());
                    $nuevoCurso = $cursoDAO->get($params['newYear']);
                    
                    $_SESSION['REAL_YEAR'] = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();
                    $_SESSION['REAL_YEAR_NAME'] = $curso->getInitialYear().'/'.$curso->getFinalYear(); 
                    
                    
                    $_SESSION['PROGRAM_SETTINGS']->setIdCurrentYear($params['newYear']);
                    $_SESSION['PROGRAM_SETTINGS']->setNameCurrentYear($nuevoCurso->getInitialYear().'/'.$nuevoCurso->getFinalYear());
                }else{                     
                    $nuevoCurso = $cursoDAO->get($params['newYear']);
                    $_SESSION['PROGRAM_SETTINGS']->setIdCurrentYear($params['newYear']);   
                    $_SESSION['PROGRAM_SETTINGS']->setNameCurrentYear($nuevoCurso->getInitialYear().'/'.$nuevoCurso->getFinalYear()); 
                }
                $data['CURRENT_MAIN']['CURRENT_YEAR'] = $_SESSION['REAL_YEAR'];
                $data['CURRENT_MAIN']['CURRENT_YEAR_NAME'] = $_SESSION['REAL_YEAR_NAME'];
            }
            $data['CURRENT_MAIN']['CHANGED'] = true;
        }else{
            if(isset($_SESSION['VIRTUAL_YEAR'])){
                $data['CURRENT_MAIN']['CURRENT_YEAR'] = $_SESSION['REAL_YEAR'];
            }else{
                $data['CURRENT_MAIN']['CURRENT_YEAR'] = $_SESSION['PROGRAM_SETTINGS']->getIdCurrentYear();
            }
        }      
        
        
       
        return $data;
    }

    /**
     * Genera el contenido de la vista
     * 
     * El array $data contiene toda la información que necesita la vista para
     * poder funcionar. Es un array asociativo de múltiples dimensiones que, al menos, 
     * contiene la siguiente información.
     * 
     * $data['CONTENT_MAIN'] -> Contenido principal solicitado por el cliente
     * $data['CONTENT_ERROR'] -> Información sobre errores producidos, si no
     * se produjo error alguno, estará establecido a null
     * $data['TIPOS'] -> Información sobre filtrados que se pueden realizar o de información
     * adicional que se puede solicitar al modelo en base al contenido generado previamente.
     * 
     * 
     * Para:
     *      TypesEnum::NO_LOGIN:
     * 
     *          Si no hay ninguna sesión iniciada por un usuario en el sistema, devuelve
     *              $data['CONTENT_MAIN']['NO_LOGIN'] = true;  
     *          Además, si el usuario ha intentado iniciar sesión el sistema
     *              $data['CONTENT_MAIN']['LOGIN_TRY'] = true;
     *          Si el intento de inición de sesión se ha realizado correctamente:
     *              $data['CONTENT_MAIN']['RESULT'] = true;
     *          En cambio, si los datos introducidos presentan algún problema:
     *              $data['CONTENT_MAIN']['RESULT'] = false;
     * 
     *          $params requiere, en este caso
     *              -> 0 parámetros, si el usuario no está intentando iniciar sesión
     *              -> $login y $password si está intentando iniciar sesión
     * 
     *      
     *       TypesEnum::LOGOUT:
     *          No se requieren parámetros, $data devolverá
     *              $data['LOGOUT'] = true;
     *          En caso de que la sesión se haya cerrado con éxito. O null si
     *          se ha producido algún error.
     * 
     *      TypesEnum::LOGIN_ONLY:
     *          No se requieren parámetros, $data devolverá:
     *              $data['CONTENT_MAIN'][i]['name'] = Nombre de la competencia
     *              $data['CONTENT_MAIN'][i]['id'] = id de la competencia
     *              $data['CONTENT_MAIN'][i]['descripcion'] = Descripción de la competencia
     *          
     *              Para cada i, competencia matriculada por el usuario
     * 
     *          $data['TIPOS'] = {Básicas, medias, avanzadas} según el tipo de competencias
     *          que presente un usuario.
     *          
     *          Si el usuario no tiene competencias matriculadas, devolverá:
     *              $data['CONTENT_MAIN']['NO_COMPETENCES_AVAILABLE'] = true;
     *  
     *      TypesEnum::COMPETENCE_INFO: 
     *          Muestra información sobre UNA competencia
     * 
     *              $data['CONTENT_MAIN']['id'] = identificador
     *              $data['CONTENT_MAIN']['name'] = nombre
     *              $data['CONTENT_MAIN']['description'] = descripción
     *              $data['CONTENT_MAIN']['year'] = curso
     *              $data['CONTENT_MAIN']['observations'] = observaciones
     *              $data['CONTENT_MAIN']['type'] = tipo
     * 
     *      TypesEnum::MESSAGES:
     *              Si se pasó $params['DELETE_MESSAGES']
     *                  
     *      TypesEnum::MESSAGES_VIEW:
     *              Si solamente se ha solicitado el envío de un mensaje, pero 
     *          aún no se ha enviado el mensaje.
     *                  $data['CONTENT_MAIN']['sendTry'] = false;
     *      TypesEnum::PROFESSOR_VIEW_ONLY:
     *              Devuelve:
     *                  $data['no_activities'] -> Booleano, si vale true, el profesor
     *                      no tiene actividades asociadas
     *                  $data['CONTENT_MAIN'] -> Es un array de actividades que imparte
     *                      el profesor
     * 
     * 
     *      TypesEnum::PROFESSOR_ACTIVITY_VIEW:
     *              Devuelve:
     *                  $data['is_activity_available'] booleano, true si el profesor
     *                      imparte la actividad especificada, false en caso contrario o si la actividad no existe
     *                  $data['CONTENT_MAIN']['activity'] Objeto Activity con la actividad especificada
     *                  $data['CONTENT_MAIN']['isCompetencesAvailables'] booleano, true si el profesor tiene competencias asociadas
     *                  $data['CONTENT_MAIN']['competences'] Array con Competences[]
     * 
     * @return String[]
     */
    public function generate($params) {
        $data = array();

        try {
            switch ($this->tipoModelo) {
                case TypesEnum::NO_LOGIN:

                    if (!isset($params['REMINDER'])) {
                        $data = $this->noLogin($params);
                    } else {//Recordatorio de contraseña                        
                        $data = $this->passwordReminder($params);
                    }
                    break;
                case TypesEnum::LOGOUT:
                    $data = $this->logout();
                    break;
                case TypesEnum::LOGIN_ONLY:
                    $data = $this->login_only($params);
                    break;
                case TypesEnum::COMPETENCE_INFO:
                    $data = $this->competenceInfo($params);
                    break;
                case TypesEnum::INDICATOR_INFO:
                    $data = $this->indicatorInfo($params);
                    break;
                case TypesEnum::MESSAGES:
                    if (isset($params['DELETE_MESSAGES'])) {
                        $this->deleteMessages($params['DELETE_MESSAGES']);
                    }
                    $data = $this->messagesInfo($params);
                    break;
                case TypesEnum::MESSAGE_VIEW:
                    $data = $this->viewMessage($params);
                    break;
                case TypesEnum::MESSAGE_SEND:
                    $data = $this->sendMessage($params);
                    break;
                case TypesEnum::REGISTER:
                    $data = $this->register($params);
                    break;
                case TypesEnum::PROFESSOR_VIEW_ONLY:
                    $data = $this->professorViewOnly($params);
                    break;
                case TypesEnum::PROFESSOR_ACTIVITY_VIEW:
                    $data = $this->professorActivityView($params);
                    break;
                case TypesEnum::PROFESSOR_COMPETENCE_EVAL:
                    $data = $this->professorCompetenceEval($params);
                    break;
                case TypesEnum::PROFESSOR_SESSIONS_LIST:
                    $data = $this->professor_sessions_list($params);
                    break;
                case TypesEnum::PROFESSOR_STUDENTS_LIST:
                    $data = $this->professor_students_list($params);
                    break;
                case TypesEnum::PROFESSOR_COMPETENCE_VIEW:
                    $data = $this->professor_competence_view($params);
                    break;
                case TypesEnum::PROFESSOR_SESSIONS_ASSISTANCE:
                    $data = $this->professor_sessions_assistance($params);
                    break;
                case TypesEnum::PROFESOR_INSERT_STUDENTS:
                    $data = $this->UserAddStudentsFromFile($params);
                    break;
                case TypesEnum::ADMIN_VIEW_ONLY:
                    $data = $this->adminView($params);
                    break;
                case TypesEnum::STUDENT_ACTIVITIES:
                    $data = $this->student_view_activities($params);
                    break;
                case TypesEnum::STUDENT_ACTIVITY_INFO:
                    $data = $this->student_view_activity($params);
                    break;
                case TypesEnum::STUDENT_ACTIVITY_ASSISTANCE:
                    $data = $this->student_view_activitiesAssistance($params);
                    break;
                case TypesEnum::USER_PROFILE:
                    $data = $this->view_user_profile($params);
                    break;
                case TypesEnum::USER_VIEW_COMPETENCES:
                    $data = $this->view_user_viewCompetence($params);
                    break;
                case TypesEnum::PROFESSOR_STUDENTS_EVAL:
                    $data = $this->view_professor_student_eval($params);
                    break;
                case TypesEnum::USER_CONTACT:
                    $data = $this->view_user_contact($params);
                    break;
                case TypesEnum::USER_CHANGEROL:
                    $data = $this->userChangeRol($params);
                    break;
                case TypesEnum::STUDENT_DOWNLOAD_REPORT:
                    $data = $this->studentDownloadReport($params);
                    break;
                case TypesEnum::PROFESSOR_EVALUATION_REPORT:
                    $data = $this->view_professor_student_eval($params);
                    break;
                case TypesEnum::PROFESSOR_CHANGE_YEAR:
                    $data = $this->professor_change_year($params);
                    break;                
                case TypesEnum::ADMIN_ACTIVITIES:
                    $data = $this->adminActivities($params);
                    break;
                case TypesEnum::ADMIN_COMPETENCE_ACTIVITIES:
                    $data = $this->adminCompetenceActivities($params);
                    break;
                case TypesEnum::ADMIN_PROFESSOR_ACTIVITIES:
                    $data = $this->adminProfessorActivities($params);
                    break;
                case TypesEnum::ADMIN_COMPETENCES:
                    $data = $this->adminCompetences($params);
                    break;
                case TypesEnum::ADMIN_COMPETENCES_INDICATOR:
                    $data = $this->adminCompetencesIndicator($params);
                    break;
                case TypesEnum::USER_MY_PROFILE:
                    $data = $this->userProfile($params);
                    break;
                case TypesEnum::USER_ERROR:
                    $data = $this->userError($params);
                    break;
                case TypesEnum::USER_HELP:
                    $data = $this->userHelp($params);
                    break;
                case TypesEnum::ADMIN_SESSIONS_LIST:
                    $data = $this->professor_sessions_list($params);
                    break;                 
                case TypesEnum::ADMIN_USERS:
                    $data = $this->adminUsers($params);
                    
                    if ($params['file'] != false) {
                        
                        $dataInsertion = $this->UserAddStudentsFromFile($params);
                        $data['INSERTION_DATA'] = $dataInsertion;
                    }
                    break;
                case TypesEnum::ADMIN_SYSTEM:
                    $data = $this->adminSystem($params);
                    break;
                case TypesEnum::ADMIN_UPLOAD_FROM_FILE:
                    $data = $this->adminUploadFiles($params);
                    break;
            }
        } catch (Exception $ex) {
            $data['CONTENT_ERROR'] = $ex->getMessage();
        }

        if ($_SESSION['login'] && $this->tipoModelo != TypesEnum::NO_LOGIN) {
            $messagesDAO = new MessageMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
            $data['NUMBER_OF_MESSAGES'] = $messagesDAO->getNumberOfMessagesNotReadByUser($this->idUsuario);
        }


        return $data;
    }



}
