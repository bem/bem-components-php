<?php
return function ($bh) {

    $bh->match('control-group', function($ctx, $json) {
        $ctx->attrs([ 'role' => 'group' ]);
    });

};
