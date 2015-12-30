<?php

namespace HomefinanceBundle\Share;

class Permission {

  const OWNER="Owner";
  const FULL_ACCESS="Full access";
  const EDIT="Edit";
  const VIEW="View";
  const NO_ACCESS="No access";

  public static function getAllPermissions() {
    return array(
      static::NO_ACCESS,
      static::VIEW,
      static::EDIT,
      static::FULL_ACCESS,
      static::OWNER,
    );
  }

  public static function getPermissionTree() {
    return array (
      static::OWNER => array(
        static::FULL_ACCESS,
        static::EDIT,
        static::VIEW,
      ),
      static::FULL_ACCESS => array (
        static::EDIT,
        static::VIEW,
      ),
      static::EDIT => array(
        static::VIEW,
      ),
      static::VIEW => array(
      ),
      static::NO_ACCESS => array(

      ),
    );
  }

  public static function isPermissionValid($permission) {
    $allPermissions = static::getAllPermissions();
    if (in_array($permission, $allPermissions)) {
      return true;
    }
    return false;
  }

  public static function hasPermission($neededPermission, $currentPermission) {
    $tree = static::getPermissionTree();
    $currentPermissions = $tree[$currentPermission];
    if (in_array($neededPermission, $currentPermissions)) {
      return true;
    } elseif ($neededPermission == $currentPermission) {
      return true;
    }
    return false;
  }

}