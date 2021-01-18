<?php
/**
 * User: Lessmore92
 * Date: 1/14/2021
 * Time: 12:54 AM
 */

namespace Lessmore92\Ripple;


use Exception;

class Utils
{
    public static function xrpToDrops($xrp): string
    {
        if (!preg_match('/^-?[0-9]*\.?[0-9]*$/', $xrp))
        {
            throw new Exception("xrpToDrops: invalid value '${xrp}', should be a number matching (^-?[0-9]*\.?[0-9]*$).");
        }
        else if ($xrp === '.')
        {
            throw new Exception("xrpToDrops: invalid value '${xrp}', should be a string-encoded number.");
        }

        if (!preg_match('/^-?[0-9.]+$/', $xrp))
        {
            throw new Exception("xrpToDrops: failed sanity check - value '${xrp}', does not match (^-?[0-9.]+$)");
        }

        $components = explode('.', $xrp);
        if (count($components) > 2)
        {
            throw new Exception("xrpToDrops: failed sanity check - value '${xrp}' has too many decimal points.");
        }

        $fraction = isset($components[1]) ? $components[1] : '0';
        if (strlen($fraction) > 6)
        {
            throw new Exception("xrpToDrops: value '${xrp}' has too many decimal places.");
        }

        return number_format($xrp * 1000000.0, 0, '', '');
    }

    public static function dropsToXrp($_drops)
    {
        if (!preg_match('/^-?[0-9]*\.?[0-9]*$/', $_drops))
        {
            throw new Exception("dropsToXrp: invalid value '${_drops}', should be a number matching (^-?[0-9]*\.?[0-9]*$).");
        }
        else if ($_drops === '.')
        {
            throw new Exception("dropsToXrp: invalid value '${_drops}', should be a string-encoded number.");
        }

        if (strpos($_drops, '.') !== false)
        {
            throw new Exception("dropsToXrp: value '${_drops}' has too many decimal places.");
        }

        if (!preg_match('/^-?[0-9]+$/', $_drops))
        {
            throw new Exception("dropsToXrp: failed sanity check - value '${_drops}', does not match (^-?[0-9]+$)");
        }

        return $_drops / 1000000.0;
    }

    public static function arrayGet(array $array, string $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}
