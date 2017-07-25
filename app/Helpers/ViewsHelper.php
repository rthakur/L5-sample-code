<?php

namespace App\Helpers;

class ViewsHelper
{
    
    public static function selectPackageOne($selectedPackage = null, $package, $packageRow)
    {
        if (isset($selectedPackage) && (
            ($package->id == '2' && $selectedPackage->id >= 2) || 
            (($selectedPackage->id == '' || $selectedPackage->id == 1) && $packageRow == 0)
            )
        ) {
            return true; 
        }
        
        if (!isset($selectedPackage) && $package->id == 1) {
            return true;
        }
        
        return false;
    }
    
}