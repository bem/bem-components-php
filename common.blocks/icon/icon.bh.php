<?php
return function ($bh) {
    $bh->match('icon', function($ctx, $json) {
        $attrs = [];
        $url = $json->url;
        if($url) $attrs['style'] = 'background-image:url(' . $url . ')';
        $ctx
            ->tag('span')
            ->attrs($attrs);
    });
};
