<?php

namespace Brainr\Http\Controllers\Api;

use GuzzleHttp;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Brainr\Profile;
use Brainr\ProfileLocation;
use Illuminate\Http\Request;
use Brainr\Http\Controllers\Controller;

class LocationController extends Controller
{
    /**
     * LocationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('getAddress');
    }

    /**
     * Displays all the location details owned by the profile
     *
     * @param  Profile  $profile
     *
     * @return mixed
     */
    public function index(Profile $profile)
    {
        $this->authorize('update', $profile);

        return $profile->locations;
    }

    /**
     * Displays all the location details owned by the profile
     *
     * @param  Profile  $profile
     *
     * @return mixed
     */
    public function getMarkerData(Profile $profile)
    {
        $this->authorize('update', $profile);
        
        $data = [];
        
        foreach ($profile->locations as $location) {
            array_push($data, [
                'id' => $location->id,
                'country' => $location->country,
                'address' => $location->address,
                'position' => [
                    'lat' => floatval($location->lat),
                    'lng' => floatval($location->lng),
                ]
            ]);
        }

        return response($data, 200);
    }

    public function getAddress(Request $request){
        $lat = floatval($request->lat);//"6.550408470382355";
        $lng = floatval($request->lng);//"3.339059199999994";
        $url = "https://maps.google.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=false&key=".env('GOOGLE_MAP_API_KEY', 'AIzaSyAA8RYM-kA3GdpJgoDI5eo5zjrNhJctNeY');
        
        $data = file_get_contents($url);
        $data = json_decode($data);
        $response  = $data->results;
        $client = new Client();
        $result = (string)$client->get($url)->getBody();
        $response = $response[0];
        $components = $response->address_components;
        $country = "";
        $state = ""; 
        $city = "";
        $street = isset($response->formatted_address) ? $response->formatted_address : "";

        foreach ($components as $key) {
            if($key->types[0] == 'administrative_area_level_2') $city = $key->long_name;

            if($key->types[0] == 'administrative_area_level_1') $state = $key->long_name;
        
            if($key->types[0] == 'country') $country = $key->long_name;
        }
        
        $data = [
            "address " => $street,
            "city" => $city,
            "state" => $state,
            "country " => $country,
        ];

        return response($data, 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brainr\Profile  $profile
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Profile $profile)
    {
        $this->authorize('update', $profile);

        $request->validate([
            'country' => ['required', 'string'],
            'address' => ['required', 'string'],
        ]);

        $client = new Client();
        $address = $request->address.', '.$request->country;
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=".env('GOOGLE_MAP_API_KEY', 'AIzaSyAA8RYM-kA3GdpJgoDI5eo5zjrNhJctNeY');
        $result = (string)$client->post($url)->getBody();
        $json =json_decode($result);
        $request['lat'] = $json->results[0]->geometry->location->lat;
        $request['lng'] = $json->results[0]->geometry->location->lng;

        $locations = $profile->locations()
                            ->create($request->all());

        return response($locations, 201);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brainr\Profile  $profile
     * @param  \Brainr\ProfileLocation  $location
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(Request $request, Profile $profile, ProfileLocation $location)
    {
        $this->authorize('update', $profile);

        $request->validate([
            'country' => ['required', 'string'],
            'address' => ['required', 'string'],
            'lat' => ['required', 'string'],
            'lng' => ['required', 'string'],
        ]);

        $location->fill($request->all())
                ->saveOrFail();

        return response(null, 204);
    }

    /**
     * @param  \Brainr\Profile  $profile
     * @param  \Brainr\ProfileLocation  $location
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Profile $profile, ProfileLocation $location)
    {
        $this->authorize('update', $profile);

        $location->delete();

        return response(null, 204);
    }
}
