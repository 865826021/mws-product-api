<?php namespace RaffW\MwsProductApi;

/*******************************************************************************
 * Copyright 2009-2014 Amazon Services. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 *
 * You may not use this file except in compliance with the License.
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 *******************************************************************************
 * PHP Version 5
 *
 * @category Amazon
 * @package  Marketplace Web Service Products
 * @version  2011-10-01
 *           Library Version: 2014-10-20
 *           Generated: Fri Oct 17 17:59:56 GMT 2014
 */

use RaffW\MwsProductApi\Model;

/**
 * MarketplaceWebServiceProducts_Client is an implementation of MarketplaceWebServiceProducts
 *
 */
class MwsClient implements MwsProductsInterface {

    const SERVICE_VERSION = '2011-10-01';
    const MWS_CLIENT_VERSION = '2014-10-20';

    /** @var string */
    private $_awsAccessKeyId = null;

    /** @var string */
    private $_awsSecretAccessKey = null;

    /** @var array */
    private $_config = [
        'ServiceURL' => null,
        'UserAgent' => 'MarketplaceWebServiceProducts PHP5 Library',
        'SignatureVersion' => 2,
        'SignatureMethod' => 'HmacSHA256',
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'ProxyUsername' => null,
        'ProxyPassword' => null,
        'MaxErrorRetry' => 3,
        'Headers' => []
    ];

    /**
     * Construct new Client
     *
     * @param string $awsAccessKeyId AWS Access Key ID
     * @param string $awsSecretAccessKey AWS Secret Access Key
     * @param string $applicationName application name
     * @param string $applicationVersion application version
     * @param array  $config configuration options.
     * Valid configuration options are: ServiceURL, UserAgent,
     * SignatureVersion, TimesRetryOnError, ProxyHost, ProxyPort,
     * ProxyUsername, ProxyPassword, MaxErrorRetry
     */
    public function __construct($awsAccessKeyId, $awsSecretAccessKey, $applicationName, $applicationVersion, $config = null)
    {
        if (PHP_VERSION_ID < 50600)
        {
            iconv_set_encoding('output_encoding', 'UTF-8');
            iconv_set_encoding('input_encoding', 'UTF-8');
            iconv_set_encoding('internal_encoding', 'UTF-8');
        }
        else
        {
            ini_set('output_encoding', 'UTF-8');
            ini_set('input_encoding', 'UTF-8');
            ini_set('internal_encoding', 'UTF-8');
        }

        $this->_awsAccessKeyId = $awsAccessKeyId;
        $this->_awsSecretAccessKey = $awsSecretAccessKey;

        if ( ! is_null($config))
        {
            $this->_config = array_merge($this->_config, $config);
        }

        $this->setUserAgentHeader($applicationName, $applicationVersion);
    }

    private function setUserAgentHeader(
        $applicationName,
        $applicationVersion,
        $attributes = null)
    {

        if (is_null($attributes))
        {
            $attributes = [];
        }

        $this->_config['UserAgent'] =
            $this->constructUserAgentHeader($applicationName, $applicationVersion, $attributes);
    }

