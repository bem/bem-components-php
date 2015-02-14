<?php
return function ($bh) {

    $bh->match('menu_mode_radio', function($ctx) {
        $ctx->applyBase();
        $firstItem = $ctx->tParam('firstItem');
        if ($firstItem && !count($ctx->tParam('checkedItems'))) {
            $firstItem->mods->checked = true;
        }
    });

};
