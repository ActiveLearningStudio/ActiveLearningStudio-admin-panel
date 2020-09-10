<?php
namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Log;
use Throwable;

trait RequestTrait
{
    /**
     * Public properties
     */
    public $apiV = '/v1/admin';
    public $headers = ['Accept' => 'application/json'];
    public $response = [];

    /**
     * @param $end_point
     * @param array $params
     * @return array|mixed
     * @throws Throwable
     */
    public function getHTTP($end_point, $params = [])
    {
        $this->response = Http::withToken(session('access_token'))->withHeaders($this->headers)->get(api_url() . $this->apiV . $end_point, $params);
        throw_if($this->response->failed() || $this->response->serverError(), new GeneralException($this->getError()));
        return $this->response->json();
    }

    /**
     * @param $end_point
     * @param array $params
     * @return array|RedirectResponse|mixed
     * @throws Throwable
     */
    public function postHTTP($end_point, $params = [])
    {
        $this->response = Http::withToken(session('access_token'))->withHeaders($this->headers)->post(api_url() . $this->apiV . $end_point, $params);
        if ($this->response->status() === 422) {
            return redirect()->back()->withErrors($this->response->json()['errors'])->withInput();
        }
        throw_if($this->response->failed() || $this->response->serverError(), new GeneralException($this->getError()));
        return $this->response->json();
    }

    /**
     * @param $end_point
     * @param array $params
     * @return array|RedirectResponse|mixed
     * @throws Throwable
     */
    public function deleteHTTP($end_point, $params = [])
    {
        $this->response = Http::withHeaders($this->headers)->delete(api_url() . $this->apiV . $end_point)->withToken(session('access_token'));
//        dd($this->response, $this->response->body());
        throw_if($this->response->failed() || $this->response->serverError(), new GeneralException($this->getError()));
        return $this->response->json();
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        $error = "REMOTE SERVER ERROR!";
        if (isset($this->response->json()['errors'])) {
            $error = implode("<br>", $this->response->json()['errors']);
        }
        Log::info($error);
        return $error;
    }
}
