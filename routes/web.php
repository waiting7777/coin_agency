<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Ixudra\Curl\Facades\Curl;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function() {
    $response = Curl::to('http://www.google.com.tw')->get();
    $user = ['name' => 'John', 'age' => 65];
    Log::channel('slack')->info('User: ' . print_r($user, true));
    return $response;
});

Route::post('/webhook', function(Request $request){
    $data = $request->all();
    Log::info(print_r($data, true));
    if(strpos($data['event']['text'], '<@U9FAL208Y>') !== false && strpos($data['event']['text'], 'BTC') !== false){
        $coin_data = json_decode(Curl::to('https://api.coinmarketcap.com/v1/ticker/?limit=1')->get(), true);
        $coin = $coin_data[0];
        $res = array('attachments' => 
            array(
                array(
                    'fallback' => 'Data not avaiable',
                    'color' => '#70A800',
                    'pretext' => 'Latest price from CoinMarketCap:',
                    'author_name' => $coin['name'] . ' (' . $coin['symbol'] . ')',
                    'author_link' => 'https://coinmarketcap.com/currencies/bitcoin/',
                    'author_icon' => 'https://files.coinmarketcap.com/static/img/coins/32x32/1.png',
                    'fields' => array(
                        array(
                            'title' => 'Rank',
                            'value' => $coin['rank'],
                            'short'=> true
                        ),
                        array(
                            'title' => 'Price',
                            'value' => '$' . number_format($coin['price_usd'], 2),
                            'short'=> true
                        ),
                        array(
                            'title' => 'Available Supply',
                            'value' => number_format($coin['available_supply']) . ' ' . $coin['name'],
                            'short'=> true
                        ),
                        array(
                            'title' => 'Total Supply',
                            'value' => number_format($coin['total_supply']) . ' ' . $coin['name'],
                            'short'=> true
                        ),
                        array(
                            'title' => 'Max Supply',
                            'value' => number_format($coin['max_supply']) . ' ' . $coin['name'],
                            'short'=> true
                        ),
                        array(
                            'title' => 'Volume (24h)',
                            'value' => '$' . number_format($coin['24h_volume_usd']),
                            'short'=> true
                        ),
                        array(
                            'title' => 'Market Cap',
                            'value' => '$' . number_format($coin['market_cap_usd']),
                            'short'=> true
                        ),
                        array(
                            'title' => 'Percent Change (1h)',
                            'value' => $coin['percent_change_1h'] . '%',
                            'short'=> true
                        ),
                        array(
                            'title' => 'Percent Change (24h)',
                            'value' => $coin['percent_change_24h'] . '%',
                            'short'=> true
                        ),
                        array(
                            'title' => 'Percent Change (7d)',
                            'value' => $coin['percent_change_7d'] . '%',
                            'short'=> true
                        )
                    ),
                    'ts' => $coin['last_updated']
                )
            )
        );
        $response = Curl::to('https://hooks.slack.com/services/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')->withData( $res )->asJson()->post();
        // Log::channel('slack')->info($data[0]->symbol);
    }
    return response('hello', 200)->header('Content-Type', 'text/plain');
});