    private function constructUserAgentHeader($applicationName, $applicationVersion, $attributes = null)
    {
        if (is_null($applicationName) || $applicationName === "")
        {
            throw new \InvalidArgumentException('$applicationName cannot be null');
        }

        if (is_null($applicationVersion) || $applicationVersion === "")
        {
            throw new \InvalidArgumentException('$applicationVersion cannot be null');
        }

        $userAgent = $this->quoteApplicationName($applicationName) . '/' .
            $this->quoteApplicationVersion($applicationVersion) . ' (Language=PHP/' .
            phpversion() . '; Platform=' . php_uname('s') . '/' . php_uname('m') . '/' .
            php_uname('r') . '; MWSClientVersion=' . self::MWS_CLIENT_VERSION;

        foreach ($attributes as $key => $value)
        {
            if (empty($value))
            {
                throw new \InvalidArgumentException("Value for $key cannot be null or empty.");
            }

            $userAgent .= '; ' . $this->quoteAttributeName($key) . '=' . $this->quoteAttributeValue($value);
        }

        $userAgent .= ')';

        return $userAgent;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '/' characters from a string.
     *
     * @param $s
     *
     * @return string
     */
    private function quoteApplicationName($s)
    {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\//', '\\/', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' character.
     *
     * @param $s
     *
     * @return string
     */
    private function collapseWhitespace($s)
    {
        return preg_replace('/ {2,}|\s/', ' ', $s);
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '(' characters from a string.
     *
     * @param $s
     *
     * @return string
     */
    private function quoteApplicationVersion($s)
    {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\(/', '\\(', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '=' characters from a string.
     *
     * @param $s
     *
     * @return string
     */
    private function quoteAttributeName($s)
    {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\=/', '\\=', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape ';', '\',
     * and ')' characters from a string.
     *
     * @param $s
     *
     * @return string
     */
    private function quoteAttributeValue($s)
    {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\;/', '\\;', $quotedString);
        $quotedString = preg_replace('/\\)/', '\\)', $quotedString);

        return $quotedString;
    }

    /**
     * Get Competitive Pricing For ASIN
     * Gets competitive pricing and related information for a product identified by
     * the MarketplaceId and ASIN.
     *
     * @param mixed $request array of parameters for GetCompetitivePricingForASIN request or GetCompetitivePricingForASIN object itself
     *
     * @see GetCompetitivePricingForASINRequest
     * @return Model\GetCompetitivePricingForASINResponse
     *
     * @throws \Exception
     */
    public function getCompetitivePricingForASIN($request)
    {
        if ( ! ($request instanceof Model\GetCompetitivePricingForASINRequest))
        {
            $request = new Model\GetCompetitivePricingForASINRequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetCompetitivePricingForASIN';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetCompetitivePricingForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get Competitive Pricing For SKU
     * Gets competitive pricing and related information for a product identified by
     * the SellerId and SKU.
     *
     * @param mixed $request array of parameters for GetCompetitivePricingForSKU request or GetCompetitivePricingForSKU object itself
     *
     * @see GetCompetitivePricingForSKURequest
     * @return Model\GetCompetitivePricingForSKUResponse
     *
     * @throws MwsProductsException
     */
    public function getCompetitivePricingForSKU($request)
    {
        if ( ! ($request instanceof Model\GetCompetitivePricingForSKURequest))
        {
            $request = new Model\GetCompetitivePricingForSKURequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetCompetitivePricingForSKU';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetCompetitivePricingForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get Lowest Offer Listings For ASIN
     * Gets some of the lowest prices based on the product identified by the given
     * MarketplaceId and ASIN.
     *
     * @param mixed $request array of parameters for GetLowestOfferListingsForASIN request or GetLowestOfferListingsForASIN object itself
     *
     * @see GetLowestOfferListingsForASINRequest
     * @return Model\GetLowestOfferListingsForASINResponse
     *
     * @throws MwsProductsException
     */
    public function getLowestOfferListingsForASIN($request)
    {
        if ( ! ($request instanceof Model\GetLowestOfferListingsForASINRequest))
        {
            $request = new Model\GetLowestOfferListingsForASINRequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetLowestOfferListingsForASIN';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetLowestOfferListingsForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get Lowest Offer Listings For SKU
     * Gets some of the lowest prices based on the product identified by the given
     * SellerId and SKU.
     *
     * @param mixed $request array of parameters for GetLowestOfferListingsForSKU request or GetLowestOfferListingsForSKU object itself
     *
     * @see GetLowestOfferListingsForSKURequest
     * @return Model\GetLowestOfferListingsForSKUResponse
     *
     * @throws MwsProductsException
     */
    public function getLowestOfferListingsForSKU($request)
    {
        if ( ! ($request instanceof Model\GetLowestOfferListingsForSKURequest))
        {
            $request = new Model\GetLowestOfferListingsForSKURequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetLowestOfferListingsForSKU';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetLowestOfferListingsForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get Matching Product
     * GetMatchingProduct will return the details (attributes) for the
     * given ASIN.
     *
     * @param mixed $request array of parameters for GetMatchingProduct request or GetMatchingProduct object itself
     *
     * @see GetMatchingProductRequest
     * @return Model\GetMatchingProductResponse
     *
     * @throws MwsProductsException
     */
    public function getMatchingProduct($request)
    {
        if ( ! ($request instanceof Model\GetMatchingProductRequest))
        {
            $request = new Model\GetMatchingProductRequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetMatchingProduct';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetMatchingProductResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get Matching Product For Id
     * GetMatchingProduct will return the details (attributes) for the
     * given Identifier list. Identifer type can be one of [SKU|ASIN|UPC|EAN|ISBN|GTIN|JAN]
     *
     * @param mixed $request array of parameters for GetMatchingProductForId request or GetMatchingProductForId object itself
     *
     * @see GetMatchingProductForIdRequest
     * @return Model\GetMatchingProductForIdResponse
     *
     * @throws MwsProductsException
     */
    public function getMatchingProductForId($request)
    {
        if ( ! ($request instanceof Model\GetMatchingProductForIdRequest))
        {
            $request = new Model\GetMatchingProductForIdRequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetMatchingProductForId';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetMatchingProductForIdResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get My Price For ASIN
     * <!-- Wrong doc in current code -->
     *
     * @param mixed $request array of parameters for GetMyPriceForASIN request or GetMyPriceForASIN object itself
     *
     * @see GetMyPriceForASINRequest
     * @return Model\GetMyPriceForASINResponse
     *
     * @throws MwsProductsException
     */
    public function getMyPriceForASIN($request)
    {
        if ( ! ($request instanceof Model\GetMyPriceForASINRequest))
        {
            $request = new Model\GetMyPriceForASINRequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetMyPriceForASIN';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetMyPriceForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get My Price For SKU
     * <!-- Wrong doc in current code -->
     *
     * @param mixed $request array of parameters for GetMyPriceForSKU request or GetMyPriceForSKU object itself
     *
     * @see GetMyPriceForSKURequest
     * @return Model\GetMyPriceForSKUResponse
     *
     * @throws MwsProductsException
     */
    public function getMyPriceForSKU($request)
    {
        if ( ! ($request instanceof Model\GetMyPriceForSKURequest))
        {
            $request = new Model\GetMyPriceForSKURequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetMyPriceForSKU';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetMyPriceForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get Product Categories For ASIN
     * Gets categories information for a product identified by
     * the MarketplaceId and ASIN.
     *
     * @param mixed $request array of parameters for GetProductCategoriesForASIN request or GetProductCategoriesForASIN object itself
     *
     * @see GetProductCategoriesForASINRequest
     * @return Model\GetProductCategoriesForASINResponse
     *
     * @throws MwsProductsException
     */
    public function getProductCategoriesForASIN($request)
    {
        if ( ! ($request instanceof Model\GetProductCategoriesForASINRequest))
        {
            $request = new Model\GetProductCategoriesForASINRequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetProductCategoriesForASIN';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetProductCategoriesForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get Product Categories For SKU
     * Gets categories information for a product identified by
     * the SellerId and SKU.
     *
     * @param mixed $request array of parameters for GetProductCategoriesForSKU request or GetProductCategoriesForSKU object itself
     *
     * @see GetProductCategoriesForSKURequest
     * @return Model\GetProductCategoriesForSKUResponse
     *
     * @throws MwsProductsException
     */
    public function getProductCategoriesForSKU($request)
    {
        if ( ! ($request instanceof Model\GetProductCategoriesForSKURequest))
        {
            $request = new Model\GetProductCategoriesForSKURequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetProductCategoriesForSKU';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetProductCategoriesForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Get Service Status
     * Returns the service status of a particular MWS API section. The operation
     * takes no input.
     * All API sections within the API are required to implement this operation.
     *
     * @param mixed $request array of parameters for GetServiceStatus request or GetServiceStatus object itself
     *
     * @see GetServiceStatusRequest
     * @return Model\GetServiceStatusResponse
     *
     * @throws MwsProductsException
     */
    public function getServiceStatus($request)
    {
        if ( ! ($request instanceof Model\GetServiceStatusRequest))
        {
            $request = new Model\GetServiceStatusRequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetServiceStatus';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\GetServiceStatusResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * List Matching Products
     * ListMatchingProducts can be used to
     * find products that match the given criteria.
     *
     * @param mixed $request array of parameters for ListMatchingProducts request or ListMatchingProducts object itself
     *
     * @see ListMatchingProductsRequest
     * @return Model\ListMatchingProductsResponse
     *
     * @throws MwsProductsException
     */
    public function listMatchingProducts($request)
    {
        if ( ! ($request instanceof Model\ListMatchingProductsRequest))
        {
            $request = new Model\ListMatchingProductsRequest($request);
        }

        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListMatchingProducts';
        $httpResponse = $this->_invoke($parameters);

        $response = Model\ListMatchingProductsResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);

        return $response;
    }

    /**
     * Invoke request and return response
     */
    private function _invoke(array $parameters)
    {
        try
        {
            if (empty($this->_config['ServiceURL']))
            {
                throw new MwsProductsException([
                        'ErrorCode' => 'InvalidServiceURL',
                        'Message' => "Missing serviceUrl configuration value. " .
                            "You may obtain a list of valid MWS URLs by consulting " .
                            "the MWS Developer's Guide, or reviewing the sample " .
                            "code published along side this library."
                    ]);
            }

            $parameters = $this->_addRequiredParameters($parameters);
            $retries = 0;

            for (; ;)
            {
                $response = $this->_httpPost($parameters);
                $status = $response['Status'];

                if ($status == 200)
                {
                    return [
                        'ResponseBody' => $response['ResponseBody'],
                        'ResponseHeaderMetadata' => $response['ResponseHeaderMetadata']
                    ];
                }

                if ($status == 500 && $this->_pauseOnRetry(++$retries))
                {
                    continue;
                }

                throw $this->_reportAnyErrors($response['ResponseBody'],
                    $status, $response['ResponseHeaderMetadata']);
            }
        }

        catch (MwsProductsException $se)
        {
            throw $se;
        }

        catch (\Exception $t)
        {
            throw new MwsProductsException([
                'Exception' => $t,
                'Message' => $t->getMessage()
            ]);
        }
    }

    /**
     * Add authentication related and version parameters
     */
    private function _addRequiredParameters(array $parameters)
    {
        $parameters['AWSAccessKeyId'] = $this->_awsAccessKeyId;
        $parameters['Timestamp'] = $this->_getFormattedTimestamp();
        $parameters['Version'] = self::SERVICE_VERSION;
        $parameters['SignatureVersion'] = $this->_config['SignatureVersion'];

        if ($parameters['SignatureVersion'] > 1)
        {
            $parameters['SignatureMethod'] = $this->_config['SignatureMethod'];
        }

        $parameters['Signature'] = $this->_signParameters($parameters, $this->_awsSecretAccessKey);

        return $parameters;
    }

    /**
     * Formats date as ISO 8601 timestamp
     */
    private function _getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }

    /**
     * Computes RFC 2104-compliant HMAC signature for request parameters
     * Implements AWS Signature, as per following spec:
     *
     * If Signature Version is 0, it signs concatenated Action and Timestamp
     *
     * If Signature Version is 1, it performs the following:
     *
     * Sorts all  parameters (including SignatureVersion and excluding Signature,
     * the value of which is being created), ignoring case.
     *
     * Iterate over the sorted list and append the parameter name (in original case)
     * and then its value. It will not URL-encode the parameter values before
     * constructing this string. There are no separators.
     *
     * If Signature Version is 2, string to sign is based on following:
     *
     *    1. The HTTP Request Method followed by an ASCII newline (%0A)
     *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
     *    3. The URL encoded HTTP absolute path component of the URI
     *       (up to but not including the query string parameters);
     *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
     *    4. The concatenation of all query string components (names and values)
     *       as UTF-8 characters which are URL encoded as per RFC 3986
     *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
     *       Parameter names are separated from their values by the '=' character
     *       (ASCII character 61), even if the value is empty.
     *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
     *
     */
    private function _signParameters(array $parameters, $key)
    {
        $signatureVersion = $parameters['SignatureVersion'];
        $algorithm = "HmacSHA1";
        $stringToSign = null;

        if (2 == $signatureVersion)
        {
            $algorithm = $this->_config['SignatureMethod'];
            $parameters['SignatureMethod'] = $algorithm;
            $stringToSign = $this->_calculateStringToSignV2($parameters);
        }
        else
        {
            throw new \Exception("Invalid Signature Version specified");
        }

        return $this->_sign($stringToSign, $key, $algorithm);
    }

    /**
     * Calculate String to Sign for SignatureVersion 2
     *
     * @param array $parameters request parameters
     *
     * @return String to Sign
     */
    private function _calculateStringToSignV2(array $parameters)
    {
        $data = 'POST';
        $data .= "\n";
        $endpoint = parse_url($this->_config['ServiceURL']);
        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;

        if ( ! isset ($uri))
        {
            $uri = "/";
        }

        $uriencoded = implode("/", array_map([
            $this,
            "_urlencode"
        ], explode("/", $uri)));

        $data .= $uriencoded;
        $data .= "\n";

        uksort($parameters, 'strcmp');

        $data .= $this->_getParametersAsString($parameters);

        return $data;
    }

    /**
     * Convert paremeters to Url encoded query string
     */
    private function _getParametersAsString(array $parameters)
    {
        $queryParameters = [];

        foreach ($parameters as $key => $value)
        {
            $queryParameters[] = $key . '=' . $this->_urlencode($value);
        }

        return implode('&', $queryParameters);
    }

    private function _urlencode($value)
    {
        return str_replace('%7E', '~', rawurlencode($value));
    }

    /**
     * Computes RFC 2104-compliant HMAC signature.
     */
    private function _sign($data, $key, $algorithm)
    {
        if ($algorithm === 'HmacSHA1')
        {
            $hash = 'sha1';
        }
        else if ($algorithm === 'HmacSHA256')
        {
            $hash = 'sha256';
        }
        else
        {
            throw new \Exception ("Non-supported signing method specified");
        }

        return base64_encode(
            hash_hmac($hash, $data, $key, true)
        );
    }

    /**
     * Perform HTTP post with exponential retries on error 500 and 503
     *
     */
    private function _httpPost(array $parameters)
    {
        $config = $this->_config;
        $query = $this->_getParametersAsString($parameters);
        $url = parse_url($config['ServiceURL']);
        $uri = array_key_exists('path', $url) ? $url['path'] : null;

        if ( ! isset ($uri))
        {
            $uri = "/";
        }

        switch ($url['scheme'])
        {
            case 'https':
                $scheme = 'https://';
                $port = isset($url['port']) ? $url['port'] : 443;
                break;

            default:
                $scheme = 'http://';
                $port = isset($url['port']) ? $url['port'] : 80;
        }

        $allHeaders = $config['Headers'];
        $allHeaders['Content-Type'] = "application/x-www-form-urlencoded; charset=utf-8";
        // We need to make sure to set utf-8 encoding here
        $allHeaders['Expect'] = null; // Don't expect 100 Continue
        $allHeadersStr = [];

        foreach ($allHeaders as $name => $val)
        {
            $str = $name . ": ";

            if (isset($val))
            {
                $str = $str . $val;
            }

            $allHeadersStr[] = $str;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $scheme . $url['host'] . $uri);
        curl_setopt($ch, CURLOPT_PORT, $port);

        $this->setSSLCurlOptions($ch);

        curl_setopt($ch, CURLOPT_USERAGENT, $this->_config['UserAgent']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeadersStr);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($config['ProxyHost'] != null && $config['ProxyPort'] != -1)
        {
            curl_setopt($ch, CURLOPT_PROXY, $config['ProxyHost'] . ':' . $config['ProxyPort']);
        }

        if ($config['ProxyUsername'] != null && $config['ProxyPassword'] != null)
        {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $config['ProxyUsername'] . ':' . $config['ProxyPassword']);
        }

        $response = "";
        $response = curl_exec($ch);

        if ($response === false)
        {
            $exProps["Message"] = curl_error($ch);
            $exProps["ErrorType"] = "HTTP";

            curl_close($ch);

            throw new MwsProductsException($exProps);
        }

        curl_close($ch);

        return $this->_extractHeadersAndBody($response);
    }

    /**
     * Set curl options relating to SSL. Protected to allow overriding.
     *
     * @param $ch curl handle
     */
    protected function setSSLCurlOptions($ch)
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }

    /**
     * This method will attempt to extract the headers and body of our response.
     * We need to split the raw response string by 2 'CRLF's.  2 'CRLF's should indicate the separation of the response header
     * from the response body.  However in our case we have some circumstances (certain client proxies) that result in
     * multiple responses concatenated.  We could encounter a response like
     *
     * HTTP/1.1 100 Continue
     *
     * HTTP/1.1 200 OK
     * Date: Tue, 01 Apr 2014 13:02:51 GMT
     * Content-Type: text/html; charset=iso-8859-1
     * Content-Length: 12605
     *
     * ... body ..
     *
     * This method will throw away extra response status lines and attempt to find the first full response headers and body
     *
     * return [status, body, ResponseHeaderMetadata]
     */
    private function _extractHeadersAndBody($response)
    {
        //First split by 2 'CRLF'
        $responseComponents = preg_split("/(?:\r?\n){2}/", $response);
        $body = null;

        for ($count = 0;
            $count < count($responseComponents) && $body == null;
            $count++)
        {
            $headers = $responseComponents[$count];
            $responseStatus = $this->_extractHttpStatusCode($headers);

            if ($responseStatus != null && $this->_httpHeadersHaveContent($headers))
            {
                $responseHeaderMetadata = $this->_extractResponseHeaderMetadata($headers);
                //The body will be the next item in the responseComponents array
                $body = $responseComponents[++$count];
            }
        }

        //If the body is null here then we were unable to parse the response and will throw an exception
        if ($body == null)
        {
            $exProps["Message"] = "Failed to parse valid HTTP response (" . $response . ")";
            $exProps["ErrorType"] = "HTTP";

            throw new MwsProductsException($exProps);
        }

        return [
            'Status' => $responseStatus,
            'ResponseBody' => $body,
            'ResponseHeaderMetadata' => $responseHeaderMetadata
        ];
    }

    /**
     * parse the status line of a header string for the proper format and
     * return the status code
     *
     * Example: HTTP/1.1 200 OK
     * ...
     * returns String statusCode or null if the status line can't be parsed
     */
    private function _extractHttpStatusCode($headers)
    {
        $statusCode = null;

        if (1 === preg_match("/(\\S+) +(\\d+) +([^\n\r]+)(?:\r?\n|\r)/", $headers, $matches))
        {
            //The matches array [entireMatchString, protocol, statusCode, the rest]
            $statusCode = $matches[2];
        }

        return $statusCode;
    }


    // Private API ------------------------------------------------------------//

    /**
     * Tries to determine some valid headers indicating this response
     * has content.  In this case
     * return true if there is a valid "Content-Length" or "Transfer-Encoding" header
     */
    private function _httpHeadersHaveContent($headers)
    {
        return (1 === preg_match("/[cC]ontent-[lL]ength: +(?:\\d+)(?:\\r?\\n|\\r|$)/", $headers) ||
            1 === preg_match("/Transfer-Encoding: +(?!identity[\r\n;= ])(?:[^\r\n]+)(?:\r?\n|\r|$)/i", $headers));
    }

    /**
     *  extract a ResponseHeaderMetadata object from the raw headers
     */
    private function _extractResponseHeaderMetadata($rawHeaders)
    {
        $inputHeaders = preg_split("/\r\n|\n|\r/", $rawHeaders);

        $headers = [];
        $headers['x-mws-request-id'] = null;
        $headers['x-mws-response-context'] = null;
        $headers['x-mws-timestamp'] = null;
        $headers['x-mws-quota-max'] = null;
        $headers['x-mws-quota-remaining'] = null;
        $headers['x-mws-quota-resetsOn'] = null;

        foreach ($inputHeaders as $currentHeader)
        {
            $keyValue = explode(': ', $currentHeader);

            if (isset($keyValue[1]))
            {
                list ($key, $value) = $keyValue;

                if (isset($headers[$key]) && $headers[$key] !== null)
                {
                    $headers[$key] = $headers[$key] . "," . $value;
                }
                else
                {
                    $headers[$key] = $value;
                }
            }
        }

        return new Model\ResponseHeaderMetadata(
            $headers['x-mws-request-id'],
            $headers['x-mws-response-context'],
            $headers['x-mws-timestamp'],
            $headers['x-mws-quota-max'],
            $headers['x-mws-quota-remaining'],
            $headers['x-mws-quota-resetsOn']
        );
    }

    /**
     * Exponential sleep on failed request
     *
     * @param retries current retry
     */
    private function _pauseOnRetry($retries)
    {
        if ($retries <= $this->_config['MaxErrorRetry'])
        {
            $delay = (int)(pow(4, $retries) * 100000);
            usleep($delay);

            return true;
        }

        return false;
    }

    /**
     * Look for additional error strings in the response and return formatted exception
     */
    private function _reportAnyErrors($responseBody, $status, $responseHeaderMetadata, \Exception $e = null)
    {
        $exProps = [];
        $exProps["StatusCode"] = $status;
        $exProps["ResponseHeaderMetadata"] = $responseHeaderMetadata;

        libxml_use_internal_errors(true);  // Silence XML parsing errors
        $xmlBody = simplexml_load_string($responseBody);

        if ($xmlBody !== false)
        {  // Check XML loaded without errors
            $exProps["XML"] = $responseBody;
            $exProps["ErrorCode"] = $xmlBody->Error->Code;
            $exProps["Message"] = $xmlBody->Error->Message;
            $exProps["ErrorType"] = ! empty($xmlBody->Error->Type) ? $xmlBody->Error->Type : "Unknown";
            $exProps["RequestId"] = ! empty($xmlBody->RequestID) ? $xmlBody->RequestID : $xmlBody->RequestId; // 'd' in RequestId is sometimes capitalized
        }
        else
        { // We got bad XML in response, just throw a generic exception
            $exProps["Message"] = "Internal Error";
        }

        return new MwsProductsException($exProps);
    }

    /**
     * Convert GetCompetitivePricingForASINRequest to name value pairs
     */
    private function _convertGetCompetitivePricingForASIN($request)
    {
        $parameters = [];
        $parameters['Action'] = 'GetCompetitivePricingForASIN';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetASINList())
        {
            $ASINListGetCompetitivePricingForASINRequest = $request->getASINList();

            foreach ($ASINListGetCompetitivePricingForASINRequest->getASIN() as $ASINASINListIndex => $ASINASINList)
            {
                $parameters['ASINList' . '.' . 'ASIN' . '.' . ($ASINASINListIndex + 1)] = $ASINASINList;
            }
        }

        return $parameters;
    }

    /**
     * Convert GetCompetitivePricingForSKURequest to name value pairs
     */
    private function _convertGetCompetitivePricingForSKU($request)
    {
        $parameters = [];
        $parameters['Action'] = 'GetCompetitivePricingForSKU';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetSellerSKUList())
        {
            $SellerSKUListGetCompetitivePricingForSKURequest = $request->getSellerSKUList();
            foreach ($SellerSKUListGetCompetitivePricingForSKURequest->getSellerSKU() as
                $SellerSKUSellerSKUListIndex => $SellerSKUSellerSKUList)
            {
                $parameters['SellerSKUList' . '.' . 'SellerSKU' . '.' .
                ($SellerSKUSellerSKUListIndex + 1)] = $SellerSKUSellerSKUList;
            }
        }

        return $parameters;
    }

    /**
     * Convert GetLowestOfferListingsForASINRequest to name value pairs
     */
    private function _convertGetLowestOfferListingsForASIN($request)
    {
        $parameters = [];
        $parameters['Action'] = 'GetLowestOfferListingsForASIN';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetASINList())
        {
            $ASINListGetLowestOfferListingsForASINRequest = $request->getASINList();

            foreach ($ASINListGetLowestOfferListingsForASINRequest->getASIN() as $ASINASINListIndex => $ASINASINList)
            {
                $parameters['ASINList' . '.' . 'ASIN' . '.' . ($ASINASINListIndex + 1)] = $ASINASINList;
            }
        }

        if ($request->isSetItemCondition())
        {
            $parameters['ItemCondition'] = $request->getItemCondition();
        }

        if ($request->isSetExcludeMe())
        {
            $parameters['ExcludeMe'] = $request->getExcludeMe() ? "true" : "false";
        }

        return $parameters;
    }

    /**
     * Convert GetLowestOfferListingsForSKURequest to name value pairs
     */
    private function _convertGetLowestOfferListingsForSKU($request)
    {

        $parameters = [];
        $parameters['Action'] = 'GetLowestOfferListingsForSKU';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetSellerSKUList())
        {
            $SellerSKUListGetLowestOfferListingsForSKURequest = $request->getSellerSKUList();

            foreach ($SellerSKUListGetLowestOfferListingsForSKURequest->getSellerSKU() as
                $SellerSKUSellerSKUListIndex => $SellerSKUSellerSKUList)
            {
                $parameters['SellerSKUList' . '.' . 'SellerSKU' . '.' .
                ($SellerSKUSellerSKUListIndex + 1)] = $SellerSKUSellerSKUList;
            }
        }

        if ($request->isSetItemCondition())
        {
            $parameters['ItemCondition'] = $request->getItemCondition();
        }

        if ($request->isSetExcludeMe())
        {
            $parameters['ExcludeMe'] = $request->getExcludeMe() ? "true" : "false";
        }

        return $parameters;
    }

    /**
     * Convert GetMatchingProductRequest to name value pairs
     */
    private function _convertGetMatchingProduct($request)
    {

        $parameters = [];
        $parameters['Action'] = 'GetMatchingProduct';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetASINList())
        {
            $ASINListGetMatchingProductRequest = $request->getASINList();

            foreach ($ASINListGetMatchingProductRequest->getASIN() as
                $ASINASINListIndex => $ASINASINList)
            {
                $parameters['ASINList' . '.' . 'ASIN' . '.' .
                ($ASINASINListIndex + 1)] = $ASINASINList;
            }
        }

        return $parameters;
    }

    /**
     * Convert GetMatchingProductForIdRequest to name value pairs
     */
    private function _convertGetMatchingProductForId($request)
    {
        $parameters = [];
        $parameters['Action'] = 'GetMatchingProductForId';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetIdType())
        {
            $parameters['IdType'] = $request->getIdType();
        }

        if ($request->isSetIdList())
        {
            $IdListGetMatchingProductForIdRequest = $request->getIdList();

            foreach ($IdListGetMatchingProductForIdRequest->getId() as $IdIdListIndex => $IdIdList)
            {
                $parameters['IdList' . '.' . 'Id' . '.' . ($IdIdListIndex + 1)] = $IdIdList;
            }
        }

        return $parameters;
    }

    /**
     * Convert GetMyPriceForASINRequest to name value pairs
     */
    private function _convertGetMyPriceForASIN($request)
    {
        $parameters = [];
        $parameters['Action'] = 'GetMyPriceForASIN';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetASINList())
        {
            $ASINListGetMyPriceForASINRequest = $request->getASINList();

            foreach ($ASINListGetMyPriceForASINRequest->getASIN() as $ASINASINListIndex => $ASINASINList)
            {
                $parameters['ASINList' . '.' . 'ASIN' . '.' . ($ASINASINListIndex + 1)] = $ASINASINList;
            }
        }

        return $parameters;
    }

    /**
     * Convert GetMyPriceForSKURequest to name value pairs
     */
    private function _convertGetMyPriceForSKU($request)
    {
        $parameters = [];
        $parameters['Action'] = 'GetMyPriceForSKU';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetSellerSKUList())
        {
            $SellerSKUListGetMyPriceForSKURequest = $request->getSellerSKUList();

            foreach ($SellerSKUListGetMyPriceForSKURequest->getSellerSKU() as
                $SellerSKUSellerSKUListIndex => $SellerSKUSellerSKUList)
            {
                $parameters['SellerSKUList' . '.' . 'SellerSKU' . '.' .
                ($SellerSKUSellerSKUListIndex + 1)] = $SellerSKUSellerSKUList;
            }
        }

        return $parameters;
    }

    /**
     * Convert GetProductCategoriesForASINRequest to name value pairs
     */
    private function _convertGetProductCategoriesForASIN($request)
    {
        $parameters = [];
        $parameters['Action'] = 'GetProductCategoriesForASIN';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetASIN())
        {
            $parameters['ASIN'] = $request->getASIN();
        }

        return $parameters;
    }

    /**
     * Convert GetProductCategoriesForSKURequest to name value pairs
     */
    private function _convertGetProductCategoriesForSKU($request)
    {
        $parameters = [];
        $parameters['Action'] = 'GetProductCategoriesForSKU';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetSellerSKU())
        {
            $parameters['SellerSKU'] = $request->getSellerSKU();
        }

        return $parameters;
    }

    /**
     * Convert GetServiceStatusRequest to name value pairs
     */
    private function _convertGetServiceStatus($request)
    {
        $parameters = [];
        $parameters['Action'] = 'GetServiceStatus';
        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        return $parameters;
    }

    /**
     * Convert ListMatchingProductsRequest to name value pairs
     */
    private function _convertListMatchingProducts($request)
    {
        $parameters = [];
        $parameters['Action'] = 'ListMatchingProducts';

        if ($request->isSetSellerId())
        {
            $parameters['SellerId'] = $request->getSellerId();
        }

        if ($request->isSetMWSAuthToken())
        {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        if ($request->isSetMarketplaceId())
        {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }

        if ($request->isSetQuery())
        {
            $parameters['Query'] = $request->getQuery();
        }

        if ($request->isSetQueryContextId())
        {
            $parameters['QueryContextId'] = $request->getQueryContextId();
        }

        return $parameters;
    }

    /**
     * Formats date as ISO 8601 timestamp
     */
    private function getFormattedTimestamp($dateTime)
    {
        return $dateTime->format(DATE_ISO8601);
    }

}
