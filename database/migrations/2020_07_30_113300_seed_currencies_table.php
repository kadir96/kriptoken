<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('app.env') == 'testing') {
            return;
        }

        DB::table('currencies')->insert([
            [
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'btc_value' => '1',
            ],
            [
                'name' => 'Ethereum',
                'symbol' => 'ETH',
                'btc_value' => '0.04791411',
            ],
            [
                'name' => 'Litecoin',
                'symbol' => 'LTC',
                'btc_value' => '0.00592855',
            ],
            [
                'name' => 'Dash',
                'symbol' => 'DASH',
                'btc_value' => '0.06756612',
            ],
            [
                'name' => 'Ripple',
                'symbol' => 'XRP',
                'btc_value' => '0.00002001',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('app.env') == 'testing') {
            return;
        }

        DB::table('currencies')->truncate();
    }
}
