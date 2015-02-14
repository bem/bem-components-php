<?php
return function ($bh) {

    $bh->match('checkbox-group', function($ctx, $json) {
        $ctx
            ->tag('span')
            ->js(true)
            ->mix([ 'block' => 'control-group' ]);

        $mods = $ctx->mods();
        $val = $json->val;
        $isValDef = key_exists('val', $json);

        if ($isValDef && !is_array($val)) throw new \Exception('checkbox-group: val must be an array');

        $content = [];
        if ($json->options) {
            foreach ($json->options as $i => $option) {
                $content[] = [
                    $i && !$mods->type ? [ 'tag' => 'br' ] : null,
                    [
                        'block' => 'checkbox',
                        'mods' => [
                            'type' => $mods->type,
                            'theme' => $mods->theme,
                            'size' => $mods->size,
                            'checked' => $isValDef && in_array(@$option['val'], $val),
                            'disabled' => @$option['disabled'] ?: $mods->disabled
                        ],
                        'name' => $json->name,
                        'val' => @$option['val'],
                        'text' => @$option['text'],
                        'title' => @$option['title'],
                        'icon' => @$option['icon']
                    ]
                ];
            }
        }
        $ctx->content($content);
    });

};
