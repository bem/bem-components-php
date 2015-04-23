<?php
return function ($bh) {

    $bh->match('progressbar', function($ctx, $json) {
        $val = $json->val ?: 0;
        $ctx
            ->js([ 'val' => $val ])
            ->content([
                'elem' => 'bar',
                'attrs' => [ 'style' => 'width:' . $val . '%' ]
            ]);
    });
};
