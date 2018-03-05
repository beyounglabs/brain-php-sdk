<?php

namespace Brain\Redis\Brand;

use Brain\Models\Brand;
use Brain\Models\Company;

class Response
{
    /**
     * @var Brand
     */
    protected $brand;

    /**
     * @var Company
     */
    protected $company;

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }
}