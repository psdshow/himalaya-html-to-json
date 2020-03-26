<?php
require __DIR__.'/src/service/htmlToJson.php';

use html2json\service\htmlToJson;

$inner_html = '<p style="line-height: normal;"></p><div style="text-align: right;"><span style="font-size: 18px;">text1</span></div><div style="text-align: right;"><span style="font-size: 18px;font-family: AliHYAiHei; color: rgb(235, 13, 13);"><em>text2</em></span></div><p></p>';

$json = htmlToJson::parseInnerHtmlToJson($inner_html);

$contents = ['aaa','bbb'];

echo htmlToJson::replaceHtmlContent($json, $contents);