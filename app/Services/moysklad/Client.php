<?php

namespace App\Services\moysklad;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class Client extends BaseModel
{
  const RESULT_LIVETIME = 60 * 60;
  const MAX_QUERIES = 45;
  const QUERIES_PERIOD = 3;
  private $queriesCount = 0;
  private $queryTime = 0;

  protected $baseUrl;
  protected $apikey;
  protected $client;
  protected $user;
  protected $pass;

  public function __construct($arguments)
  {
    parent::__construct($arguments);
    $headers = [
      'content-type' => "application/json; charset=utf-8",
      'Authorization' => "Bearer " . $this->apikey
    ];
    if (empty($this->apikey)) {
      $headers['Authorization'] = 'Basic ' . base64_encode($this->user . ":" . $this->pass);
    }
    $this->client = new \GuzzleHttp\Client(['headers' => $headers]);
    $this->queryTime = time();
  }

  /**
   * @param $uri
   * @param string $method
   * @param array $filter
   * @return array
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws Exception
   */
  protected function execute($uri, $filter = '', $fn = '')
  {
    $fn = empty($fn) ? $uri . '.json' : $fn;
    if (!Resource::exists($fn) || // true ||
      Resource::getCreateTime($fn) + self::RESULT_LIVETIME < time()
    ) {
      try {
        if (!empty($filter)) {
          $filters = explode(';', $filter);
          $partsPerQuery = floor(1950 / mb_strlen($filters[0]));
        } else {
          $filters = [''];
          $partsPerQuery = 1;
        }

        $result = [];
        for ($i = 0; $i < count($filters); $i += $partsPerQuery) {
          $filter = implode(';', array_slice($filters, $i, $partsPerQuery));
          $finishUri = $uri;
          if (!empty($filter)) {
            $finishUri = $uri . '?filter=' . $filter;
          }
          Log::debug('URL: ' . $this->baseUrl.$finishUri);
          $response = $this->download($this->baseUrl . $finishUri);
          $rows = (array)json_decode($response, JSON_OBJECT_AS_ARRAY);
          $result = array_merge($result, !empty($rows['rows']) ? $rows['rows'] : $rows);
        }
        Resource::putJson($fn, $result);

      } catch (RequestException $e) {
        Log::error($e->getMessage(), $e);
        $result = (array)json_decode($e->getResponse()->getBody(), JSON_OBJECT_AS_ARRAY);
        if (is_array($result)) {
          throw new Exception(implode("<br>", array_map(function ($error) {
            return $error['error'];
          }, $result['errors'])));
        } else {
          throw new Exception($e->getMessage());
        }
      }
      return $result;
    }
    return Resource::getJson($fn);
  }

  /**
   * @param $url
   * @return \Psr\Http\Message\StreamInterface
   * @throws GuzzleException
   */
  protected function download($url, $method = 'GET', $data = null)
  {
    if ($this->queriesCount >= self::MAX_QUERIES) {
      sleep( time() - $this->queryTime + self::QUERIES_PERIOD);
      $this->queriesCount = 0;
      $this->queryTime = time();
    }
    $this->queriesCount++;

    $options = [];
    if (!empty($data)){
      $options = ['body' => $data];
    }
    $response = $this->client->request($method, $url, $options);
    return $response->getBody();
  }

  /**
   * @param $uid
   * @return array
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getUserInfo($uid)
  {
    return $this->execute('entity/employee', "uid=$uid", $uid . '.json');
  }

  /**
   * @param $id
   * @return array
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getImages($id)
  {
    $rows = $this->execute("entity/variant/$id/images", '', $id . '_images.json');
    if (empty($rows)) {
      return [];
    }

    return array_filter(array_map(function ($image) {
      $url = $image['meta']['downloadHref'];
      if (empty($url)){
        Log::info('IMAGE: source href is empty');
        return '';
      }
      $newPath = "upload/" . Helpers::getLastUrlPart($image['meta']['downloadHref']) . ".jpg";
      if (file_exists($newPath)){
        return 'https://' . $_SERVER['SERVER_NAME'] . "/$newPath";
      }
      file_put_contents($newPath, $this->download($image['meta']['downloadHref']));
      return 'https://' . $_SERVER['SERVER_NAME'] . "/$newPath";
    }, (array) $rows), function($href){
      return !empty($href);
    });
  }

}
