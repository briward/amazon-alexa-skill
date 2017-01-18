<?php

namespace AmazonAlexaSkill\Request;

/**
 * Class RequestValidator
 */
class RequestValidator
{
    /**
     * The raw JSON request.
     *
     * @var string
     */
    private $rawRequest;

    /**
     * The parsed JSON request.
     *
     * @var stdClass
     */
    private $parsedRequest;

    /**
     * An array of validation errors.
     *
     * @var array
     */
    private $errors;

    /**
     * @param $request
     */
    public function __construct($rawRequest)
    {
        $this->rawRequest = $rawRequest;
        $this->parsedRequest = json_decode($rawRequest);
    }

    /**
     * @param string $applicationId
     *
     * @return bool
     */
    public function validate($appId)
    {
        if(!$this->checkTimestampOfRequest()) return false;
        if(!$this->checkSignature()) return false;
        if(!$this->checkAppIdMatches($appId)) return false;
        if(!$this->checkCertification()) return false;
        if(!$this->checkSubjectAltNameIsValid()) return false;
        return true;
    }

    /**
     * @return bool
     */
    public function checkTimestampOfRequest()
    {
        $date = new \DateTime();
        $requestTimestamp = new \DateTime($this->parsedRequest->request->timestamp);
        if ($date->getTimestamp() - $requestTimestamp->getTimestamp() > 60) {
            $this->errors[] = 'Invalid timestamp';
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkSignature()
    {
        if(!$this->checkSignatureHeaderExists()) return false;
        if(!$this->checkSignatureUrlHeaderExists()) return false;
        if(!$this->checkSignatureUrlHostIsValid()) return false;
        if(!$this->checkSignatureUrlPathIsValid()) return false;
        if(!$this->checkSignatureUrlIsHttps()) return false;
        if(!$this->checkSignatureUrlIsCorrectPort()) return false;
        return true;
    }

    /**
     * @return bool
     */
    private function checkSignatureUrlHeaderExists()
    {
        if (!array_key_exists('HTTP_SIGNATURECERTCHAINURL',$_SERVER)) {
            $this->errors[] = 'Signature certificate chain url header not found';
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkSignatureUrlHostIsValid()
    {
        $url = parse_url($_SERVER['HTTP_SIGNATURECERTCHAINURL']);
        if (strcasecmp($url['host'], 's3.amazonaws.com') != 0) {
            $this->errors[] = 'The URL host in the signature header is invalid';
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkSignatureUrlPathIsValid()
    {
        $url = parse_url($_SERVER['HTTP_SIGNATURECERTCHAINURL']);
        if (strpos($url['path'], '/echo.api/') !== 0) {
            $this->errors[] = 'The URL path in the signature header is invalid';
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkSignatureUrlIsHttps()
    {
        $url = parse_url($_SERVER['HTTP_SIGNATURECERTCHAINURL']);
        if (strcasecmp($url['scheme'], 'https') != 0) {
            $this->errors[] = 'The URL in the signature header is not using HTTPS';
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkSignatureUrlIsCorrectPort()
    {
        $url = parse_url($_SERVER['HTTP_SIGNATURECERTCHAINURL']);
        if (array_key_exists('port', $url) && $url['port'] != '443') {
            $this->errors[] = 'The URL in the signature header is not using the correct port';
            return false;
        }
        return true;
    }

    /**
     * @param $applicationId
     *
     * @return bool
     */
    private function checkAppIdMatches($applicationId)
    {
        if( $this->parsedRequest->session->application->applicationId != $applicationId) {
            $this->errors[] = 'Invalid application id';
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkSignatureHeaderExists()
    {
        if (!array_key_exists('HTTP_SIGNATURE',$_SERVER)) {
            $this->errors[] = 'Signature header not found';
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkCertification()
    {
        if(!$this->checkCertificationIsValid()) return false;
        if(!$this->checkCertificateParsesCorrectly()) return false;
        return true;
    }

    /**
     * @return bool
     */
    private function checkCertificationIsValid()
    {
        $pem = file_get_contents($_SERVER['HTTP_SIGNATURECERTCHAINURL']);
        if ( !openssl_verify( $this->rawRequest, base64_decode($_SERVER['HTTP_SIGNATURE']), $pem)) {
            $this->errors[] = 'Certificate verification failed';
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkCertificateParsesCorrectly()
    {
        $pem = file_get_contents($_SERVER['HTTP_SIGNATURECERTCHAINURL']);
        $cert = openssl_x509_parse($pem);
        if (!$cert) {
            $this->errors[] = 'x509 parsing failed';
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkSubjectAltNameIsValid()
    {
        $pem = file_get_contents($_SERVER['HTTP_SIGNATURECERTCHAINURL']);
        $cert = openssl_x509_parse($pem);
        if( strpos($cert['extensions']['subjectAltName'], 'echo-api.amazon.com') === false) {
            $this->errors[] = 'subjectAltName is invalid';
            return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
