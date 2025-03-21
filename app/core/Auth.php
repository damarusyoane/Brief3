<?php
class Auth{
    /* verifier si l'utilisateur est connecter */
    /**
     * @return bool
     */
    public static function isUserLoggedIn(){
        return isset($_SESSION['user_id']);
    }
    /* verifier si l'utilisateur est admin */
    /**
     * @return bool
     */
    public static function isAdmin(){
        return isset($_SESSION['user_id'])&& $_SESSION['user_id']['role']=='admin';
    }

    /* verifie si l'admin est */
    /**
     * @return bool
     */
    public static function isAdminLoggedIn(){
        return isset($_SESSION['admin_id']);
    }


    /* recupere l'id de l'utilisateur connecter */
    public static function getUserId(){
        return $_SESSION['user_id']?? null;
    }
    /* recupere l'id de l'admin connecter */
    public static function getAdminId(){
        return $_SESSION['admin_id']?? null;
    }

    /* on connecte l'utilisateur */
     /**
     * @param int $userId id de l'utilisateur 
     */
    public static function loginUser($userId){
        $_SESSION['user_id']=$userId;
    }
    /* on connecte l'admin */
     /**
     * @param int $adminId id de l'utilisateur 
     */
    public static function loginAdmin($adminId){
        $_SESSION['admin_id']=$adminId;
    }

    /* on deconnecte l'utilisateur connecter */
    public static function logoutUser(){
        session_destroy();
    }

    /* on deconnecte l'admin connecter */
    public static function logoutAdmin(){
        session_destroy();
    }


}