<?php
return function ($bh) {

    $bh->match('progressbar', function($ctx, $json) {
        $ctx
            ->js([ 'val' => $json->val ])
            ->content([
                'elem' => 'bar',
                'attrs' => [ 'style' => 'width:' . $json->val . '%' ]
            ]);
    });
};
