<?php
namespace html2json\service;

class htmlToJson
{
    /**
     * @param string $inner_html
     * @return array
     */
    public static function parseInnerHtmlToJson(string $inner_html)
    {
        $dom = new \DOMDocument();

        $dom->loadHTML(mb_convert_encoding($inner_html, 'HTML-ENTITIES', 'UTF-8'));

        $json = self::elementToObj($dom->documentElement);

        return $json['children'][0]['children'] ?? [];
    }

    /**
     * innerHtml to json
     * @param $element
     * @return array
     */
    public static function elementToObj($element) {
        $obj = array(
            "type" => 'element',
            "tagName" => $element->tagName,
            'attributes' => [],
            'children' => [],
        );
        foreach ($element->attributes as $attribute) {

            if($attribute->name == 'style')
            {
                $obj['attributes'][] = [
                    'key' => $attribute->name,
                    'value' => $attribute->value,
                ];
            }
        }
        foreach ($element->childNodes as $subElement) {
            if ($subElement->nodeType == XML_TEXT_NODE) {
                $obj['children'][] = [
                    'type' => 'text',
                    'content' => $subElement->wholeText,
                ];
            }
            else {
                $obj["children"][] = self::elementToObj($subElement);
            }
        }
        return $obj;
    }

    /**
     * replace content
     * @param array $json
     * @param array $contents
     * @return string
     */
    public static function replaceHtmlContent(array $json,array &$contents)
    {
        $html = '';
        foreach ($json as $each_json)
        {
            if(isset($each_json['type']) && $each_json['type'] == 'element')
            {
                $html .= '<'.$each_json['tagName'];

                $attributes = $each_json['attributes'] ?? [];
                if($attributes)
                {
                    foreach ($attributes as $attribute)
                    {
                        if($attribute['key'])
                        {
                            $html .= ' '.$attribute['key'].'="'.$attribute['value'].'"';
                        }
                    }
                }

                $html .= '>';

                $children = $each_json['children'] ?? [];
                if($children)
                {
                    $html .= self::replaceHtmlContent($children,$contents);
                }

                $html .= '</'.$each_json['tagName'].'>';
            }
            elseif($each_json['type'] == 'text')
            {
                //$html .= $each_json['content'];
                if($contents)
                {
                    $content = array_shift($contents);
                    $html .= $content;
                }
                else
                {
                    //nothing
                }
            }
        }
        return $html;
    }
}