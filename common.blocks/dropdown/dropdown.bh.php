<?php
return function ($bh) {

    $bh->match([
        'dropdown' => function($ctx) {
            $dropdown = $ctx->json();

            $mix = $ctx->phpize([$dropdown]);
            if (is_array($dropdown->switcher) && array_key_exists('mix', $dropdown->switcher)) {
                $mix->append($dropdown->switcher['mix']);
            }
            if (key_exists('mix', $dropdown)) $mix->append($dropdown->mix);

            $ctx
                ->js($ctx->extend([ 'id' => $ctx->generateId() ], $ctx->js()))
                ->tParam('dropdown', $dropdown)
                ->tParam('popupId', $ctx->generateId())
                ->tParam('theme', $ctx->mod('theme'))
                ->tParam('mix', $mix);

            return [[ 'elem' => 'switcher' ], [ 'elem' => 'popup' ]];
        },

        'dropdown__popup' => function($ctx) {
            $dropdown = $ctx->tParam('dropdown');
            $popup = $dropdown->popup;

            if ($ctx->isSimple($popup) || (@$popup['block'] ?: @$popup->block) !== 'popup') {
                $popup = [ 'block' => 'popup', 'content' => $popup ];
            }

            $dropdown->popup = $popup = $ctx->phpize($popup);
            if (empty($popup->attrs)) $popup->attrs = [];

            $popupMods = $popup->mods;
            $popupAttrs = &$popup->attrs;

            $popupMods->theme || ($popupMods->theme = $ctx->tParam('theme'));
            key_exists('autoclosable', $popupMods) || ($popupMods->autoclosable = true);

            $popupMods->target = 'anchor';
            $popupAttrs['id'] = $ctx->tParam('popupId');

            $popup->mix = $ctx->phpize([$dropdown, $popup->mix]);

            return $popup;
        },

        'dropdown__switcher' => function($ctx) {
            $dropdown = $ctx->tParam('dropdown');
            $dropdown->switcher = $switcher = $ctx->phpize($dropdown->switcher);

            if (key_exists('block', $switcher)) $swticher->mix = $ctx->tParam('mix');

            return $switcher;
        }
    ]);

};
