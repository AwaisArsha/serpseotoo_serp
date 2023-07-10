<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Session;

class KeywordsController extends Controller
{
    public function SearchVolume()
    {
        $locations = DB::table('serp_google_locations')->where('status', 1)->orderBy('location_name', 'ASC')->get();
        $languages = DB::table('serp_google_languages')->where('status', 1)->orderBy('language_name', 'ASC')->get();
        return view('seo.search-volume', compact('locations', 'languages'));
    }

    public function keweordLocations(Request $request)
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();

        $api_url = 'https://api.dataforseo.com/';
        try {
            // Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
            exit();
        }
        try {
            set_time_limit(500);
            $result = $client->get('/v3/keywords_data/google/locations');
            $result = json_decode(json_encode($result), false);
            // prx($result);
            if ($result->status_message == "Ok.") {
                $locations = $result->tasks[0]->result;
                // prx($locations);
                foreach ($locations as $loc) {
                    // prx($loc->location_type);
                    if ($loc->location_type == "Country") {
                        DB::table('google_adwords_locations')->insert([
                            'location_code' => $loc->location_code,
                            'location_name' => $loc->location_name,
                            'location_code_parent' => $loc->location_code_parent,
                            'country_iso_code' => $loc->country_iso_code,
                            'location_type' => $loc->location_type
                        ]);
                    }
                }
                echo "ok";
            }
            echo "Done";
            // print_r($result);
            // do something with result
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
        }
        $client = null;
    }
    public function keweordLanguages(Request $request)
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();

        $api_url = 'https://api.dataforseo.com/';
        try {
            // Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
            exit();
        }
        try {
            set_time_limit(500);
            $result = $client->get('/v3/keywords_data/google/languages');
            $result = json_decode(json_encode($result), false);
            if ($result->status_message == "Ok.") {
                $languages = $result->tasks[0]->result;
                foreach ($languages as $lan) {
                    DB::table('google_adwords_languages')->insert([
                        'language_name' => $lan->language_name,
                        'language_code' => $lan->language_code,
                    ]);
                }
                echo "ok";
            }
            echo "Done";
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
        }
        $client = null;
    }

    public function AdwordsStatus()
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        
        $api_url = 'https://api.dataforseo.com/';
        try {
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
            exit();
        }
        try {
            // using this method you can get the AdWords status
            // GET /v3/keywords_data/google/adwords_status
            $result = $client->get('/v3/keywords_data/google/adwords_status');
            prx($result);
            // do something with result
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
        }
        $client = null;
    }

    public function VolumeQuery(Request $request)
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        $api_url = 'https://api.dataforseo.com/';
        try {
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
            exit();
        }
        $post_array = array();
        $location_code = $request->location_code;
        $language_code = $request->language_code;
        $keyword = $request->keyword;
        $date = date('Y-m-d H:i:s');
        $post_array[] = array(
            "location_code" => $location_code,
            "language_code" => $language_code,
            "keywords" => array(
                $keyword
            )
        );
        try {
            // POST /v3/keywords_data/google/search_volume/live
            // the full list of possible parameters is available in documentation
            $result = $client->post('/v3/keywords_data/google/search_volume/live', $post_array);
            $result = json_decode(json_encode($result), false);
            if($result->status_message == "Ok.") {
                $search_volume_id = $result->tasks[0]->id;
                if($result->tasks[0]->status_message == "Ok.") {
                    $final_result = $result->tasks[0]->result[0];
                    
                    $competition = $final_result->competition;
                    $cpc = $final_result->cpc;
                    $total_search_volume = $final_result->search_volume;
                    $monthly_volumes = $final_result->monthly_searches;
                    foreach ($monthly_volumes as $volume) {
                        DB::table('google_adwords_search_volume')->insert([
                            'user_id'   =>  Session::get('user_id'),
                            'search_volume_id'  =>  $search_volume_id,
                            'keyword'           =>  $keyword,
                            'language_code'     =>  $language_code,
                            'location_code'     =>  $location_code,
                            'competition'       =>  $competition,
                            'cpc'               =>  $cpc,
                            'total_search_volume'   =>  $total_search_volume,
                            'year'              =>  $volume->year,
                            'month'              => $volume->month,
                            'search_volume'      => $volume->search_volume,
                            'date'              =>  $date
                        ]);
                    }
                    // echo "done";
                    return redirect('/user/search-volume/detail/'.$search_volume_id);
                }
                // echo "ok";
            }
            // prx($result);
            // do something with post result
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
        }
        $client = null;
    }
    
    public function SearchVolumeDetail($id)
    {
        $volume_data = DB::table('google_adwords_search_volume')->where('search_volume_id', $id)->orderBy('id', 'ASC')->get();
        $location_query = DB::table('google_adwords_locations')->where('location_code', $volume_data[0]->location_code)->first();
        $language_query = DB::table('google_adwords_languages')->where('language_code', $volume_data[0]->language_code)->first();
        // prx($language_query->language_name);
        $location = $location_query->location_name;
        $language = $language_query->language_name;
        return view('seo.search-volume-result', compact('volume_data', 'language', 'location'));

    }
    
    public function SearchVolumeHistory()
    {
        $volume_data = DB::table('google_adwords_search_volume')->where('user_id', Session::get('user_id'))->where('status', 1)->orderBy('date', 'DESC')->groupBy('search_volume_id')->get();
        return view('seo.search-volume-history', compact('volume_data'));

    }

    public function SearchVolumeDelete($id)
    {
        DB::table('google_adwords_search_volume')->where('search_volume_id', $id)->update([
            'status'    =>   0
        ]);
        return redirect()->back();
    }
}





