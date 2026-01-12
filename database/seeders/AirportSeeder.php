<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $airports = [
            [
                'airport_code' => 'JED',
                'airport_name' => 'مطار الملك عبدالعزيز الدولي',
                'city' => 'جدة',
                'country' => 'السعودية',
                'lat' => 21.679564,
                'lng' => 39.156536,
            ],
            [
                'airport_code' => 'RUH',
                'airport_name' => 'مطار الملك خالد الدولي',
                'city' => 'الرياض',
                'country' => 'السعودية',
                'lat' => 24.958056,
                'lng' => 46.698889,
            ],
            [
                'airport_code' => 'DMM',
                'airport_name' => 'مطار الملك فهد الدولي',
                'city' => 'الدمام',
                'country' => 'السعودية',
                'lat' => 26.470800,
                'lng' => 49.797901,
            ],
            [
                'airport_code' => 'MED',
                'airport_name' => 'مطار الأمير محمد بن عبدالعزيز الدولي',
                'city' => 'المدينة المنورة',
                'country' => 'السعودية',
                'lat' => 24.553333,
                'lng' => 39.705000,
            ],

            [
                'airport_code' => 'CAI',
                'airport_name' => 'مطار القاهرة الدولي',
                'city' => 'القاهرة',
                'country' => 'مصر',
                'lat' => 30.121944,
                'lng' => 31.405556,
            ],

            [
                'airport_code' => 'DXB',
                'airport_name' => 'مطار دبي الدولي',
                'city' => 'دبي',
                'country' => 'الإمارات',
                'lat' => 25.252778,
                'lng' => 55.364444,
            ],
            [
                'airport_code' => 'AUH',
                'airport_name' => 'مطار أبوظبي الدولي',
                'city' => 'أبوظبي',
                'country' => 'الإمارات',
                'lat' => 24.432972,
                'lng' => 54.651138,
            ],

            [
                'airport_code' => 'DOH',
                'airport_name' => 'مطار حمد الدولي',
                'city' => 'الدوحة',
                'country' => 'قطر',
                'lat' => 25.273056,
                'lng' => 51.608056,
            ],

            [
                'airport_code' => 'IST',
                'airport_name' => 'مطار إسطنبول الدولي',
                'city' => 'إسطنبول',
                'country' => 'تركيا',
                'lat' => 41.262222,
                'lng' => 28.727778,
            ],

            [
                'airport_code' => 'LHR',
                'airport_name' => 'مطار لندن هيثرو',
                'city' => 'لندن',
                'country' => 'المملكة المتحدة',
                'lat' => 51.470022,
                'lng' => -0.454295,
            ],

            [
                'airport_code' => 'TUU',
                'airport_name' => 'مطار الأمير سلطان بن عبدالعزيز',
                'city' => 'تبوك',
                'country' => 'السعودية',
                'lat' => 28.365417,
                'lng' => 36.618889,
            ],
            [
                'airport_code' => 'ABT',
                'airport_name' => 'مطار الملك عبدالله بن عبدالعزيز',
                'city' => 'الطائف',
                'country' => 'السعودية',
                'lat' => 21.348333,
                'lng' => 40.335833,
            ],
            [
                'airport_code' => 'AHB',
                'airport_name' => 'مطار أبها الدولي',
                'city' => 'أبها',
                'country' => 'السعودية',
                'lat' => 18.240367,
                'lng' => 42.656625,
            ],
            [
                'airport_code' => 'ELQ',
                'airport_name' => 'مطار الأمير نايف بن عبدالعزيز الدولي',
                'city' => 'القصيم',
                'country' => 'السعودية',
                'lat' => 26.302822,
                'lng' => 43.773911,
            ],
        ];


        foreach ($airports as $airport) {
            Airport::updateOrCreate(
                ['airport_code' => $airport['airport_code']],
                $airport
            );
        }
    }
}
