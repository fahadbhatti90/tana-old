<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('ams.login-with-amazon');
    }

    /**
     * This function is used to get code of ams for authorization
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function code(Request $request)
    {
        try {
            $codeValues = $request->code;
            $client_id = 'amzn1.application-oa2-client.570442d019be482ea08dd54b03553b80';
            $client_secret = '0f91286bdbf41576d545a4d9714a541153910da2a0ce54dd60a48f3db6f3c868';
            $post_data = [
                'grant_type' => 'authorization_code',
                'code' => $codeValues,
                'redirect_uri' => route('ams.code'),
                'client_id' => $client_id,
                'client_secret' => $client_secret
            ];
            // Get Response CURL call
            $tokenUrl = Config::get('constants.amsAuthUrl');
            $client = new Client();
            $response = $client->request('POST', $tokenUrl, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'charset' => 'UTF-8'
                ],
                'form_params' => $post_data,
                'delay' => Config::get('constants.delayTimeInApi'),
                'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                'timeout' => Config::get('constants.timeoutInApi'),
            ]);
            $authBody = json_decode($response->getBody()->getContents());
            Log::info('Login With Amazon');
            Log::info(json_encode($authBody));
            $profileUrl = Config::get('constants.amsApiUrl') . '/' . Config::get('constants.apiVersion') . '/' . Config::get('constants.amsProfileUrl');
            // Get Response CURL call
            $client = new Client();
            $responseProfile = $client->request('GET', $profileUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $authBody->access_token,
                    'Content-Type' => 'application/json',
                    'Amazon-Advertising-API-ClientId' => $client_id
                ],
                'delay' => Config::get('constants.delayTimeInApi'),
                'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                'timeout' => Config::get('constants.timeoutInApi'),
            ]);
            $bodyProfile = json_decode($responseProfile->getBody()->getContents());
            Log::info(json_encode($bodyProfile));
            dd($bodyProfile);
            if (!empty($bodyAuth)) {
                try {
                    // verify that the access token belongs to us
                    $url = 'https://api.amazon.com/auth/o2/tokeninfo?access_token=' . urlencode($body->access_token);
                    $clientVerify = new Client();
                    $responseVerify = $clientVerify->request('GET', $url, [
                        'headers' => [
                            'Content-Type' => 'application/json'],
                        'delay' => Config::get('constants.delayTimeInApi'),
                        'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                        'timeout' => Config::get('constants.timeoutInApi'),
                    ]);
                    $bodyVerify = json_decode($responseVerify->getBody()->getContents());
                    if ($bodyVerify->aud != 'amzn1.application-oa2-client.71a9af3683d247449d06e1982a114496') {
                        // the access token does not belong to us
                        header('HTTP/1.1 404 Not Found');
                        echo 'Page not found';
                        exit;
                    }
                    Log::info('the access token does not belong to us');
                    Log::info(json_encode($bodyVerify));
                    // exchange the access token for user profile
                    $url = 'https://advertising-api.amazon.com/v2/profiles';
                    $clientProfile = new Client();
                    $responseProfile = $clientProfile->request('GET', $url, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $bodyAuth->access_token,
                            'Amazon-Advertising-API-ClientId' => 'amzn1.application-oa2-client.71a9af3683d247449d06e1982a114496',
                            'Content-Type' => 'application/json'],
                        'delay' => Config::get('constants.delayTimeInApi'),
                        'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                        'timeout' => Config::get('constants.timeoutInApi'),
                    ]);
                    $bodyProfile = json_decode($responseProfile->getBody()->getContents());
                    Log::info('exchange the access token for user profile');
                    Log::info(json_encode($bodyProfile));
                    $request->session()->flash('message', 'Successfully Added.');
                    return redirect('/ams/apiconfig');
                    echo '<br>';
                    echo '21312';
                    exit;
                } catch (\Exception $ex) {
                    echo 'get profile information';
                    dd($ex);
                }
            } else {
                dd('not found data.');
            }
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function AmazonLogin1($code = NULL, Request $request)
    {
        dd($request->all());
        if ($code != NULL) {
            try {
                $codeValues = $request->code;
                $post_data = ['grant_type' => 'authorization_code',
                    'refresh_token' => '',
                    'code' => $codeValues,
                    'redirect_uri' => '',
                    'client_id' => '',
                    'client_secret' => ''];
                // Get Response CURL call
                $client = new Client();
                $response = $client->request('POST', 'https://api.amazon.com/auth/o2/token', [
                    'headers' => ['Content-Type' => 'application/x-www-form-urlencoded',
                        'charset' => 'UTF-8'],
                    'form_params' => $post_data,
                    'delay' => Config::get('constants.delayTimeInApi'),
                    'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                    'timeout' => Config::get('constants.timeoutInApi'),
                ]);
                $body = json_decode($response->getBody()->getContents());
                Log::info('Login With Amazon');
                Log::info(json_encode($body));
                // send call to get access token
                $url = Config::get('constants.amsAuthUrl');
                $post_data_auth = ['grant_type' => 'refresh_token',
                    'refresh_token' => $body->refresh_token,
                    'client_id' => '',
                    'client_secret' => ''];
                // Get Response CURL call
                $responseAuth = $client->request('POST', $url, [
                    'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                    'form_params' => $post_data_auth,
                    'delay' => Config::get('constants.delayTimeInApi'),
                    'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                    'timeout' => Config::get('constants.timeoutInApi'),
                ]);
                $bodyAuth = json_decode($responseAuth->getBody()->getContents());
                echo '<pre>';
                echo 'get authorization code' . '<br/>';
                print_r($bodyAuth->access_token);
                if (!empty($bodyAuth)) {
                    try {
                        // verify that the access token belongs to us
                        $url = 'https://api.amazon.com/auth/o2/tokeninfo?access_token=' . urlencode($body->access_token);
                        $clientVerify = new Client();
                        $responseVerify = $clientVerify->request('GET', $url, [
                            'headers' => [
                                'Content-Type' => 'application/json'],
                            'delay' => Config::get('constants.delayTimeInApi'),
                            'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                            'timeout' => Config::get('constants.timeoutInApi'),
                        ]);
                        $bodyVerify = json_decode($responseVerify->getBody()->getContents());
                        if ($bodyVerify->aud != 'amzn1.application-oa2-client.71a9af3683d247449d06e1982a114496') {
                            // the access token does not belong to us
                            header('HTTP/1.1 404 Not Found');
                            echo 'Page not found';
                            exit;
                        }
                        Log::info('the access token does not belong to us');
                        Log::info(json_encode($bodyVerify));
                        // exchange the access token for user profile
                        $url = 'https://advertising-api.amazon.com/v2/profiles';
                        $clientProfile = new Client();
                        $responseProfile = $clientProfile->request('GET', $url, [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $bodyAuth->access_token,
                                'Amazon-Advertising-API-ClientId' => 'amzn1.application-oa2-client.71a9af3683d247449d06e1982a114496',
                                'Content-Type' => 'application/json'],
                            'delay' => Config::get('constants.delayTimeInApi'),
                            'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                            'timeout' => Config::get('constants.timeoutInApi'),
                        ]);
                        $bodyProfile = json_decode($responseProfile->getBody()->getContents());
                        Log::info('exchange the access token for user profile');
                        Log::info(json_encode($bodyProfile));
                        $request->session()->flash('message', 'Successfully Added.');
                        return redirect('/ams/apiconfig');
                        echo '<br>';
                        echo '21312';
                        exit;
                    } catch (\Exception $ex) {
                        echo 'get profile information';
                        dd($ex);
                    }
                } else {
                    dd('not found data.');
                }
            } catch (\Exception $ex) {
                dd($ex);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function AmazonLogin(Request $request)
    {
        $message = '';
        $class = '';
        try {
            $access_token = $request['access_token'];
            $urlTokenValid = 'https://api.amazon.com/auth/o2/tokeninfo?access_token=' . $access_token;
            $urlProfile = 'https://api.amazon.com/user/profile';
            $clientId = 'amzn1.application-oa2-client.c9f64774daa347ad8f741984216ace51';
            // Get Response CURL call
            $client = new Client();
            $responseToken = $client->request('GET', $urlTokenValid, [
                'headers' => [
                    'Content-Type' => 'application/json'],
                'delay' => Config::get('constants.delayTimeInApi'),
                'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                'timeout' => Config::get('constants.timeoutInApi'),
            ]);
            $bodyToken = json_decode($responseToken->getBody()->getContents());
            Log::info('Token Detail :' . json_encode($bodyToken));
            if (!empty($bodyToken)) {
                if ($bodyToken->exp == 0) {
                    Session::flash('lwamessage', 'Your Session Expired');
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('amsApiConfig');
                }
                if ($bodyToken->aud != $clientId) {
                    // the access token does not belong to us
                    Session::flash('lwamessage', 'Your Client Not map With it.');
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('amsApiConfig');
                }
                if ($bodyToken->exp > 0) {
                    $dataArray = array(
                        'aud' => $bodyToken->aud,
                        'user_id' => $bodyToken->user_id,
                        'iss' => $bodyToken->iss,
                        'exp' => $bodyToken->exp,
                        'app_id' => $bodyToken->app_id,
                        'iat' => $bodyToken->iat,
                        'createdAt' => date('Y-m-d H:i:s'),
                        'updatedAt' => date('Y-m-d H:i:s')
                    );
                    LWAModel::storeTokenDetailData($dataArray);
                }
                // exchange the access token for user profile
                $responseProfile = $client->request('GET', $urlProfile, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $access_token,
                        'Content-Type' => 'application/json'],
                    'delay' => Config::get('constants.delayTimeInApi'),
                    'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                    'timeout' => Config::get('constants.timeoutInApi'),
                ]);
                $bodyProfile = json_decode($responseProfile->getBody()->getContents());
                Log::info('Profile Detail :' . json_encode($bodyToken));
                $dataProfileArray = array(
                    'user_id' => $bodyProfile->user_id,
                    'name' => $bodyProfile->name,
                    'email' => $bodyProfile->email,
                    'createdAt' => date('Y-m-d H:i:s'),
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $response = LWAModel::storeProfileDetailData($dataProfileArray);
                if ($response['status'] == 'true') {
                    $class = 'alert-success';
                    $message = 'Thank you for your login!. Your ' . $bodyProfile->user_id . ' , ' . $bodyProfile->name . ' and ' . $bodyProfile->email . ' stored. session time:' . $bodyToken->exp;
                } else if ($response['status'] == 'already') {
                    $class = 'alert-success';
                    $message = 'Thank you for your login!. Your ' . $bodyProfile->user_id . ' , ' . $bodyProfile->name . ' and ' . $bodyProfile->email . ' stored. session time:' . $bodyToken->exp;
                } else if ($response['status'] == 'false') {
                    $class = 'alert-danger';
                    $message = 'record not insert in DB.';
                }
            }
            Session::flash('lwamessage', $message);
            Session::flash('bodyToken', json_encode($bodyToken));
            Session::flash('bodyProfile', json_encode($bodyProfile));
            Session::flash('alert-class', $class);
            return redirect()->route('amsApiConfig');
        } catch (\Exception $ex) {
            Session::flash('lwamessage', $ex->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('amsApiConfig');
        }
    }

    public function AccountSetup(Request $request)
    {
        dd($request);
    }

}
