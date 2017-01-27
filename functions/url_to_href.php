<?php
// Make hyperlinks by url
// Usage: echo url_to_href('http://gbretas.com'); outputs <a href="http://gbretas.com">http://gbretas.com</a>
function url_to_href($str) {
    $pattern = '/((?:http|https)(?::\\/{2}[\\w]+)(?:[\\/|\\.]?)(?:[^\\s"]*))/is';
    $replace = '<a target="blank" href="$1">$1</a>';
    return preg_replace($pattern, $replace, $str);
}