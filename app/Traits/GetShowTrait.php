<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Console\DumpCommand;

trait GetShowTrait
{
    public function getShowsByIds($ids)
    {
        $key = $this->tmdbkey;
        $url = "https://api.themoviedb.org/3/tv/";
        $client = new Client(['base_uri' => $url]);
        $shows = [];

        $requestGenerator = function ($ids) use ($client, $key) {
            foreach ($ids as $id) {
                yield $id => function () use ($client, $id, $key) {
                    return $client->getAsync("{$id}?api_key={$key}");
                };
            }
        };

        $pool = new Pool($client, $requestGenerator($ids), [
            // this is a trial-error number, you can change it to whatever you want, but check the actual request times
            'concurrency' => 3,
            'fulfilled' => function (Response $response, $index) use (&$shows) {
                $data = json_decode((string)$response->getBody(), true);
                $shows[] = $data;
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
                report($reason);
                return back()->with('message', 'Something went wrong when looking for your shows. Try again later or contact me.');
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        
        return $shows;
    }

    public function getShowById($id)
    {
        $key = $this->tmdbkey;
        $url = "https://api.themoviedb.org/3/tv/";
        $client = new Client(['base_uri' => $url]);
        
        $show = $client->get("{$id}?api_key={$key}");
     
        return $show;
    }
}