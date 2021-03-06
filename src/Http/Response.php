<?php

namespace SmartOysters\SaferMe\Http;

class Response
{
    /**
     * The response code.
     *
     * @var integer
     */
    protected $statusCode;

    /**
     * The response data.
     *
     * @var mixed
     */
    protected $content;

    /**
     * The response headers.
     *
     * @var array
     */
    private $headers;

    /**
     * Response constructor.
     *
     * @param integer $statusCode
     * @param mixed   $content
     * @param array   $headers
     */
    public function __construct($statusCode, $content, $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->headers = $headers;
    }

    /**
     * Check if the request was successful.
     */
    public function isSuccess()
    {
        if (in_array($this->statusCode, [200,201,202,204,206,207,302])) {
            return true;
        }

        return false;
    }

    /**
     * Get the request data.
     */
    public function getData()
    {
        if ($this->isSuccess() && !empty($this->getContent())) {
            return $this->getContent();
        }

        return null;
    }

    /**
     * Get the status code.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Get the content.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get the headers array.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
