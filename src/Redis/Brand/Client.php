<?php

namespace Brain\Redis\Brand;

use Brain\Models\Brand;
use Brain\Models\Company;
use Brain\Redis\RedisConfig;
use Brain\Redis\RedisConnector;

class Client
{
    const BRAND_KEY = 'Brand';

    /**
     * @param string $domain
     * @return Response
     */
    public static function getByDomain(string $domain): Response
    {
        $connection = (new RedisConnector)->connect(RedisConfig::get());
        $brandKeys = $connection->keys(sprintf('%s:*', self::BRAND_KEY));

        foreach ($brandKeys as $brandKey) {
            $brand = json_decode($connection->get($brandKey), true);

            if ($brand['domain'] === $domain) {
                return self::buildResponse($brand);
            }
        }

        throw new \Exception(sprintf('Brand not found for domain "%s"', $domain));
    }

    /**
     * @param array $brandResponse
     * @return Response
     */
    protected static function buildResponse(array $brandResponse): Response
    {
        $company = new Company();
        $company->setCode($brandResponse['company']['code']);
        $company->setName($brandResponse['company']['name']);

        $brand = new Brand();
        $brand->setCode($brandResponse['code']);
        $brand->setDomain($brandResponse['domain']);
        $brand->setName($brandResponse['name']);
        $brand->setCompany($company);

        $parsedResponse = new Response();
        $parsedResponse->setBrand($brand);
        $parsedResponse->setCompany($company);

        return $parsedResponse;
    }
}