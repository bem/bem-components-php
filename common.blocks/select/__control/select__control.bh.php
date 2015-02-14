<?php
return function ($bh) {

    $bh->match('select__control', function($ctx, $json) {
        $ctx
            ->tag('input')
            ->attrs([
                'type' => 'hidden',
                'name' => $ctx->tParam('select')->name,
                'value' => $json->val,
                'disabled' => isset($json->blockMods) && $json->blockMods->disabled? 'disabled' : null
            ]);
    });

};
