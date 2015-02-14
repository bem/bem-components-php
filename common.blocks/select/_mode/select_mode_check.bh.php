<?php
return function ($bh) {

    $bh->match('select_mode_check', function($ctx, $json) {
        $ctx
            ->applyBase()
            ->js($ctx->extend($ctx->js(), [ 'text' => $json->text ]));

        $checkedOptions = $ctx->tParam('refs')->checkedOptions;

        if(is_array($checkedOptions) && isset($checkedOptions[0])) {
            $res = array_map(function ($option) {
                return [
                    'elem' => 'control',
                    'val' => $option['val']
                ];
            }, $checkedOptions);

            $ctx->content([
                $res,
                $ctx->content()
            ], true);
        }
    });

    $bh->match('select_mode_check__button', function($ctx) {
        $checkedOptions = $ctx->tParam('refs')->checkedOptions;
        $ctx->content([
            'elem' => 'text',
            'content' => sizeof($checkedOptions) === 1?
                $checkedOptions[0]['text'] :
                array_reduce($checkedOptions, function ($res, $option) {
                    return $res . ($res? ', ' : '') . (@$option['checkedText'] ?: @$option['text']);
                }, '') ?:
                    $ctx->tParam('select')->text
        ]);
    });

};
