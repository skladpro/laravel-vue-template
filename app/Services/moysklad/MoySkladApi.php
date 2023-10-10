<?php

namespace App\moysklad;

use App\Helpers;
use App\Log;
use GuzzleHttp\Exception\GuzzleException;

class MoySkladApi extends Client
{
    /**
     * @param null $filter
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAssortment($filter = [], $fn = '')
    {
        return $this->execute('entity/assortment', $filter, $fn);
    }

    /**
     * @param $href
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProductfolderVariants($href)
    {
        $fn = Helpers::getLastUrlPart($href) . '.json';
        $filter = [
            "productFolder=$href",
            "type=variant"
        ];
        return $this->getAssortment(implode(';', $filter), $fn);
    }

    /**
     * @param $href
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProductfolderProducts($href)
    {
        $fn = Helpers::getLastUrlPart($href) . '.json';
        $filter = [
            "productFolder=$href",
            "type=product",
        ];
        return array_filter($this->getAssortment(implode(';', $filter), $fn), function($item){
          if (empty($item['id'])){
            Log::info('EMPTY: product folder id is empty. ' . $item['name']);
          }
          return !empty($item['id']);
        });
    }

    /**
     * @param $id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getVariant($id)
    {
        $fn = $id . '.json';
        return $this->getAssortment("id=$id", $fn);
    }

    /**
     * @param $id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getVariants($ids)
    {
        $fn = time() . '.json';
        $filter = array_map(function ($id) {
            return "id=$id";
        }, $ids);
        $variants = $this->getAssortment(implode(';', $filter), $fn);
        foreach ($variants as $k => $variant) {
            $characteristics = [];
            if (isset($variant['characteristics'])) {
                foreach ($variant['characteristics'] as $character) {
                    $characteristics[$character['name']] = $character['value'];
                }
            }
            $variants[$k]['mods'] = $characteristics;
        }
        return $variants;
    }

    /**
     * @param $productId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProductVariants($productId, &$absent = [])
    {
        $fn = Helpers::getLastUrlPart($productId) . '.json';
        $filter = "productid=$productId";
        $stock = $this->getStockCurrent();
        $variants = $this->execute('entity/variant', $filter, $fn);
        $result = array_map(function($variant) use ($stock){
            $variant['stock'] = isset($stock[$variant['id']]) ? $stock[$variant['id']] : 0;
            return $variant;
        }, $variants);
        $exists = array_filter($result, function($row) {
          return $row['stock'] > 0;
        });
        $absent = array_filter($result, function($row) {
          return $row['stock'] <= 0;
        });
        return $exists;
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getStockCurrent()
    {
        $stocks = $this->execute('report/stock/all/current?stockType=quantity', '', 'stockCurrent.json');
        $result = [];
        foreach ($stocks as $stock){
            $stock = (array) $stock;
            $result[$stock['assortmentId']] = $stock['quantity'];
        }
        return $result;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProductfolder($filter = [])
    {
        return $this->execute('entity/productfolder', $filter);
    }
}