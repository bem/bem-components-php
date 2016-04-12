<?php
return function ($bh) {

    $bh->match('radio__text', function($ctx) {
        $ctx
            ->tag('span')
            ->attrs([ 'role' => 'presentation' ]);
    });

};
