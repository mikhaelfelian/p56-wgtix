<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-09-01
 * Github: github.com/mikhaelfelian
 * 
 * This file represents the Akses Helper for managing access to the admin panel.
 */

if (!function_exists('isAdmin')) {
    /**
     * Check if current user is admin (level != 5)
     * 
     * @return bool
     */
    function isAdmin()
    {
        $ionAuth = service('ionAuth');
        if (!$ionAuth) {
            return false;
        }
        $user = $ionAuth->user();
        if (!$user || !is_object($user)) {
            return false;
        }
        $userRow = $user->row();
        if (!$userRow) {
            return false;
        }
        $userId = $userRow->id;
        $groups = $ionAuth->getUsersGroups($userId);
        if (!$groups || !is_object($groups)) {
            return false;
        }
        $group = $groups->getRow();
        if (!$group) {
            return false;
        }
        
        // Assuming 'level' is a property of the group object
        if (isset($group->level) && $group->level != 5) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('isUser')) {
    /**
     * Check if current user is user (level == 5)
     * 
     * @return bool
     */
    function isUser()
    {
        $ionAuth = service('ionAuth');
        // if (!$ionAuth) {
        //     return false;
        // }
        // Return true if user is logged in
        return $ionAuth;
    }
}


