<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseCategories = [
            ['name' => 'দোকান ভাড়া'],
            ['name' => 'বিদ্যুৎ বিল'],
            ['name' => 'চা নাস্তা ও অন্যান্য'],
            ['name' => 'মোবাইল রির্চাজ'],
            ['name' => 'ইলেকট্রিক বিল'],
            ['name' => 'কালেকশন বাবদ যাতায়ত'],
            ['name' => 'পূজার চাঁদা'],
            ['name' => 'মাসিক গার্ডের চাঁদা'],
            ['name' => 'মাসিক সমিতির চাঁদা'],
            ['name' => 'ইন্টারনেট বিল'],
            ['name' => 'গুদাম ভাড়া (রাজধানী রাইচ)'],
            ['name' => 'মেঝ ভাড়া ও বিদ্যুৎ বিল'],
            ['name' => 'লোকনাথ মন্দিরের মাসিক চাঁদা'],
            ['name' => 'বকশিষ বাবদ'],
            ['name' => 'ষ্টেশনারী সামগ্রী'],
            ['name' => 'গড়মিল বাবদ'],
        ];

        DB::table('expense_categories')->insert($expenseCategories);
    }
}
