<?php
return function ($bh) {

    $bh->match('button', function($ctx, $json) {
        $ctx->tag($json->tag ?: 'button'); // NOTE: need to predefine tag

        $json->icon = $ctx->phpize($json->icon);
        $modType = $ctx->mod('type');
        $isRealButton = ($ctx->tag() === 'button')
            && (!$modType || $modType === 'submit');

        $ctx
            ->tParam('_button', $json)
            ->js(true)
            ->attrs([
                'role' => 'button',
                'tabindex' => $json->tabIndex,
                'id' => $json->id,
                'type' => $isRealButton? ($modType ?: 'button') : null,
                'name' => $json->name,
                'value' => $json->val,
                'title' => $json->title
            ])
            ->mix([ 'elem' => 'control' ]); // NOTE: satisfy interface of `control`

        if ($ctx->mod('disabled')) {
            $isRealButton ?
                $ctx->attr('disabled', 'disabled')
                : $ctx->attr('aria-disabled', 'true');
        }

        $content = $ctx->content();
        if ($content === null) {
            $content = [$json->icon];
            key_exists('text', $json) && ($content[] = ['elem' => 'text', 'content' => $json->text]);
            $ctx->content($content);
        }
    });

};
