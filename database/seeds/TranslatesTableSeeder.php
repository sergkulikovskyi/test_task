<?php

use Illuminate\Database\Seeder;

class TranslatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filepath = base_path( 'database/csv/translations.csv' );

        $handle = file_exists( $filepath ) ? fopen( $filepath, 'r' ) : null;

        if ( ! $handle ) {
            $this->insertHardcodedData();
        } else {
            $headers = null;
            while ($data = fgetcsv($handle)) {
                if (!$headers) {
                    $headers = $data;
                } else {
                    $data = array_combine($headers, $data);

                    (new \App\Translation($data))->save();
                }
            }
        }
    }

    private function insertHardcodedData()
    {
        DB::table('translations')->insert([
            'ru' => 'привет',
            'en' => 'hi'
        ]);
    }
}
