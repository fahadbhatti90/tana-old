<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ModuleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);

        /*
         * create users
         */
        $this->call(UserTableSeeder::class);

        /*
         * create dummy KPI Information for sales in KpiInfo table
         */
        $this->call(SaleKpiInformation::class);

        /*
         * Add by default value of po_value and po_unit in mgmt_po_plan table
         */
        $this->call(PoPlanSeeder::class);

        /*
         * create Data Entry Operator Role, Module and users,
         * also assign permission of operator to Super Admin
         */
        $this->call(OperatorSeeder::class);
        /*
         * create dummy KPI Information for new sales in KpiInfo table
         */
        $this->call(NewSaleKpiInformation::class);
    }
}
