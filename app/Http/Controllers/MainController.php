<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;

use function PHPUnit\Framework\returnValueMap;

class MainController extends Controller
{
    public function index(Request $request)
    {           
        return view('welcome');
    }

    public function login()
    {      
        return Redirect::to('https://accounts.spotify.com/authorize?client_id=70b74b2cbcbd4656b556c098361f9bb1&response_type=token&redirect_uri=http://spotify-player-widget.test/callback?&scope=streaming%20user-read-email%20user-read-private%20user-library-read%20user-library-modify%20user-read-playback-state%20user-modify-playback-state');
    }

    public function login2()
    {      
        return Redirect::to('https://accounts.spotify.com/authorize?client_id=70b74b2cbcbd4656b556c098361f9bb1&response_type=code&redirect_uri=http://spotify-player-widget.test/call-test?&scope=streaming%20user-read-email%20user-read-private%20user-library-read%20user-library-modify%20user-read-playback-state%20user-modify-playback-state');
    }

    public function player($id) {
        $user = User::where('widget_id', '=', $id)->first();

        return view('player.1', compact('user'));
        // dd($user);
    }

    public function callback(Request $request) {
        return view('callback');
    }

    public function callback2(Request $request) {
        return view('callback2');
    }

    public function redirect(Request $request) {
        $user = User::where('spot_id', '=', $request['spotify_id'])->first();

        if (!$user){
            User::create([
                'name' => $request['display_name'],
                'spot_id' => $request['spotify_id'],
                'img' => $request['images'],
                'access_token'=> $request['token_type'].' '.$request['access_token'],
                'email'=> $request['email'],    
                'widget_id' => $this->generate_id(20)       
            ]);

            Session::put('spot_id', $request['spotify_id']);
            Session::put('access_token', $request['token_type'].' '.$request['access_token']);
            return response()->json([
                'response' =>  'Success, New user',
                'goto' =>  route('dashboard')
            ]); 
        } else {

            $user->update([
                'access_token'=> $request['token_type'].' '.$request['access_token'],
            ]);

            Session::put('spot_id', $request['spotify_id']);
            Session::put('access_token', $request['token_type'].' '.$request['access_token']);
            return response()->json([
                'response' =>  'Success, logging in',
                'goto' =>  route('dashboard')
            ]); 
        };
    }

    public function redirect2(Request $request) {
        $user = User::where('spot_id', '=', $request['spotify_id'])->first();

        if (!$user){
            User::create([
                'name' => $request['display_name'],
                'spot_id' => $request['spotify_id'],
                'img' => $request['images'],
                'access_token'=> $request['access_token'],
                'refresh_token'=> $request['refresh_token'],
                'email'=> $request['email'],    
                'widget_id' => $this->generate_id(20)       
            ]);

            Session::put('spot_id', $request['spotify_id']);
            Session::put('access_token', $request['token_type'].' '.$request['access_token']);

            Auth::login($user);

            return response()->json([
                'response' =>  'Success, New user',
                'goto' =>  route('dashboard')
            ]); 
        } else {

            $user->update([
                'access_token'=> $request['access_token'],
                'refresh_token'=> $request['refresh_token'],
            ]);

            Session::put('spot_id', $request['spotify_id']);
            Session::put('access_token', $request['token_type'].' '.$request['access_token']);
            
            Auth::login($user);

            return response()->json([
                'response' =>  'Success, logging in',
                'goto' =>  route('dashboard')
            ]); 
        };
    }

    public function test(Request $request) {
        dd();
        
    }

    public function http_test(Request $request) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://accounts.spotify.com/api/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'code=AQDGCJ6DI0ecZsMtCYHb80fTD0FhWOQwl82o0D0oCwUdAz-1bMiea2VIDX8ygunsX_0kpaIcupKSTf8NNYa2x3xdZaqgGkxqeT2DpPP0zCo2yG78ylsCMu811f0O7DL7NHslUXRnjk2RpyyFnMqFrpmWXakIC3tAhXheMr80I4ZCFJtpcqlqSUv9rv5jmElFEdAOybsZtUDk2y93qem1XDMGCD2wnj14ASbCu53Wwb8kr_3ut6Noil4crZr5kSCUWqagz4oUl7cZNcDOgdfP4zsqSalQszuWohFlGoiEXDmGTHRxlPM8-vT8s2lFqvxemipSqubQWNajou2nMwhw9_v9CiWvXmuCO6sFXOrAsd5tsYoE1u5b75rkpepC&grant_type=authorization_code&redirect_uri=http://spotify-player-widget.test/call-test?',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic '. base64_encode('70b74b2cbcbd4656b556c098361f9bb1:8e740dd1e2454f8db1149aee1d41f6aa')
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo($response);
    }

    function generate_id($limit) {
        $key = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);

        $check = User::where('widget_id', '=', $key)->first();

        while($check != null){
            $key = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
            $check = User::where('widget_id', '=', $key)->first();
        };
       

        return $key;
    }
}
