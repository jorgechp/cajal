<?php

try {
    define("FOLDER", 'Layers/UI/web/Aplicacion_web');
    require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . '.php');
    require(constant("FOLDER") . '/view/Dictionary.php');
    require(constant("FOLDER") . '/view/MVC_view.php');
    require(constant("FOLDER") . '/view/MVC_view_nologin.php');
    require(constant("FOLDER") . '/view/MVC_view_competences.php');
    require(constant("FOLDER") . '/model/typesenum.php');
    require(constant("FOLDER") . '/model/rolEnum.php');
    require(constant("FOLDER") . '/model/ModelGenerator.php');

    
    $vista = null;

    /**
     * Selección del modelo. La variable $modelo establece la vista que se va a procesar, y otorga
     * información al modelo sobre los datos que necesita obtener.
     */
    $modelo = TypesEnum::NO_LOGIN; // Modelo por defecto
    $rol = null;
    $isLoginTry = false;

    $selected = null;
        
    if (isset($_SESSION['login']) && $_SESSION['login']) {
        $sessionObject = $_SESSION['sessionObject'];
        $modelo = TypesEnum::LOGIN_ONLY; // Sesión iniciada 
        $rol = $sessionObject->getRol();
    }else{
        $rol = RolEnum::USER_NO_LOGIN;
    }
    
    
     
    /**
     * ROL DE ESTUDIANTE
     */
    if ($rol == RolEnum::USER_STUDENT) { 
        
        if (isset($_GET)) {
            if (isset($_GET['competence'])) {
                if (!isset($_GET['indicator'])) {
                    $modelo = TypesEnum::COMPETENCE_INFO;
                    require(constant("FOLDER") . '/view/MVC_view_competenceInfo.php');                    
                } else {
                    $modelo = TypesEnum::INDICATOR_INFO;
                    require(constant("FOLDER") . '/view/MVC_view_indicator.php');
                }                
                require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Competences.php');
            }
            else if(isset ($_GET['studentActivities'])){                
                if(isset($_GET['view'])){
                    $modelo = TypesEnum::STUDENT_ACTIVITY_INFO;
                    require(constant("FOLDER") . '/view/MVC_view_students_activityInfo.php');                    
                }else{
                    $modelo = TypesEnum::STUDENT_ACTIVITIES;
                    require(constant("FOLDER") . '/view/MVC_view_student_activities.php');
                }
                require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Activities.php');
                
            }else if(isset ($_GET['activityAssistance'])){ 
              
                $modelo = TypesEnum::STUDENT_ACTIVITY_ASSISTANCE;
                require(constant("FOLDER") . '/view/MVC_view_student_activity_assistance.php');
                require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Assistance.php');     
            }
            //Descargar informe del estudiante
            else if(isset ($_GET['downloadReport'])){
                $modelo = TypesEnum::STUDENT_DOWNLOAD_REPORT;
                require(constant("FOLDER") . '/view/MVC_view_student_report.php');                
            }    
        }
        
    } 
    
    /**
     * ROL DE PROFESOR
     */
    else if ($rol == RolEnum::USER_PROFESSOR) { 
        
        if (isset($_GET['activityEval'])) {
            if (isset($_GET['competenceEval'])) { //Mostrar tabla de competencias o Evaluación enviada
                $modelo = TypesEnum::PROFESSOR_COMPETENCE_EVAL;
                require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Table.php');
                require(constant("FOLDER") . '/view/MVC_view_professor_competenceEval.php');
            } else {
                $modelo = TypesEnum::PROFESSOR_ACTIVITY_VIEW;
                require(constant("FOLDER") . '/view/MVC_professor_activity_view.php');
            }
        }else if(isset($_GET['studentsEval'])){ 
            $modelo = TypesEnum::PROFESSOR_STUDENTS_EVAL;
            require(constant("FOLDER") . '/view/MVC_view_professor_student_eval.php');
            require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'StudentEval.php');
        }else if(isset($_GET['sessions'])){ 
            $modelo = TypesEnum::PROFESSOR_SESSIONS_LIST;
            require(constant("FOLDER") . '/view/MVC_view_professor_sessions_list.php');
            require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Session.php');
        }else if(isset($_GET['students'])){
            $modelo = TypesEnum::PROFESSOR_STUDENTS_LIST;
            require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'StudentListMessages.php');
            require(constant("FOLDER") . '/view/MVC_view_professor_students_list.php');            
        }else if(isset ($_GET['professorCompetences'])){                
                $modelo = TypesEnum::PROFESSOR_COMPETENCE_VIEW;
                require(constant("FOLDER") . '/view/MVC_view_professor_view_competences.php');
                require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Competences.php');
         }
        else if(isset ($_GET['sessionsAsistance'])){                
                $modelo = TypesEnum::PROFESSOR_SESSIONS_ASSISTANCE;
                require(constant("FOLDER") . '/view/MVC_view_professor_assistance_list.php');
                require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Assistance.php');
         }  
         else if(isset ($_GET['changeYear'])){                
                $modelo = TypesEnum::PROFESSOR_CHANGE_YEAR;
                require(constant("FOLDER") . '/view/MVC_view_professor_TimeMachine.php');
                require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'TimeMachine.php');
         }  
         //Informe en excel de un estudiante
        else if(isset ($_GET['evaluationReport'])){                
                $modelo = TypesEnum::PROFESSOR_EVALUATION_REPORT;
                require(constant("FOLDER") . '/view/MVC_view_evaluationReport.php');
                require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Assistance.php');
         } 

         else {
            $modelo = TypesEnum::PROFESSOR_VIEW_ONLY;
            require(constant("FOLDER") . '/view/MVC_view_professor.php');
            require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'ProfessorView.php');
        }
        
      
    }
    
    /**
     * ROL DE ADMINISTRADOR
     */
    else if($rol == RolEnum::USER_ADMIN){
         if(isset($_GET['adminActivities'])){
             if(!isset($_GET['excel'])){
                if (isset($_GET['sessionList'])){                 
                    $modelo = TypesEnum::ADMIN_SESSIONS_LIST;
                    require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Session.php');
                    require(constant("FOLDER") . '/view/MVC_view_adminActivitiesSessions.php');           
                }else if (isset($_GET['excelActivitiesProfessors'])){                 
                    $modelo = TypesEnum::ADMIN_ACTIVITIES;
                    require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Activities.php');
                    require(constant("FOLDER") . '/view/MVC_view_adminActivities.php');           
                }                
                else{     
                    $modelo = TypesEnum::ADMIN_ACTIVITIES;
                    require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Activities.php');
                    require(constant("FOLDER") . '/view/MVC_view_adminActivities.php');
                }
             }else{
                $modelo = TypesEnum::ADMIN_ACTIVITIES;
                require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Activities.php');
                require(constant("FOLDER") . '/view/MVC_view_adminActivitiesExcel.php');                 
             }
            
        }else if(isset($_GET['competencesActivity'])){     
            $modelo = TypesEnum::ADMIN_COMPETENCE_ACTIVITIES;
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Activities.php');
            require(constant("FOLDER") . '/view/MVC_view_competencesActivities.php');           
        }else if(isset($_GET['professorsActivity'])){     
            $modelo = TypesEnum::ADMIN_PROFESSOR_ACTIVITIES;
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Activities.php');
            require(constant("FOLDER") . '/view/MVC_view_adminProfessorsActivities.php');           
        }
        else if(isset($_GET['adminCompetences'])){     
            $modelo = TypesEnum::ADMIN_COMPETENCES;
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Competences.php');
            if(!isset($_GET['excel'])){                
                require(constant("FOLDER") . '/view/MVC_view_adminCompetences.php');           
            }else{
                require(constant("FOLDER") . '/view/MVC_view_adminCompetencesExcel.php'); 
            }
        }        
        else if(isset($_GET['competencesIndicator'])){     
            $modelo = TypesEnum::ADMIN_COMPETENCES_INDICATOR;
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Competences.php');
            require(constant("FOLDER") . '/view/MVC_view_admin_competencesIndicator.php');           
        } 
        else if(isset($_GET['adminUsers'])){                 
            $modelo = TypesEnum::ADMIN_USERS;
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Users.php');
            if(!isset($_GET['excel'])){                  
                require(constant("FOLDER") . '/view/MVC_view_admin_users.php');  
            }else{
                require(constant("FOLDER") . '/view/MVC_view_admin_usersExcel.php');  
            }

        }  
        else if(isset($_GET['adminSystem'])){                 
            $modelo = TypesEnum::ADMIN_SYSTEM;
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'AdminSystem.php');
            require(constant("FOLDER") . '/view/MVC_view_admin_system.php');           
        } 
        else if(isset($_GET['uploadFromFile'])){                 
            $modelo = TypesEnum::ADMIN_UPLOAD_FROM_FILE;
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'AdminFile.php');
            require(constant("FOLDER") . '/view/MVC_view_admin_file.php');           
        }         
        else{               
            $modelo = TypesEnum::ADMIN_VIEW_ONLY;
            require(constant("FOLDER") . '/view/MVC_view_admin.php');
            require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Reports.php');
        }
        
    }
    
    /**
     * USUARIOS NO REGISTRADOS
     */
    
    if($rol == RolEnum::USER_NO_LOGIN){        
        if (isset($_GET['register'])) {             
            $modelo = TypesEnum::REGISTER;
            require(constant("FOLDER") . '/view/MVC_view_userRegister.php');
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Register.php');
        }
    }
  
    /**
     * PROFESORES Y ADMINISTRADORES
     */
   if($rol == RolEnum::USER_PROFESSOR ||$rol == RolEnum::USER_ADMIN ){   
             //Insertar estudiantes de forma masiva mediante un archivo
        
        if(isset ($_GET['insertStudents'])){   
                if($rol == RolEnum::USER_ADMIN){
                    require_once(constant("FOLDER") . '/view/MVC_view_admin_users.php');
                    require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Users.php');
                    $modelo = TypesEnum::ADMIN_USERS;
                }else{
                    $modelo = TypesEnum::PROFESOR_INSERT_STUDENTS;
                    require(constant("FOLDER") . '/view/MVC_view_professor_addStudentsFromFile.php');                
                    require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'addStudentsFromFile.php');                    
                }

                
         }
    }

    
     /**
     * TODOS LOS USUARIOS
     */
    if (isset($_GET)) {
        if (isset($_GET['messages'])) {
            if (isset($_SESSION['login']) && $_SESSION['login']) {
                if (isset($_GET['view'])) {
                    $modelo = TypesEnum::MESSAGE_VIEW;
                    require_once(constant("FOLDER") . '/view/MVC_view_message_info.php');
                } else if (isset($_GET['send'])) {
                    $modelo = TypesEnum::MESSAGE_SEND;
                   
                    require_once(constant("FOLDER") . '/view/MVC_send_message.php');
                } else {
                    $modelo = TypesEnum::MESSAGES;
                    require_once(constant("FOLDER") . '/view/MVC_view_messages.php');
                }
                require (constant("FOLDER") . "/i10n/" . $GLOBALS['UI_LANG'] . '.php');
                require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Messages.php');
                
            }
        }else if(isset($_GET['viewCompetence'])){
            require(constant("FOLDER") . '/view/MVC_view_user_competence_Info.php');
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Messages.php');
            $modelo = TypesEnum::USER_VIEW_COMPETENCES;
        }else if(isset($_GET['user'])){
                $modelo = TypesEnum::USER_PROFILE;
                require(constant("FOLDER") . '/view/MVC_view_user_profile.php');
                require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'UserProfile.php');
            } else if (isset($_GET['login'])) {
            if (!$_SESSION['login']) {
                $modelo = TypesEnum::NO_LOGIN;
                $isLoginTry = true;
                
            } else {
                if($rol == RolEnum::USER_STUDENT){
                    
                    $modelo = TypesEnum::LOGIN_ONLY;
                }else if($rol == RolEnum::USER_PROFESSOR){
                    $modelo = TypesEnum::PROFESSOR_VIEW_ONLY;
                }else{                    
                    $modelo = TypesEnum::ADMIN_VIEW_ONLY;
                }
            }
        } else if (isset($_GET['logout'])) {
            if ($_SESSION['login']) {
                $modelo = TypesEnum::NO_LOGIN;
               
            }
            $modelo = TypesEnum::LOGOUT;
        }else if (isset($_GET['contact'])) {
            $modelo = TypesEnum::USER_CONTACT;
            require(constant("FOLDER") . '/view/MVC_view_user_contact.php');
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Contact.php');
        }else if (isset($_GET['changeRol'])) { 
            $modelo = TypesEnum::USER_CHANGEROL;            
        }//Acceso al perfil de usuario
        else if(isset($_GET['myProfile'])){
            $modelo = TypesEnum::USER_MY_PROFILE;
            require(constant("FOLDER") . '/view/MVC_view_user_my_profile.php');
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Profile.php');
        }
        else if(isset($_GET['help'])){
            $modelo = TypesEnum::USER_HELP;
            require(constant("FOLDER") . '/view/MVC_view_user_help.php');
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Help.php');
        }        
        else if(isset($_GET['error'])){
            $modelo = TypesEnum::USER_ERROR;
            require(constant("FOLDER") . '/view/MVC_view_user_error.php');
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Error.php');
        }  
        else if(isset($_GET['about'])){
            $modelo = TypesEnum::USER_ABOUT;
            require(constant("FOLDER") . '/view/MVC_view_user_about.php');
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'About.php');
        }            
    }





    /**
     * Asignación de parámetros al array $params, que establecerá todos los parámetros
     * que necesita el modelo para procesar la información requerida
     */
    $params = array();
    switch ($modelo) {
        case TypesEnum::LOGOUT:
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'NoLogin.php');
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $noLoginInfo;
            $vista = new MVC_view_nologin();
            $selected = 12;
            break;
        case TypesEnum::NO_LOGIN:
            require (constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'NoLogin.php');
            if ($isLoginTry) {
                $params[] = filter_input(INPUT_POST, 'userLogin', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'userPassword', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'userRol', FILTER_SANITIZE_SPECIAL_CHARS);                
            }
            
            if(isset($_POST['LOGIN_PASSWORD_REMINDER'])){
                
                $params['REMINDER'] = 1;
                $params['USERID'] = filter_input(INPUT_POST, 'userLogin', FILTER_SANITIZE_SPECIAL_CHARS);                
            }
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $noLoginInfo;
            $vista = new MVC_view_nologin();
            $selected = 1;
            break;
        case TypesEnum::LOGIN_ONLY:           
            if($rol == RolEnum::USER_STUDENT){ 
                
                $vista = new MVC_view_competences();
                if(isset($_GET['filter_Competence'])){
                    $params[] = filter_input(INPUT_GET, 'filter_Competence', FILTER_SANITIZE_SPECIAL_CHARS);                    
                }
                //Ver competencias superadas
                if(isset($_GET['view_passed'])){
                    $params['passed'] = true;
                }
            }else if($rol == RolEnum::USER_PROFESSOR){                
                $vista = new MVC_view_professor();
            }else{               
               
               $vista = new MVC_view_admin_users();
            }
            $selected = 1;
            break;
        case TypesEnum::COMPETENCE_INFO:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_competences;
            
            $vista = new MVC_view_competenceInfo();
            $params[] = filter_input(INPUT_GET, 'competence', FILTER_SANITIZE_SPECIAL_CHARS);
            $selected = 1;
            break;
        case TypesEnum::INDICATOR_INFO:
             $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_competences;
            
            $vista = new MVC_view_indicator();
            $params[] = filter_input(INPUT_GET, 'competence', FILTER_SANITIZE_SPECIAL_CHARS);
            $params[] = filter_input(INPUT_GET, 'indicator', FILTER_SANITIZE_SPECIAL_CHARS);
            $params[] = $_SESSION['sessionObject']->getIdUsuario();
            $params[] = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_SPECIAL_CHARS);
            
            $selected = 1;
            break;
        case TypesEnum::MESSAGES:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_message_index;


            if (isset($_GET['Sent'])) {
                $params['SENT_MESSAGES'] = true;
            }
            $vista = new MVC_view_messages();

            if (isset($_POST['manageMessages'])) {
                $params['DELETE_MESSAGES'] = filter_input(INPUT_POST, 'delete_messages', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
            }
            $selected = 10;
            break;
        case TypesEnum::MESSAGE_VIEW:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_message_index;
            $vista = new MVC_view_message_info();
            $params = filter_input(INPUT_GET, 'view', FILTER_SANITIZE_SPECIAL_CHARS);
            
            $selected = 3;
            break;
        case TypesEnum::MESSAGE_SEND:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_message_index;
            $vista = new MVC_send_message();
            if (isset($_POST['messageSend'])) {
                $params['recipients'] = filter_input(INPUT_POST, 'recipients_list', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['subject'] = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['message'] = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['isSending'] = true;
            }
            if(isset($_GET['idRecipient'])) {
                $params['idRecipient'] = filter_input(INPUT_GET, 'idRecipient', FILTER_SANITIZE_SPECIAL_CHARS);               
            }
            $params['type_message'] = filter_input(INPUT_POST, 'type_message', FILTER_SANITIZE_SPECIAL_CHARS);
            if($params['type_message'] == 0){ // equivale a mensaje grupal, 1 equivale a mensaje individual
                $params['idGroup'] = filter_input(INPUT_POST, 'recipients_group', FILTER_SANITIZE_SPECIAL_CHARS);
            }
         
            $selected = 3;
            break;
        case TypesEnum::REGISTER:
            if (isset($_POST['registerSubmit'])) {
                $params[] = true;
                $params[] = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'userLastname1', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'userLastname2', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'userMail', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'userPassword', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'userPasswordRetype', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'userPhone', FILTER_SANITIZE_SPECIAL_CHARS);
            } else {
                $params[] = false;
            }
            $vista = new MVC_view_userRegister();
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_index_register;
            
            $selected = 2;
            break;
        case TypesEnum::PROFESSOR_VIEW_ONLY:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_professor;
            
            $vista = new MVC_view_professor();
            
            $selected = 1;
            break;
        case TypesEnum::PROFESSOR_ACTIVITY_VIEW:
            $vista = new MVC_professor_activity_view();
            $params[] = filter_input(INPUT_GET, 'activityEval', FILTER_SANITIZE_SPECIAL_CHARS);
            
            $selected = 1;
            break;
        case TypesEnum::PROFESSOR_COMPETENCE_EVAL:
            
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_index_TABLE;

            $vista = new MVC_view_professor_competenceEval();
            $params[] = filter_input(INPUT_GET, 'competenceEval', FILTER_SANITIZE_SPECIAL_CHARS);
            $params[] = filter_input(INPUT_GET, 'activityEval', FILTER_SANITIZE_SPECIAL_CHARS);

            if (isset($_POST['competenceEvalSendEvaluation'])) {                
                $params['evaluationSent'] = true;
                $params['evaluationSentAll'] = false;
                $params['evaluationInput'] = filter_input_array(INPUT_POST);
                
            }else if(isset($_POST['competenceEvalSendEvaluationAll'])){
               
                $params['evaluationSent'] = false;
                $params['evaluationSentAll'] = true;
                //Aplicar una calificación de $params['evaluationInput']['calification']
                $params['evaluationInput']['calification'] = filter_input(INPUT_POST, 'competenceEvalSendEvaluationApplyAll', FILTER_SANITIZE_SPECIAL_CHARS);
                //Para toda la clase o para los que asistieron
                $params['evaluationInput']['filter'] = filter_input(INPUT_POST, 'competenceEvalSendEvaluationApplyFilter', FILTER_SANITIZE_SPECIAL_CHARS);
                //En el indicador o en todos los indicadores
                $params['evaluationInput']['onIndicator'] = filter_input(INPUT_POST, 'competenceEvalSendEvaluationApplyOnIndicator', FILTER_SANITIZE_SPECIAL_CHARS);
                //Aplicar evaluación solamente a estudiantes seleccionados
                if(isset($_POST['studentMark'])){
                    $params['studentMark'] = filter_var_array($_POST['studentMark']);
                }
                
            }else {
                $params['evaluationSent'] = false;
                $params['evaluationSentAll'] = false;
            }
           
            if(isset($_POST['competenceEvalSelectedSession'])){                
                $params['evaluationInput']['competenceEvalSelectedSession'] = filter_input(INPUT_POST, 'competenceEvalSelectedSession', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            
            $selected = 1;
            break;
        case TypesEnum::PROFESSOR_SESSIONS_LIST:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $activities_sessions;
            $params[] = filter_input(INPUT_GET, 'sessions', FILTER_SANITIZE_SPECIAL_CHARS);
            if(isset($_POST['sessionNew'])){
                $params[] = true;
                $params[] = filter_input(INPUT_POST, 'sessionDateStart', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionDateStartHour', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionDateStartMinute', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionDateDuration', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionPassword', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionPlaceSelect', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if(isset($_GET['remove'])){
                $params['remove'] = filter_input(INPUT_GET, 'remove', FILTER_SANITIZE_SPECIAL_CHARS);
                
            }            
            $vista = new MVC_view_professor_sessions_list();
            
            $selected = 1;
            break;
        case TypesEnum::PROFESSOR_STUDENTS_LIST:            
            $params[] = filter_input(INPUT_GET, 'students', FILTER_SANITIZE_SPECIAL_CHARS);
           
           if(isset($_POST['studentNew'])){
                $params[] = true;
                $params[] = filter_input(INPUT_POST, 'idStudent', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if(isset($_GET['remove'])){
                $params['remove'] = filter_input(INPUT_GET, 'remove', FILTER_SANITIZE_SPECIAL_CHARS);
                
            }  
            
            
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_students_list;
            
            $vista = new MVC_view_professor_students_list();    
            
            $selected = 1;
            break;
        case TypesEnum::ADMIN_VIEW_ONLY:  
            
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_index_reports;              

            $vista = new MVC_view_admin();
            $params['solve'] = filter_input(INPUT_GET, 'solveNotification', FILTER_SANITIZE_SPECIAL_CHARS);
            $params['delete'] = filter_input(INPUT_GET, 'deleteNotification', FILTER_SANITIZE_SPECIAL_CHARS);
            
            $selected = 1;
            break;
        case TypesEnum::STUDENT_ACTIVITIES:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $activities_list;
            
            if(isset($_GET['year'])){
                $params[] = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            $vista = new MVC_view_student_activities();
            
            $selected = 2;
            break;
        case TypesEnum::STUDENT_ACTIVITY_INFO:
            if(isset($_GET['view'])){
                $params[] = filter_input(INPUT_GET, 'view', FILTER_SANITIZE_SPECIAL_CHARS);
            }else{
                $params[] = false;
            }
            $vista = new MVC_view_students_activityInfo();
            
            $selected = 2;
            break;
        case TypesEnum::STUDENT_ACTIVITY_ASSISTANCE:
            
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $activity_assistance;
            $params[] = filter_input(INPUT_GET, 'activityAssistance', FILTER_SANITIZE_SPECIAL_CHARS);
            if(isset($_GET['checkSession'])){
                $params[] = filter_input(INPUT_GET, 'checkSession', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionPassword', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            $vista = new MVC_view_student_activity_assistance();
            
            $selected = 2;
            break;
         case TypesEnum::USER_PROFILE:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $userProfileString;
            
            if(isset($_GET['user'])){
                $params[] = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            $vista = new MVC_view_user_profile();
            
            //Aquí no se necesita especificar la variable $selected
            break;
            
        case TypesEnum::PROFESSOR_COMPETENCE_VIEW:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_competences;    
                        
            $vista = new MVC_view_professor_view_competences();
            
            $selected = 2;
            break;
        case TypesEnum::PROFESSOR_STUDENTS_EVAL:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_students_eval;              

           //studentsEval contiene información de la actividad de la que se quiere mostrar la tabla de evaluación
            $params[] = filter_input(INPUT_GET, 'studentsEval', FILTER_SANITIZE_SPECIAL_CHARS);
            
            $vista = new MVC_view_professor_student_eval();    
            
            $selected = 1;
            break;
        case TypesEnum::PROFESSOR_SESSIONS_ASSISTANCE:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $activity_assistance;              

           //studentsEval contiene información de la actividad de la que se quiere mostrar la tabla de evaluación
            $params[] = filter_input(INPUT_GET, 'sessionsAsistance', FILTER_SANITIZE_SPECIAL_CHARS);
            if(isset($_GET['formSubmit'])){
                $params[] = filter_input(INPUT_GET, 'viewSession', FILTER_SANITIZE_SPECIAL_CHARS);
            }else{            
                $params[] = filter_input(INPUT_GET, 'change', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_GET, 'student', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            
            $vista = new MVC_view_professor_assistance_list();      
            
            $selected = 1;
            break;        
        case TypesEnum::PROFESOR_INSERT_STUDENTS: 
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_students_eval;        
              
            
            if(isset($_POST['uploadedFile'])){
                $params['file'] = $_FILES["file"];
                $params['IS_UPLOADED_FILE'] = true;
               
            }else{
                $params['IS_UPLOADED_FILE'] = false;
            }
            
            if(isset($_GET['subject'])){
                $params['ID_SUBJECT'] = filter_input(INPUT_GET, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
            }else{
                $params['ID_SUBJECT'] = false;
            }
            
            if($rol == RolEnum::USER_ADMIN){
                $params['IS_PROFESSOR'] = false;
                $params['file'] = true;
                $vista = new MVC_view_admin_users();
            }
            else{
                $params['IS_PROFESSOR'] = true;
                $vista = new MVC_view_addStudentsFromFile();
            }

            
            $selected = 2;
            
            break;
        case TypesEnum::USER_VIEW_COMPETENCES:
            $params[] = filter_input(INPUT_GET, 'viewCompetence', FILTER_SANITIZE_SPECIAL_CHARS);            
            $params[] = filter_input(INPUT_GET, 'indicator', FILTER_SANITIZE_SPECIAL_CHARS);
            $vista = new MVC_view_user_competence_Info();            
            break;
        case TypesEnum::USER_CONTACT:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_contact;   
            
            if(isset($_POST['CONTACT_SUBMIT'])){
                $params['check'] = true;
                $params['CONTACT_TYPE_NOTIFICATION'] = filter_input(INPUT_POST, 'CONTACT_TYPE_NOTIFICATION', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['CONTACT_SUBJECT'] = filter_input(INPUT_POST, 'CONTACT_SUBJECT', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['CONTACT_TEXT'] = filter_input(INPUT_POST, 'CONTACT_TEXT', FILTER_SANITIZE_SPECIAL_CHARS);
            }else{
                $params['check'] = false;
            }
            
            $vista = new MVC_view_user_contact(); 
            
            
            break;
        case TypesEnum::USER_CHANGEROL:            
            $params[] = filter_input(INPUT_GET, 'changeRol', FILTER_SANITIZE_SPECIAL_CHARS);
            break;
        case TypesEnum::USER_ERROR:
            
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $error_strings;      
            
            $vista = new MVC_view_user_error();
            
            break;
        case TypesEnum::ADMIN_ACTIVITIES:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $activities_list;
            $params['uploadAssociation'] = false;
            
            if(!isset($_GET['excel']) && !isset($_GET['excelActivitiesProfessors'])){
            
                if(isset($_POST['formActivated'])){
                    $params['input'] = true;
                    $params['dataInput'] = filter_input_array(INPUT_POST);
                }else{
                    $params['input'] = false;
                }

                if(isset($_GET['buscador'])){
                    $params['buscador'] = true;
                    $params['activity_name'] = filter_input(INPUT_POST, 'USER_LOOK_ACTIVITY', FILTER_SANITIZE_SPECIAL_CHARS);
                    
                    $params['filter_title'] = filter_input(INPUT_POST, 'USER_SEARCH_TITLE', FILTER_SANITIZE_SPECIAL_CHARS);
                    $params['filter_type'] = filter_input(INPUT_POST, 'USER_SEARCH_TYPE', FILTER_SANITIZE_SPECIAL_CHARS);
                }else{
                    $params['buscador'] = false;
                }
                $vista = new MVC_view_adminActivities();
            }else if(isset($_GET['excelActivitiesProfessors'])){                
                $params['buscador'] = false;
                $params['input'] = true;
                $params['uploadAssociation'] = true;
                $params['dataInput'] = filter_input_array(INPUT_POST); 
                
                $params['file'] = $_FILES["file"];  
                $vista = new MVC_view_adminActivities();
            }   
            else{
                $params['buscador'] = true;
                $params['input'] = false;
                $params['activity_name'] = filter_input(INPUT_GET, 'excel', FILTER_SANITIZE_SPECIAL_CHARS);   
                $params['filter_title'] = filter_input(INPUT_GET, 'excel_title', FILTER_SANITIZE_SPECIAL_CHARS); 
                $params['filter_type'] = filter_input(INPUT_GET, 'excel_type', FILTER_SANITIZE_SPECIAL_CHARS); 
                
                if(isset($_GET['competences'])){
                    $params['competences'] = true;
                   
                }else{
                    $params['competences'] = false;
                }
                
                if(isset($_GET['full'])){
                    $params['full'] = true;
                }else{
                    $params['full'] = false;
                }                
                
                $vista = new MVC_view_adminActivitiesExcel();
            }
            $selected = 2;
            
            
            break;
         case TypesEnum::ADMIN_COMPETENCE_ACTIVITIES:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $activities_list;

            $params[] = filter_input(INPUT_GET, 'competencesActivity', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if(isset($_POST['search_competence'])){
                $params['SEARCH'] = filter_input(INPUT_POST, 'search_competence_input', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            
            if(isset($_GET['removeCompetence'])){
                $params['REMOVE'] = filter_input(INPUT_GET, 'removeCompetence', FILTER_SANITIZE_SPECIAL_CHARS);
            }            

            
            if(isset($_GET['addCompetence'])){
                $params['ADD'] = filter_input(INPUT_GET, 'addCompetence', FILTER_SANITIZE_SPECIAL_CHARS);
            }      
            
            
            $vista = new MVC_view_competencesActivities();
            $selected = 2;
            break;
        case TypesEnum::ADMIN_PROFESSOR_ACTIVITIES:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $activities_list;

            $params[] = filter_input(INPUT_GET, 'professorsActivity', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if(isset($_POST['search_professor'])){
                $params['SEARCH'] = filter_input(INPUT_POST, 'search_professor_input', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            
            if(isset($_GET['removeProfessor'])){
                $params['REMOVE'] = filter_input(INPUT_GET, 'removeProfessor', FILTER_SANITIZE_SPECIAL_CHARS);
            }            

            
            if(isset($_GET['addProfessor'])){
                $params['ADD'] = filter_input(INPUT_GET, 'addProfessor', FILTER_SANITIZE_SPECIAL_CHARS);
            }   
            $vista = new MVC_view_adminProfessorsActivities();
            $selected = 2;
            break;
        case TypesEnum::ADMIN_COMPETENCES:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_competences;      
            
            if(!isset($_GET['excel'])){
                if(isset($_POST['formActivated'])){
                    $params['input'] = true;
                    
                    $params['dataInput'] = filter_input_array(INPUT_POST);
                    
                }else{
                    $params['input'] = false;
                }

                if(isset($_GET['buscador'])){
                    $params['buscador'] = true;
                    $params['buscador_codigo'] =  filter_input(INPUT_POST, 'USER_LOOK_CODE', FILTER_SANITIZE_SPECIAL_CHARS);
                    
                    $params['filter_area'] =  filter_input(INPUT_POST, 'USER_SEARCH_AREA', FILTER_SANITIZE_SPECIAL_CHARS);
                    $params['filter_materia'] =  filter_input(INPUT_POST, 'USER_SEARCH_MATERY', FILTER_SANITIZE_SPECIAL_CHARS);
                    
                }else{
                    $params['buscador'] = false;
                }
                $vista = new MVC_view_adminCompetences();
            }else{
                $params['input'] = false;
                $params['buscador'] = true;
                $params['buscador_codigo'] =  filter_input(INPUT_GET, 'excel', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['filter_area'] =  filter_input(INPUT_GET, 'excel_area', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['filter_materia'] =  filter_input(INPUT_GET, 'excel_materia', FILTER_SANITIZE_SPECIAL_CHARS);                
                
                if(isset($_GET['indicators'])){
                    $params['indicators'] = true;                   
                }else{
                    $params['indicators'] = false;
                }
                
                $vista = new MVC_view_adminCompetencesExcel();
            }
            
            
            $selected = 3;
            break;
        case TypesEnum::ADMIN_COMPETENCES_INDICATOR:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_competences;  
            
            $params[] = filter_input(INPUT_GET, 'competencesIndicator', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if(isset($_POST['search_indicator'])){
                $params['SEARCH'] = filter_input(INPUT_POST, 'search_indicator_input', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            
            if(isset($_GET['removeIndicator'])){
                $params['REMOVE'] = filter_input(INPUT_GET, 'removeIndicator', FILTER_SANITIZE_SPECIAL_CHARS);
            }            

            
            if(isset($_POST['addIndicator'])){
                $params['ADD'] = true;
                $params['ADD_INDICATOR_NAME'] = filter_input(INPUT_POST, 'ADD_INDICATOR_NAME', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['ADD_INDICATOR_DESCRIPTION'] = filter_input(INPUT_POST, 'ADD_INDICATOR_DESCRIPTION', FILTER_SANITIZE_SPECIAL_CHARS);   
                $params['ADD_INDICATOR_CODE'] = filter_input(INPUT_POST, 'ADD_INDICATOR_CODE', FILTER_SANITIZE_SPECIAL_CHARS); 
            }   
            $vista = new MVC_view_admin_competencesIndicator();
            $selected = 3;
            
            break;
        case TypesEnum::ADMIN_SESSIONS_LIST:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $activities_sessions;
            $params[] = filter_input(INPUT_GET, 'sessionList', FILTER_SANITIZE_SPECIAL_CHARS);
            if(isset($_POST['sessionNew'])){
                $params[] = true;
                $params[] = filter_input(INPUT_POST, 'sessionDateStart', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionDateStartHour', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionDateStartMinute', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionDateDuration', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionPassword', FILTER_SANITIZE_SPECIAL_CHARS);
                $params[] = filter_input(INPUT_POST, 'sessionPlaceSelect', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if(isset($_GET['remove'])){
                $params['remove'] = filter_input(INPUT_GET, 'remove', FILTER_SANITIZE_SPECIAL_CHARS);
                
            }            
            $vista = new MVC_view_adminActivitiesSessions();
            
            $selected = 1;
            break;         
        case TypesEnum::USER_MY_PROFILE:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_profile;       
            $params['UPLOAD_PHOTO'] = false;
            $params['CHANGE_DATA'] = false;
            $params['CHANGE_DATA_NO_PASSWORD'] = false;
            
            //Si se ha subido una imagen
            if(isset($_POST['ADD_PROFILE_SUBMIT'])){
                $params['UPLOAD_PHOTO'] = true;                
                $params['FILE'] = $_FILES["ADD_PROFILE_PHOTO"];
                
            }
            
            if(isset($_POST['CHANGE_USER_PASSWORD_SUBMIT'])){
                
                $params['CHANGE_DATA'] = true;
                $params['PASSWORD_OLD'] =  filter_input(INPUT_POST, 'CHANGE_USER_OLD_PASSWORD', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['PASSWORD_NEW'] =  filter_input(INPUT_POST, 'CHANGE_USER_NEW_PASSWORD', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['PASSWORD_RETYPE'] =  filter_input(INPUT_POST, 'CHANGE_USER_RETYPE_NEW_PASSWORD', FILTER_SANITIZE_SPECIAL_CHARS);
                 
            }
            if(isset($_POST['CHANGE_USER_DATA_NO_PASSWORD_SUBMIT'])){
                
                $params['CHANGE_DATA_NO_PASSWORD'] = true;
                $params['NAME'] =  filter_input(INPUT_POST, 'CHANGE_USER_NAME_OLD', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['LASTNAME1'] =  filter_input(INPUT_POST, 'CHANGE_USER_LASTNAME1_OLD', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['LASTNAME2'] =  filter_input(INPUT_POST, 'CHANGE_USER_LASTNAME2_OLD', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['MAIL'] =  filter_input(INPUT_POST, 'CHANGE_USER_MAIL_OLD', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            
            
            
            $vista = new MVC_view_user_my_profile();
            $selected = 6;
            break;
        case TypesEnum::USER_HELP:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_help;       

            $params['dataInput'] = filter_input_array(INPUT_GET);
            $vista = new MVC_view_user_help();
            $params[0] = $rol;
            $selected=11;
            break;
        case TypesEnum::USER_ABOUT:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_about; 
            
            $vista = new MVC_view_user_about();
            
            break;
        case TypesEnum::STUDENT_DOWNLOAD_REPORT:   
            $vista = new MVC_view_student_report();
            $selected = 1;
            break;
        case TypesEnum::PROFESSOR_EVALUATION_REPORT:
            $vista = new MVC_view_evaluationReport();
            //studentsEval contiene información de la actividad de la que se quiere mostrar la tabla de evaluación
            $params[] = filter_input(INPUT_GET, 'evaluationReport', FILTER_SANITIZE_SPECIAL_CHARS);
            $selected = 1;
            break;
        case TypesEnum::PROFESSOR_CHANGE_YEAR:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $time_machine_strings;  
            
            $vista = new MVC_view_professor_TimeMachine();
            
            if(isset($_POST['timeTravel'])){
                $params['newYear'] = filter_input(INPUT_POST, 'newYear', FILTER_SANITIZE_SPECIAL_CHARS);
            }

            $selected = 1;
            break;        
        case TypesEnum::ADMIN_USERS:
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;
            $strings_index[] = $strings_users;  
            
            if(!isset($_GET['excel'])){
                if(isset($_POST['formActivated'])){
                    $params['input'] = true;
                    $params['dataInput'] = filter_input_array(INPUT_POST);
                    $params['dataInput']['USERS_MODIFY'] = true;
                }else{
                    $params['input'] = false;
                }

                $params['file'] = false;

                if(isset($_POST['uploadedFile'])){

                    $params['file'] = $_FILES["file"];
                    $params['IS_UPLOADED_FILE'] = true;
                    $params['IS_PROFESSOR'] = false;                 

                }         


                if(isset($_GET['buscador'])){
                    $params['IS_BUSCADOR'] = true;
                    if(isset($_POST['FILTRAR'])){
                       $params['IS_FILTRO'] = true; 
                    }else{
                        $params['IS_FILTRO'] = false; 
                    }
                    
                    $params['mail'] = filter_input(INPUT_POST, 'USER_LOOK_MAIL', FILTER_SANITIZE_SPECIAL_CHARS);
                    $params['realid'] = filter_input(INPUT_POST, 'USER_LOOK_REALID', FILTER_SANITIZE_SPECIAL_CHARS);
                    $params['filter_area'] = filter_input(INPUT_POST, 'USER_LOOK_AREA', FILTER_SANITIZE_SPECIAL_CHARS);
                    $params['filter_centre'] = filter_input(INPUT_POST, 'USER_INSERT_CENTRE', FILTER_SANITIZE_SPECIAL_CHARS);

                }else{
                    $params['IS_BUSCADOR'] = false; 
                }
                $vista = new MVC_view_admin_users();
            }else{
                $params['input'] = false;
                $params['IS_BUSCADOR'] = true; 
                $params['IS_FILTRO'] = true;
                $entrada = filter_input(INPUT_GET, 'excel', FILTER_SANITIZE_SPECIAL_CHARS);
                $area = filter_input(INPUT_GET, 'area', FILTER_SANITIZE_SPECIAL_CHARS);
                $centre = filter_input(INPUT_GET, 'centre', FILTER_SANITIZE_SPECIAL_CHARS);
                
                if(strpos($entrada,'@') !== false){
                    $params['mail'] = $entrada;
                }else{
                    $params['realid'] = $entrada;
                } 
                
                if(strcmp($entrada, 'TODO') == 0){                    
                    $params['filter_area'] = -1;
                    $params['filter_centre'] = -1;     
                }else if(strcmp($entrada, 'AREA') == 0){
                    $params['filter_centre'] = -1; 
                    $params['filter_area'] = $area;
                }else if(strcmp($entrada, 'CENTRE') == 0){
                    $params['filter_centre'] = $centre; 
                    $params['filter_area'] = -1;
                }else{
                    $params['filter_area'] = $area;   
                    $params['filter_centre'] = $centre;                     
                }
 
                $vista = new MVC_view_admin_usersExcel();
            }
            
            $selected = 4;
            break;
        case TypesEnum::ADMIN_SYSTEM:
            
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;            
            $strings_index[] = $stringAdminSystem;  
            
            $vista = new MVC_view_admin_system();
            $params['is_new_year'] = false;
            $params['is_selected_year'] = false;
            if(isset($_GET['yearCreation'])){
                $params['yearInitialDate'] = filter_input(INPUT_POST, 'createYear1', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['yearFinalDate'] = filter_input(INPUT_POST, 'createYear2', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['is_new_year'] = true;
            }
            if(isset($_GET['yearSelection'])){
                $params['yearSelection'] = filter_input(INPUT_POST, 'yearSelected', FILTER_SANITIZE_SPECIAL_CHARS);
                $params['is_selected_year'] = true;
            }
            $selected = 5;
            break;
        case TypesEnum::ADMIN_UPLOAD_FROM_FILE:            
            $temp = $strings_index;
            unset($strings_index);
            $strings_index[] = $temp;            
            $strings_index[] = $stringAdminFile;  
            
      
            if(isset($_POST) && count($_POST) > 0){
               $params['type'] =  filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
               $params['file'] =  $_FILES["file"];
            }
            
            $vista = new MVC_view_admin_file();
            $selected = 5;
            break;            
         default:
            break;
    }

    /**
     * Creación del objeto modelo al que se le pasa la variable $modelo, que le especificará
     * los datos con los que va a trabajar dentro del sistema.
     */
    if(isset($_SESSION['sessionObject'])){
        $generador = new ModelGenerator($modelo,$_SESSION['sessionObject']->getIdUsuario());
    }else{
        $generador = new ModelGenerator($modelo);
    }

    /**
     * Al modelo se le envían parámetros de configuración y este devuelve un array $data
     * Nota: consultar la documentación de generate para más información sobre el 
     * array $data
     */
    
    $data = $generador->generate($params);

    /**
     * Si se solicitó un cambio de rol, no hay que cargar vista
     */
    if($modelo == TypesEnum::USER_CHANGEROL){
        if($data['changeROL'] ){
            $_SESSION['sessionObject']->setRol($params[0]+1);
            header( 'Location: index.php?rolChanged=1' );
        }
        else{
            header( 'Location: index.php?rolChanged=0' );
        }
    }else{
        /**
         * La vista requiere:
         *  -> $string_index -> Índice de cadenas i18n para mostrar a clientes "humanos"
         *  -> $systemDictionary -> Cadenas definidas sobre elementos del sistema
         *  -> $data -> Array de datos devuelto por el modelo
         *      Nota: consultar la documentación de generate para más información sobre el 
         *      array $data
         */ 

        $vista->viewHTML($strings_index, $systemDictionary,$rol, $data,$selected );
    }
} catch (Exception $Exception) {
    echo $Exception->getMessage();
}
?>
