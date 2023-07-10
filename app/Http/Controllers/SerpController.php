<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SerpController extends Controller
{

    public function NewSerp()
    {
        $locations = DB::table('serp_google_locations')->where('status', 1)->orderBy('location_name', 'ASC')->get();
        $languages = DB::table('serp_google_languages')->where('status', 1)->orderBy('language_name', 'ASC')->get();
        return view('seo.new-serp', compact('locations', 'languages'));
    }

    public function SerpQuery(Request $request)
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();

        $api_url = 'https://api.dataforseo.com/';
        try {
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        } catch (RestClientException $e) {
            echo "first not working";
            exit();
        }
        $post_array = array();
        $post_array[] = array(
            "url"   =>  "https://www.google.co.uk/search?q=%20rank%20tracker%20api&hl=en&gl=GB",
            "language_code" => $request->language,
            "location_code" => $request->location,
            "keyword" => mb_convert_encoding($request->keyword, "UTF-8"),
            "device"    =>  $request->device,
        );
        // $post_array[] = array(
        //     "language_name" => "English",
        //     "location_name" => "United States",
        //     "keyword" => mb_convert_encoding("amazon.com", "UTF-8"),
            // "url"    =>  "https://www.google.co.uk/search?q=%20rank%20tracker%20api&hl=en&gl=GB"
        // );
        try {
            set_time_limit(300);
            $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
            //prx($result);
            $date = date('Y-m-d H:i:s');
            if ($result['status_message'] == "Ok.") {
                if ($result['tasks'][0]['status_message'] == "Ok.") {
                    $all_results = $result['tasks'][0]['result'][0]['items'];
                    $keyword = $result['tasks'][0]['result'][0]['keyword'];
                    foreach ($all_results as $res) {
                        DB::table('serp_data')->insert([
                            'user_id' => Session::get('user_id'),
                            'serp_id'   =>  $result['tasks'][0]['id'],
                            'keyword'   =>  $keyword,
                            'rank_group' =>  $res['rank_group'],
                            'rank_absolute' =>  $res['rank_absolute'],
                            'type' =>  $res['type'],
                            'domain' =>  $res['domain'],
                            'title' =>  $res['title'],
                            'description' =>  utf8_encode($res['description']),
                            'url' =>  $res['url'],
                            'breadcrumb'  =>  $res['breadcrumb'],
                            'date'  =>  $date
                        ]);
                    }
                    return redirect('/user/serp/detail/'.$result['tasks'][0]['id']);
                    // return view('seo.serp-result', compact('all_results'));
                }
            } else {
                echo "not";
                die;
            }
        } catch (RestClientException $e) {
            echo "first not working";
            exit();
        }
        $client = null;
    }

    public function SerpHistory()
    {
        $serp_data = DB::table('serp_data')->where('user_id', Session::get('user_id'))->groupBy('serp_id')->orderBy('date', 'DESC')->where('status',1)->get();
        // $serp_data = DB::table('serp_data')->where('keyword', 'amazon')->orderBy('id','DESC')->get();
        // prx($serp_data);
        return view('seo.serp-history', compact('serp_data'));
    }

    public function SerpDetailHistory($id)
    {
        $keyword = null;
        $serp_data = DB::table('serp_data')->where('serp_id', $id)->orderBy('rank_group', 'ASC')->get();
        if($serp_data) {
            $keyword = $serp_data[0]->keyword;
        }
        return view('seo.serp-history-detail', compact('serp_data', 'keyword'));
    }
    
    public function SerpDeleteHistory($id)
    {
        DB::table('serp_data')->where('serp_id', $id)->update([
            'status'    =>  0
        ]);
        return redirect('/user/serp-history');
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
