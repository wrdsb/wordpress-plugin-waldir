<?php
namespace WALDIR\WP;
use WALDIR\WP\WPCore as WPCore;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/wrdsb
 * @since      1.0.0
 *
 * @package    WRDSB_Staff
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WRDSB_Staff
 * @author     WRDSB <website@wrdsb.ca>
 */
class WPRemotePost {
    public static $successCodes = array(
        200,
        202
    );
    
    public static $defaultHeaders = array(
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json'
    );

    public function __construct($params) {
        $this->timeout     = $params['timeout']     ?? 45;
        $this->redirection = $params['redirection'] ?? 5;
        $this->httpversion = $params['httpversion'] ?? '1.0';
        $this->useragent   = $params['useragent']   ?? "WordPress";
        $this->blocking    = $params['blocking']    ?? true;
        $this->cookies     = $params['cookies']     ?? array();
        $this->compress    = $params['compress']    ?? false;
        $this->decompress  = $params['decompress']  ?? true;
        $this->sslverify   = $params['sslverify']   ?? false;
        $this->stream      = $params['stream']      ?? false;
        $this->filename    = $params['filename']    ?? null;
    
        $this->url     = $params['url'];
        $this->headers = array_merge(self::$defaultHeaders, $params['headers']);
        $this->body    = $params['body'] ?? null;
    }

    public function run() {
        $args = array(
            'timeout'     => $this->timeout,
            'redirection' => $this->redirection,
            'httpversion' => $this->httpversion,
            'user-agent'  => $this->useragent,
            'blocking'    => $this->blocking,
            'cookies'     => $this->cookies,
            'compress'    => $this->compress,
            'decompress'  => $this->decompress,
            'sslverify'   => $this->sslverify,
            'stream'      => $this->stream,
            'filename'    => $this->filename,
            'headers'     => $this->headers,
            'body'        => $this->body
        );

        $retries = 0;
        $maxRetries = 5;
        
        while ($retries < $maxRetries) {
            $backoff = 5 * $retries;
            $args['timeout'] = $args['timeout'] + $backoff;
            $response  = WPCore::wpRemotePost($this->url, $args);
        
            if (is_array($response) && !empty($response) && in_array($response["response"]["code"], self::$successCodes)) {
                break;
            }
            $retries++;
        }
        
        if (WPCore::isWPError($response)) {
            $this->success = false;
            $this->status = 500;
            $this->response = $response;
            $this->error = $response->get_error_message();
        } elseif (!empty($response) && in_array($response["response"]["code"], self::$successCodes)) {
            $this->success = true;
            $this->status = $response["response"]["code"];
            $this->response = json_decode($response['body'], $assoc = false);
            $this->error = null;
        } else {
            $this->success = false;
            $this->status = $response["response"]["code"];
            $this->response = json_decode($response['body'], $assoc = false);
            $this->error = 'Unknown error.';
        }
    }
}
