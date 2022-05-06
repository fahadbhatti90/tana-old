<?php

namespace App\Model\ExecutiveDashboard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExecutiveDashboard extends Model
{

    /**
     * @param $type
     * @return array
     */
    public static function shippedCogsYtd($type)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_ytd(?)', array($type));
    }

    /**
     * @param $type
     * @return array
     */
    public static function netReceivedYtd($type)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_ytd(?)', array($type));
    }

    /**
     * @param $type
     * @param $startDate
     * @return array
     */
    public static function shippedCogsMtd($type, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_mtd(?,?)', array($type, $startDate));
    }

    /**
     * @param $type
     * @param $startDate
     * @return array
     */
    public static function netReceivedMtd($type, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_mtd(?,?)', array($type, $startDate));
    }

    /**
     * @param $type
     * @param $user_id
     * @param $role_id
     * @param $startDate
     * @return array
     */
    public static function shippedCogsTable($type, $user_id, $role_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_table(?,?,?,?)', array($type, $user_id, $role_id, $startDate));
    }

    /**
     * @param $type
     * @param $user_id
     * @param $role_id
     * @param $startDate
     * @return array
     */
    public static function netReceivedTable($type, $user_id, $role_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_table(?,?,?,?)', array($type, $user_id, $role_id, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @return array
     */
    public static function vendorDetailSC($type, $vendor)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_ytd_vendor(?,?)', array($type, $vendor));
    }

    /**
     * @param $type
     * @param $vendor
     * @return array
     */
    public static function vendorDetailNR($type, $vendor)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_ytd_vendor(?,?)', array($type, $vendor));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function vendorDetailSCMTD($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_mtd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function vendorDetailNRMTD($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_mtd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function shippedCogsTrailing($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_trailing(?,?,?)', array($type, $vendor, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function netReceivedTrailing($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_trailing(?,?,?)', array($type, $vendor, $startDate));
    }
}
