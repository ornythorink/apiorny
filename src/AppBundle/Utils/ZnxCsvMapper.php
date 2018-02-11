<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Products;

class ZnxCsvMapper
{

    public function map($data, $site)
    {
        $datetime = new \DateTime('NOW');

        $mapped['status'] = "Validation";
        $mapped['shortDescription'] = $this->existsKey($data, 'ProductShortDescription');
        $mapped['longDescription'] = null;
        $image = "";
        if ($data['ImageLargeURL'] != "" && $data['ImageLargeURL'] !== null) {
            $image = $data['ImageLargeURL'];
        } else if ($data['ImageMediumURL'] != "" && $data['ImageMediumURL'] !== null) {
            $image = $data['ImageMediumURL'];
        } else if ($data['ImageSmallURL'] != "" && $data['ImageSmallURL'] !== null) {
            $image = $data['ImageSmallURL'];
        }

        $mapped['tinyImage'] = $image;
        $mapped['url'] = $this->existsKey($data, 'ZanoxProductLink');
        $mapped['name'] = $this->existsKey($data, 'ProductName');
        $mapped['merchantCategory'] = $this->existsKey($data, 'MerchantProductCategory');
        $mapped['price'] = $this->existsKey($data, 'ProductPrice');
        $mapped['shippingPrice'] = $this->existsKey($data, 'shippingPrice');
        $mapped['currency'] = $this->existsKey($data, 'CurrencySymbolOfPrice');
        $mapped['brand'] = $this->existsKey($data, 'ProductManufacturerBrand');
        $mapped['delay'] = $this->existsKey($data, 'delay');
        $mapped['shipping'] = null;
        $mapped['oldPrice'] = null;
        $mapped['specialPrice'] = null;
        $mapped['store'] = "Inconnu";
        $mapped['source'] = "ZNX";
        $mapped['createdAt'] = $datetime;
        $mapped['lastUpdate'] = $datetime;
        $mapped['program'] = $site->getSitename();
        $mapped['site_id'] = $site->getId();
        $mapped['logostore'] = null;

        $sourceType = 'CSV';
        $produit = Products::bulkCreate($sourceType, $mapped);

        return $produit;
    }

    public function existsKey($data, $label)
    {
        $value = (array_key_exists($label, $data)) ? $data[$label] : null;

        return $value;
    }


} 