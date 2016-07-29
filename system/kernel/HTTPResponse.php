<?php

    namespace FlatOS\System;

    /**
     * HTTPResponse
     */
    class HTTPResponse {

        /**
        * The response's content.
        * @var ResponseContent
        */
        protected $content = '';

        /**
        * The response's length.
        * @var int
        */
        protected $length = 0;

        protected $outputCallback;

        /**
        * Add a HTTP header.
        * @param string $header The header to add.
        */
        public function addHeader($header) {
            header($header);
        }

        /**
        * Remove an HTTP header.
        * @param string $header The header to remove.
        */
        public function removeHeader($header) {
            header_remove($header);
        }

        /**
        * Get the HTTP response code.
        * @return int The HTTP response code.
        */
        public function responseCode() {
            if (!function_exists('http_response_code')) {
                return (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
            } else {
                return http_response_code();
            }
        }

        /**
        * Redirect the user to another URI.
        * @param string $location The destination's URI.
        */
        public function redirect($location) {
            $this->addHeader('Location: '.$location);
            exit;
        }

        /**
        * Send the response.
        */
        public function send() {
            $this->output($this->content);
            exit();
        }

        /**
        * Get the response's content.
        * @return string The response's content.
        */
        public function content() {
            return $this->content;
        }

        /**
        * Set the response's content.
        * @param string $content The response's content.
        */
        public function setContent($content) {
            $this->content = $content;
        }

        /**
        * Output some content.
        * @param  string $out The output.
        */
        public function output($out) {
            $this->length += strlen($out);
            echo $out;
        }

        // Changes compared to the setcookie() function : the last argument is true by default
        /**
        * Set a cookie.
        * @param string $name The cookie's name.
        * @param mixed $value The cookie's content.
        * @param int $expire The expiration's duration.
        * @param string $path The path for which the cookie is defined.
        * @param string $domain The domain for which the cookie is defined.
        * @param bool $secure Defines if the cookie will be secure.
        * @param bool $httpOnly Defines if the cookie will be defined for access with other methods besides HTTP requests (e.g. Javascript).
        */
        public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true) {
            setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
        }

        /**
        * Make this response cacheable.
        */
        public function setCacheable($cacheOffset = null) {
            if ($cacheOffset === null) {
                $cacheOffset = 7 * 24 * 3600; // 7 days
            }

            $this->addHeader('Cache-Control: max-age='.$cacheOffset);
            $this->addHeader('Expires: '.gmdate('D, d M Y H:i:s T', time()+$cacheOffset));
            $this->removeHeader('Pragma'); // Non-standard
        }

        /**
        * Get the response length.
        * @return int The response length, in bytes.
        */
        public function length() {
            return $this->length;
        }

    }