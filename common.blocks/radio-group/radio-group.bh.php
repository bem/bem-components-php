<?php
return function ($bh) {

    $bh->match('radio-group', function($ctx, $json) {
        $ctx
            ->tag('span')
            ->js(true)
            ->mix([ 'block' => 'control-group' ]);

        $mods = $ctx->mods();
        $isValDef = key_exists('val', $json);
        $content = [];

        foreach ($json->options as $i => $option) {
            if ($i && !$mods->type) {
                $content[] = [ 'tag' => 'br' ];
            }
            $content[] = [
                'block' => 'radio',
                'mods' => [
                    'type' => $mods->type,
                    'mode' => $mods->mode,
                    'theme' => $mods->theme,
                    'size' => $mods->size,
                    'checked' => $isValDef && $json->val === @$option['val'],
                    'disabled' => @$option['disabled'] ?: $mods->disabled
                ],
                'name' => $json->name,
                'val' => @$option['val'],
                'text' => @$option['text'],
                'title' => @$option['title'],
                'icon' => @$option['icon']
            ];
        }

        $ctx->content($content);
    });

};
