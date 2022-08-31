<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException as Exception;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Console\DumpCommand;

trait GetMovieTrait
{
    public function getMoviesByIds($ids)
    {
        $key = $this->tmdbkey;
        $url = "https://api.themoviedb.org/3/movie/";
        $client = new Client(['base_uri' => $url]);
        $movies = [];

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
            'fulfilled' => function (Response $response, $index) use (&$movies) {
                $data = json_decode((string)$response->getBody(), true);
                $movies[] = $data;

            },
            'rejected' => function (Exception $reason, $index) {
                // this is delivered each failed request
                echo "Requested search term: ", $index, "\n";
                echo $reason->getMessage(), "\n\n";
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        
        return $movies;
    }

    public function getMovieById($id)
    {
        $key = $this->tmdbkey;
        $url = "https://api.themoviedb.org/3/movie/";
        $client = new Client(['base_uri' => $url]);
        
        $movie = $client->get("{$id}?api_key={$key}");
     
        return $movie;
    }
}