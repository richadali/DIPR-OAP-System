<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsType;

class NewsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewsType::updateOrCreate(['news_type'=>'Local Newspaper']);
        NewsType::updateOrCreate(['news_type'=>'National Newspaper']);
        NewsType::updateOrCreate(['news_type'=>'Regional Newspaper']);
        NewsType::updateOrCreate(['news_type'=>'National Channel']);
        NewsType::updateOrCreate(['news_type'=>'Magazine']);
        NewsType::updateOrCreate(['news_type'=>'Website']);
        NewsType::updateOrCreate(['news_type'=>'Cable TV']);
    }
}
