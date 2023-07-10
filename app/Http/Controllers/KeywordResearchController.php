<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class KeywordResearchController extends Controller
{
    public function RelatedKeywords()
    {
         $keywords_count = 0;
        $languages = DB::table('serp_google_languages')->where('status', 1)->orderBy('language_name', 'ASC')->get();
        $locations = DB::table('serp_google_locations')->where('status', 1)->orderBy('location_name', 'ASC')->get();
        $keywords_data = DB::table('related_keywords_data')->where('user_id', Session::get('user_id'))->orderBy('date', 'DESC')->where('status',1)->groupBy('related_keywords_id')->get();
      	foreach ($keywords_data as $ref) {
            $month = date("m",strtotime($ref->date));
            $year = date("Y",strtotime($ref->date));
            if($month == date('m') && $year == date('Y')) { 
               $keywords_count++;
            }
        } 
        return view('seo.related-keywords', compact('languages', 'locations', 'keywords_count'));
    }

    public function RelatedKeywordsHistory() 
    {
        $keywords_data = DB::table('related_keywords_data')->where('user_id', Session::get('user_id'))->orderBy('date', 'DESC')->where('status',1)->groupBy('related_keywords_id')->get();
        return view('seo.related-keywords-history', compact('keywords_data'));
    }

    public function RelatedKeywordDetail($id)
    {
        $keywords_data = DB::table('related_keywords_data')->where('related_keywords_id', $id)->orderBy('id', 'ASC')->groupBy('related_keyword')->get();
        // prx($keywords_data[0]->keyword);
        $location_query = DB::table('google_adwords_locations')->where('location_code', $keywords_data[0]->location_code)->first();
        $language_query = DB::table('google_adwords_languages')->where('language_code', $keywords_data[0]->language_code)->first();
        $location = $location_query->location_name;
        $language = $language_query->language_name;
        return view('seo.related-keyword-detail', compact('keywords_data', 'language', 'location'));
    }

    public function RelatedKeywordMonthlyDetail($id, $keyword)
    {
        $keywords_data = DB::table('related_keywords_data')->where([
            'user_id' => Session::get('user_id'),
            'related_keyword'   =>  $keyword,
            'related_keywords_id'   =>  $id
        ])->get();
        return view('seo.related-keyword-monthly-detail', compact('keywords_data'));
    }

    public function RelatedKeywordDelete($id)
    {
        DB::table('related_keywords_data')->where([
            'related_keywords_id'   =>  $id
        ])->update([
            'status'    =>  0
        ]);
        // DB::table('related_keywords_data')->where([
        //     'related_keywords_id'   =>  $id
        // ])->delete();
        // prx($keywords_data);
        return redirect()->back();
    }

    public function RelatedKeywordsLocationsLanguages()
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();

        DB::table('related_keywords_languages')->delete();
        DB::table('related_keywords_locations')->delete();
        $api_url = 'https://api.dataforseo.com/';
        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);

        try {
            set_time_limit(500);
            $result = $client->get('/v3/dataforseo_labs/locations_and_languages');
            $result = json_decode(json_encode($result), false);
            // prx($result);
            if ($result->status_message == "Ok.") {
                $locations = $result->tasks[0]->result;
                // prx($locations);
                foreach ($locations as $loc) {
                    // prx($loc->location_type);
                    if ($loc->location_type == "Country") {
                        DB::table('related_keywords_locations')->insert([
                            'location_code' => $loc->location_code,
                            'location_name' => $loc->location_name,
                            'location_code_parent' => $loc->location_code_parent,
                            'country_iso_code' => $loc->country_iso_code,
                            'location_type' => $loc->location_type
                        ]);
                    }
                    foreach ($loc->available_languages as $language) {
                        $language_exists = DB::table('related_keywords_languages')->where('language_name', $language->language_name)->get();
                        if (count($language_exists) < 1) {
                            DB::table('related_keywords_languages')->insert([
                                'language_name' => $language->language_name,
                                'language_code' => $language->language_code,
                            ]);
                        }
                    }
                }
                echo "ok";
            }
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

    public function RelatedKeywordsQuery(Request $request)
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();

        $language = $request->language;
        $location = $request->location;
        // prx($request->post());
        $related_keywords_id = null;
        $api_url = 'https://api.dataforseo.com/';
        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        $post_array = array();
        // simple way to set a task
        if (isset($request->max_cpc) && $request->max_cpc != null && isset($request->min_clicks) && $request->min_clicks != null) {
            $post_array[] = array(
                "keyword" => $request->keyword,
                "language_code" => $language,
                "location_code" => $location,
                "filters" => [
                    ["keyword_data.impressions_info.cpc_max", "<", $request->max_cpc],
                    "and",
                    ["keyword_data.impressions_info.daily_clicks_min", ">=", $request->min_clicks]
                ]
            );
        } else if (isset($request->max_cpc) && $request->max_cpc != null) {
            $post_array[] = array(
                "keyword" => $request->keyword,
                "language_code" => $language,
                "location_code" => $location,
                "filters" => [
                    ["keyword_data.impressions_info.cpc_max", "<", $request->max_cpc]
                ]
            );
        } else if (isset($request->min_clicks) && $request->min_clicks != null) {
            $post_array[] = array(
                "keyword" => $request->keyword,
                "language_code" => $language,
                "location_code" => $location,
                "filters" => [
                    ["keyword_data.impressions_info.daily_clicks_min", ">=", $request->min_clicks]
                ]
            );
        } else {
            $post_array[] = array(
                "keyword" => $request->keyword,
                "language_code" => $language,
                "location_code" => $location,
                "depth"     =>  2
            );
        }


        try {
            set_time_limit(500);
            $result = $client->post('/v3/dataforseo_labs/related_keywords/live', $post_array);
            // prx($result);
            $result = json_decode(json_encode($result), false);
            // prx($result);
            if ($result->status_message == "Ok.") {
                if ($result->tasks[0]->status_message == "Ok.") {
                    $user_id = Session::get('user_id');
                    $related_keywords_id = $result->tasks[0]->id;
                    $keyword = $request->keyword;
                    $all_results = $result->tasks[0]->result[0]->items;
                    $date = date('Y-m-d H:i:s');
                    if (isset($all_results) && count($all_results) > 0) {
                        foreach ($all_results as $res) {
                            $related_keyword = $res->keyword_data->keyword;
                            $competition = $res->keyword_data->keyword_info->competition;
                            $cpc = $res->keyword_data->keyword_info->cpc;
                            $total_volume = $res->keyword_data->keyword_info->search_volume;
                            $daily_clicks_average = $res->keyword_data->impressions_info->daily_clicks_average;
                            // $keyword_difficulty = $res->keyword_data->serp_info->keyword_difficulty;
                            $monthly_searches = $res->keyword_data->keyword_info->monthly_searches;
                            foreach ($monthly_searches as $searches) {
                                DB::table('related_keywords_data')->insert([
                                    'user_id'   =>  $user_id,
                                    'related_keywords_id'   =>  $related_keywords_id,
                                    'keyword'   =>  $keyword,
                                    'language_code' =>  $language,
                                    'location_code' =>  $location,
                                    'related_keyword'   =>  $related_keyword,
                                    'competition'   =>  $competition,
                                    'cpc'   =>  $cpc,
                                    'total_volume'  =>  $total_volume,
                                    'daily_clicks_average'  =>  $daily_clicks_average,
                                    'year'  =>  $searches->year,
                                    'month' =>  $searches->month,
                                    'search_volume' =>  $searches->search_volume,
                                    'date'  =>  $date
                                ]);
                            }
                        }
                    } else {
                        $request->session()->flash('message', 'No Data Found');
                        $request->session()->flash('alert-type', 'info');
                        return redirect()->back();
                    }
                    // prx($all_results);
                } else {
                    $request->session()->flash('message', 'Something went wrong');
                    $request->session()->flash('alert-type', 'error');
                    return redirect()->back();
                }
                // echo "ok";
            } else {
                $request->session()->flash('message', 'Something went wrong');
                $request->session()->flash('alert-type', 'error');
                return redirect()->back();
            }
            // echo "Done";
        } catch (RestClientException $e) {
            $request->session()->flash('message', 'Something went wrong');
            $request->session()->flash('alert-type', 'error');
            return redirect()->back();
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
        }
        $client = null;

        return redirect('/user/related-keyword/detail/' . $related_keywords_id);
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
