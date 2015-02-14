<?php
return function ($bh) {

    $bh->match('select_mode_radio', function($ctx) {
        $ctx->applyBase();

        $refs = $ctx->tParam('refs');

        if ($refs->firstOption && empty($refs->checkedOptions)) {
            $refs->firstOption['checked'] = true;
            $refs->checkedOptions[] = $refs->firstOption;
        }

        $refs->checkedOption = $refs->checkedOptions[0];

        $ctx
            ->tParam('checkedOption', $refs->checkedOption)
            ->content([
                [
                    'elem' => 'control',
                    'val' => @$refs->checkedOption['val']
                ],
                $ctx->content()
            ], true);
    });

    $bh->match('select_mode_radio__button', function($ctx) {
        $ctx->content([
            'elem' => 'text',
            'content' => @$ctx->tParam('refs')->checkedOption['text']
        ]);
    });

};
