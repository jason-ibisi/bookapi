<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use GuzzleHttp\Client;

class ExternalBookController extends Controller
{
    /**
     * Get book data from IceAndFire API
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->query()['name'];

        $guzzle_client = new Client([
            'base_uri' => 'https://anapioficeandfire.com/api/',
            'verify' => false
        ]);

        $response = $guzzle_client->get('books', [
            'query' => [
                'name' => $query
            ]
        ]);

        // Format the data from the api call
        $data = $this->sanitize($response->getBody());

        return response()->json($data);
    }

    /**
     * Format the response object
     *
     * @param Object $obj
     * @return array
     */
    private function sanitize(Object $obj)
    {
        $result = [
            'status_code' => 200,
            'status' => 'success',
            'data' => []
        ];

        // convert object to array
        $array = json_decode($obj, true);

        foreach ($array as &$item) {
            if (count($item) > 2) {
                $date = new Carbon($item['released']);
                // assign date part of released property to release_date property
                $item['release_date'] = $date->toDateString();
                // remove unwanted fields
                $item = Arr::except($item, [
                    'url', 'mediaType', 'characters', 'released', 'povCharacters'
                ]);
            }
            array_push($result['data'], $item);
        }

        return $result;
    }
}
