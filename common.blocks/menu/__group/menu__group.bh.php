<?php
return function ($bh) {

    $bh->match('menu__group', function($ctx, $json) {
        $ctx->attr('role', 'group');

        if(key_exists('title', $json)) {
            $title = $json->title;
            $titleId = $ctx->generateId();
            $ctx
                ->attr('aria-labelledby', $titleId)
                ->content([
                    [
                        'elem' => 'group-title',
                        'attrs' => [
                            'role' => 'presentation',
                            'id' => $titleId
                        ],
                        'content' => $title
                    ],
                    $ctx->content()
                ], true);
        }
    });

};
