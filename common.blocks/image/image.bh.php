<?php
return function ($bh) {
    $bh->match('image', function($ctx, $json) {
        $ctx->attr('role', 'img');

        if (!empty($json->content)) {
            $ctx->tag('span');
            return;
        }

        $ctx
            ->tag('img')
            ->attrs([
                'src' => $json->url,
                'width' => $json->width,
                'height' => $json->height,
                'alt' => $json->alt,
                'title' => $json->title
            ]);
    });
};
