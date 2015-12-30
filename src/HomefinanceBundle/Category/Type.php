<?php

namespace HomefinanceBundle\Category;

class Type {

    const INCOME = "category.income";
    const EXPENSE = "category.expense";
    const SUSPENSE = "category.suspense";
    const ROOT = "category.root";

    public static function getAllTypes() {
        return array (
            static::INCOME,
            static::EXPENSE,
            static::SUSPENSE,
        );
    }

    public static function isTypeValid($type) {
        $allTypes = static::getAllTypes();
        if (in_array($type, $allTypes)) {
            return true;
        }
        if ($type == static::ROOT) {
            return true;
        }
        return false;
    }

}