<?php

namespace Jetfuel\Anpay\Traits;

use Sunra\PhpSimple\HtmlDomParser;

trait ResultParser
{
    /**
     * Parse HTML format response to string.
     *
     * @param string $response
     * @return string|null
     */
    public function parseResponse($response)
    {   
        
        $html = HtmlDomParser::str_get_html($response);
        $result = [];
        try {
            if ($html) {
                $form = $html->find('form', 0);
                if (isset($form)) {
                    $result['postUrl'] = $form->action;
                    $inputs = $form->children();
                    foreach ($inputs as $input) {
                        $name = $input->name;
                        $value = $input->value;
                        if ($name && $value) {
                            $result[$name] = $value;
                        }
                    }
                }
            }
        }
        catch (Exception $e) {
            return null;
        }

        return $result;
    }

    /**
     * Parse HTML format forward response to string.
     *
     * @param string $response
     * @return string|null
     */
    public function parseForwardResponse($response)
    {   
        
        $html = HtmlDomParser::str_get_html($response);

        if ($html) {
            $imgSrc = $html->find('img', 0);

            if (isset($imgSrc)) {
                return ltrim($imgSrc->src, '/');
            }
        }

        return null;
    }

    /**
     * Parse JSON format response to array.
     *
     * @param string $response
     * @return array
     */
    public function parseQueryResponse($response)
    {
        return json_decode($response, true);
    }
}
