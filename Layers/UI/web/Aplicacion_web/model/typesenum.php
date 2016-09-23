<?php

abstract class TypesEnum{
    const NO_LOGIN = 0;
    const LOGIN_ONLY = 1;    
    const COMPETENCE_INFO = 2;
    const INDICATOR_INFO = 3;
    const MESSAGES = 4;
    const MESSAGE_VIEW = 5;
    const MESSAGE_SEND = 6;
    const LOGOUT = 7;
    const REGISTER = 8;
    
    const PROFESSOR_VIEW_ONLY = 9; //Vista inicial del profesor
    const PROFESSOR_ACTIVITY_VIEW = 10; //vista de evaluación de una actividad
    const PROFESSOR_COMPETENCE_EVAL = 11; //Evaluación de una competencia
    const PROFESSOR_SESSIONS_LIST = 12; //Lista de sesiones de profesores
    const PROFESSOR_STUDENTS_LIST = 13; //Visualización del listado de estudiantes
    const PROFESSOR_STUDENTS_EVAL = 14; //Tabla de evaluacion de estudiantes
    const PROFESSOR_COMPETENCE_VIEW = 15; //Ver competencias
    const PROFESSOR_SESSIONS_ASSISTANCE = 16; //Comprobar asistencia de estudiantes
    const PROFESSOR_EVALUATION_REPORT = 17; //Descargar informe de evaluacion de un estudiante
    const PROFESOR_INSERT_STUDENTS = 18; //Insertar estudiantes de forma masiva
    const PROFESSOR_CHANGE_YEAR = 19; //Modifica el curso actual
    
    const ADMIN_VIEW_ONLY = 20; //Vista inicial del administrador
    
    const STUDENT_ACTIVITIES = 21; //Vista de las actividades un estudiante
    const STUDENT_ACTIVITY_INFO = 22; //Vista de una actividad
    const STUDENT_ACTIVITY_ASSISTANCE = 23; //Registro de asistencia de un estudiante
    const STUDENT_DOWNLOAD_REPORT = 24;  //Descargar informe del estudiante
    
    const USER_PROFILE = 25; //Perfil de usuario
    const USER_VIEW_COMPETENCES = 26; //Mostrar información sobre una competencia
    const USER_CONTACT = 27; //Formulario de contacto
    const USER_CHANGEROL  = 28; //Cambio de rol de usuario
    const USER_MY_PROFILE = 29; //Visión de los datos personales de unu usuario
    const USER_ERROR = 30; //Visión de mensaje de error
    const USER_HELP = 31; //Muestra la ayuda
    const USER_ABOUT = 32; //Muestra
    
    const ADMIN_ACTIVITIES = 33; //Administrar actividades
    const ADMIN_COMPETENCE_ACTIVITIES = 34; //Administrador de competencias  asociados a una actividad
    const ADMIN_PROFESSOR_ACTIVITIES = 35; //Administrador de profesores asociados a una actividad 
    const ADMIN_COMPETENCES = 36; //Administrar competencias
    const ADMIN_COMPETENCES_INDICATOR = 37; //Gestiona la lista de indicadores
    const ADMIN_USERS = 38; //Gestiona la lista de indicadores
    const ADMIN_SYSTEM = 39; //Gestiona la lista de indicadores
    const ADMIN_SESSIONS_LIST = 40; //Gestiona la lista de actividades
    const ADMIN_UPLOAD_FROM_FILE = 41; //Gestiona la vista de carga de datos desde un fichero por parte del Administrador.
}
