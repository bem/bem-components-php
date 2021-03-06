<?php
return function ($bh) {

    $bh->match('select__menu', function($ctx, $json) {
        $mods = $ctx->mods();
        $select = $ctx->tParam('select');
        $optionToMenuItem = function ($option) use ($mods) {
            $res = [
                'block' => 'menu-item',
                'mods' => [ 'disabled' => $mods->disabled ?: @$option['disabled'] ],
                'attrs' => [ 'role' => 'option' ],
                'id' => @$option['id'],
                'val' => @$option['val'],
                'js' => [ 'checkedText' => @$option['checkedText'] ],
                'content' => @$option['text']
            ];

            if (!empty($option['icon'])) {
                $res['js']['text'] = @$option['text'];
                $res['content'] = [
                    $option['icon'],
                    $res['content']
                ];
            }

            return $res;
        };

        return $select->options ? [
            'block' => 'menu',
            'mix' => [ 'block' => $json->block, 'elem' => $json->elem ],
            'mods' => [
                'size' => $mods->size,
                'theme' => $mods->theme,
                'disabled' => $mods->disabled,
                'mode' => $mods->mode
            ],
            'val' => $select->val,
            'attrs' => [ 'role' => null, 'tabindex' => null ],
            'content' => array_map(function ($optionOrGroup) use ($select, $optionToMenuItem) {
                return isset($optionOrGroup['group'])?
                    [
                        'elem' => 'group',
                        'title' => @$optionOrGroup['title'],
                        'content' => array_map($optionToMenuItem, $optionOrGroup['group'])
                    ] :
                    $optionToMenuItem($optionOrGroup);
            }, $select->options)
        ] : null;
    });

};
