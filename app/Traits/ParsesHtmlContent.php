<?php

namespace App\Traits;

trait ParsesHtmlContent
{
    public function parseHtmlContent($content)
    {
        $return = [];

        if ($content == '') {
            return [(object) ['type' => 'p', 'text' => '']];
        }

        $dom = new \DOMDocument();
        $dom->loadHTML('<html><head><meta http-equiv="Content-Type" content="charset=utf-8" /></head><body>'.$content.'</body></html>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        $items = $dom->childNodes->item(0)->childNodes->item(1);

        foreach ($items->childNodes as $node) {
            $return[] = (object) [
                'text' => $node->hasChildNodes()
                    ? $dom->saveHTML($node->childNodes->item(0))
                    : $dom->saveHTML($node),
                'type' => ($node->nodeName == '#text') ? 'p' : $node->nodeName
            ];
        }

        return $return;
    }
}