class RestClient
{
    public string $host; // the url to the rest server
    public ?int $port = null;
    public string $scheme;
    public string $post_type = 'json';
    public int $timeout = 60;
    public int $connection_timeout = 10;
    private ?string $token; // Auth token
    private ?string $ba_user;
    private ?string $ba_password;
    private ?string $ba_ua;
    public string $last_url = '';
    public $last_response = null;
    public $last_http_code = null;

    public function __construct(
        string $host,
        string $token = null,
        string $ba_user = null,
        string $ba_password = null,
        string $ba_user_agent = null
    ) {
        $arr_h = parse_url($host);
        if (isset($arr_h['port'])) {
            $this->port = (int)$arr_h['port'];
            $this->host = str_replace(":" . $this->port, "", $host);
        } else {
            $this->port = null;
            $this->host = $host;
        }
        if (isset($arr_h['scheme'])) {
            $this->scheme = $arr_h['scheme'];
        }
        $this->token = $token;
        $this->ba_user = $ba_user;
        $this->ba_password = $ba_password;
        $this->ba_ua = $ba_user_agent;
    }

    /**
     * Returns the absolute URL
     *
     * @param string $raw_headers
     */
    private function http_parse_headers(string $raw_headers): array
    {
        $headers = array();
        $key = '';

        foreach (explode("\n", $raw_headers) as $h) {
            $h = explode(':', $h, 2);
            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                } else {
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                }
                $key = $h[0];
            } else {
                if (substr($h[0], 0, 1) == "\t") {
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } elseif (!$key) {
                    $headers[0] = trim($h[0]);
                }
            }
        }

        return $headers;
    }

    /**
     * Returns the absolute URL
     *
     * @param string|null $url
     * @return string
     */
    private function url(string $url = null): string
    {
        $_host = rtrim($this->host, '/');
        $_url = ltrim($url, '/');

        return "{$_host}:{$this->port}/{$_url}";
    }

    /**
     * Returns the URL with encoded query string params
     *
     * @param string $url
     * @param array|null $params
     * @return string
     */
    private function urlQueryString(string $url, array $params = null): string
    {
        $qs = array();
        if ($params) {
            foreach ($params as $key => $value) {
                $qs[] = "{$key}=" . urlencode($value);
            }
        }

        $url = explode('?', $url);
        if (isset($url[1])) {
            $url_qs = $url[1];
        }
        $url = $url[0];
        if (isset($url_qs)) {
            $url = "{$url}?{$url_qs}";
        }

        if (count($qs)) {
            return "{$url}?" . implode('&', $qs);
        } else {
            return $url;
        }
    }

    /**
     * Make an HTTP request using cURL
     *
     * @param string $verb
     * @param string $url
     * @param array $params
     */
    private function request(string $verb, string $url, array $params = array())
    {

        $ch = curl_init(); // the cURL handler
        $url = $this->url($url); // the absolute URL
        $request_headers = array();
        if (!empty($this->token)) {
            $request_headers[] = "Authorization: {$this->token}";
        }

        if ((!empty($this->ba_user)) and (!empty($this->ba_password))) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->ba_user . ":" . $this->ba_password);
        }

        // encoded query string on GET
        switch (true) {
            case 'GET' == $verb:
                $url = $this->urlQueryString($url, $params);
                break;
            case in_array($verb, array(
                'POST',
                'PUT',
                'DELETE'
            ), false):
                if ($this->post_type == 'json') {
                    $request_headers[] = 'Content-Type: application/json';
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                }
        }

        // set the URL
        curl_setopt($ch, CURLOPT_URL, $url);
        $this->last_url = $url;

        // set the HTTP verb for the request
        switch ($verb) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case 'PUT':
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connection_timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        if (!empty($this->ba_ua)) {
            curl_setopt($ch, CURLOPT_USERAGENT, $this->ba_ua);
        }
        if (!empty($this->port)) {
            curl_setopt($ch, CURLOPT_PORT, $this->port);
        }
        if ((!empty($this->scheme)) and ($this->scheme == 'https')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = $this->http_parse_headers(substr($response, 0, $header_size));
        $response = substr($response, $header_size);
        $http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $content_error = curl_error($ch);
        //var_dump($content_error);

        curl_close($ch);

        if (strpos($content_type, 'json')) {
            $response = json_decode($response, true);
        }

        $this->last_response = $response;
        $this->last_http_code = $http_code;

        switch (true) {
            case 'GET' == $verb:
                if ($http_code !== 200) {
                    if (is_array($response)) {
                        $this->throw_error($response, $http_code);
                    } else {
                        $this->throw_error(trim($content_error . "\n" . $response), $http_code);
                    }
                }
                return $response;
            case in_array($verb, array(
                'POST',
                'PUT',
                'DELETE'
            ), false):
                if (($http_code !== 303) and ($http_code !== 200)) {
                    if (is_array($response)) {
                        $this->throw_error($response, $http_code);
                    } else {
                        $this->throw_error(trim($content_error . "\n" . $response), $http_code);
                    }
                }
                if ($http_code === 200) {
                    return $response;
                } else {
                    return str_replace(rtrim($this->host, '/') . '/', '', $headers['Location']);
                }
        }
    }

    private function throw_error($response, $http_code)
    {
        if (is_array($response) && array_key_exists('error', $response)) {
            if ((isset($response['error']['message'])) and (isset($response['error']['code']))) {
                if (is_array($response['error']['message'])) {
                    throw new RestClientException(
                        implode("; ", $response['error']['message']),
                        (int)$response['error']['code'],
                        $http_code
                    );
                } else {
                    throw new RestClientException($response['error']['message'], (int)$response['error']['code'], $http_code);
                }
            } else {
                throw new RestClientException(implode("; ", $response), 0, $http_code);
            }
        } else {
            if (is_string($response)) {
                throw new RestClientException($response, 0, $http_code);
            } else {
                throw new RestClientException(json_encode($response), 0, $http_code);
            }
        }
    }

    /**
     * Make an HTTP GET request
     *
     * @param string $url
     * @param array $params
     */
    public function get($url, $params = array())
    {
        return $this->request('GET', $url, $params);
    }

    /**
     * Make an HTTP POST request
     *
     * @param string $url
     * @param array $params
     */
    public function post($url, $params = array())
    {
        return $this->request('POST', $url, $params);
    }

    /**
     * Make an HTTP PUT request
     *
     * @param string $url
     * @param array $params
     */
    public function put($url, $params = array())
    {
        return $this->request('PUT', $url, $params);
    }

    /**
     * Make an HTTP DELETE request
     *
     * @param string $url
     * @param array $params
     */
    public function delete($url, $params = array())
    {
        return $this->request('DELETE', $url, $params);
    }
}

class RestClientException extends Exception
{
    protected $http_code;

    public function __construct(string $message, int $code = 0, int $http_code = 0, Exception $previous = null)
    {
        $this->http_code = $http_code;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int the http code error representation of the exception.
     */
    public function getHttpCode()
    {
        return $this->http_code;
    }

    /**
     * @return string the string representation of the exception.
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message} (HTTP status code: {$this->http_code})\n";
    }
}
