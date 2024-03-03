<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            ['name' => 'নগদ', 'details' => 'নগদ টাকা', 'created_at' => '2024-02-24 07:08:01', 'updated_at' => '2024-02-24 07:08:33'],
            ['name' => 'Al Arafah Islami Bank Ltd. 17135', 'details' => 'M/S. S.A Drayer Auto Rice Mill  No - 0091020017135  R.No - 015154275 Ktg Br. Ctg', 'created_at' => '2024-02-24 07:24:38', 'updated_at' => '2024-02-24 11:37:38'],
            ['name' => 'Al Arafah Islami Bank Ltd. 17563', 'details' => 'M/S. Rajdhani Rice Agency  No- 0091020017563    R.No - 015154275  Ktg Br. Ctg', 'created_at' => '2024-02-24 11:16:52', 'updated_at' => '2024-02-24 11:38:35'],
            ['name' => 'Al Arafah Islami Bank Ltd. 16639', 'details' => 'M/S. Sagar And Akash Brothers  No - 0091020016639  R.No - 015154275 Ktg Br. Ctg', 'created_at' => '2024-02-24 11:28:08', 'updated_at' => '2024-02-24 11:39:21'],
            ['name' => 'Standard Bank Ltd. 561', 'details' => 'M/S. Rajdhani Rice Agency  No - 07733000561 R.No - 210150087  Chaktai Br. Ctg', 'created_at' => '2024-02-24 11:32:20', 'updated_at' => '2024-02-24 11:54:48'],
            ['name' => 'Islami Bank Bd Ltd CD -1657', 'details' => 'M/S. Sagar And Akash Brothers  No - 205014604100165717  R.No - 125151755 Chaktai Br. Ctg', 'created_at' => '2024-02-24 11:36:44', 'updated_at' => '2024-02-24 11:55:03'],
            ['name' => 'Pubali Bank Ltd . 37560', 'details' => 'Jantu Kumar Bhumik. No - 0791901037560  R.No - 175151750 Chaktai Br. Ctg', 'created_at' => '2024-02-24 11:42:27', 'updated_at' => '2024-02-24 11:42:27'],
            ['name' => 'BRAC  BANK 30001', 'details' => 'M/S. Sagar And Akash Brothers  No - 2059119230001  R.No - 060155674 Jubilee Road Br. Ctg', 'created_at' => '2024-02-24 11:49:30', 'updated_at' => '2024-02-24 11:55:15'],
            ['name' => 'BRAC  BANK 199001', 'details' => 'M/S. Sagar And Akash Brothers  No - 1114204847199001  R.No - 060155674 Jubilee Road Br. Ctg', 'created_at' => '2024-02-24 11:54:34', 'updated_at' => '2024-02-24 11:54:34'],
            ['name' => 'Janata Bank Ltd. 5518', 'details' => 'M/S. Sagar And Akash Brothers  No - 0100120285518  R.No - 135151758 Chaktai Br. Ctg', 'created_at' => '2024-02-24 11:59:46', 'updated_at' => '2024-02-24 11:59:46'],
            ['name' => 'Uttara Bank Ltd. 212005', 'details' => 'M/S. Sagor And Akash Brothers  No - 012512200212005  R.No - 250151754  Chaktai Br. Ctg', 'created_at' => '2024-02-24 12:20:47', 'updated_at' => '2024-02-24 12:20:47'],
            ['name' => 'Standard Bank Ltd. 436', 'details' => 'M/S. Sagar And Akash Brothers  No - 07733000436  R.No - 135151758 Chaktai Br. Ctg', 'created_at' => '2024-02-24 12:28:19', 'updated_at' => '2024-02-24 12:28:19'],
        ];

        DB::table('accounts')->insert($accounts);
    }
}
