<?php
return function ($bh) {

    $bh->match('menu', function($ctx, $json) {
        $menuMods = [
            'theme' => $ctx->mod('theme'),
            'disabled' => $ctx->mod('disabled')
        ];

        $ctx
            ->js(true)
            ->tParam('menuMods', $menuMods)
            ->mix([ 'elem' => 'control' ]);

        $attrs = [ 'role' => 'menu' ];
        $ctx->mod('disabled') || ($attrs['tabindex'] = 0);
        $ctx->attrs($attrs);

        $refs = new stdClass();
        $refs->firstItem = null;
        $refs->checkedItems = [];

        if($json->content) {
            $isValDef = key_exists('val', $json);
            $isModeCheck = $ctx->mod('mode') === 'check';

            $containsVal = function ($val) use ($isValDef, $isModeCheck, $json) {
                return $isValDef &&
                    ($isModeCheck?
                        is_array($json->val) && in_array($val, $json->val) :
                        $json->val === $val);
            };

            $iterateItems = function (&$content) use (&$iterateItems, $containsVal, $refs) {
                foreach ($content as $_ => $itemOrGroup) {
                    if (!$itemOrGroup) {
                        break;
                    }
                    // menu__group
                    if ($itemOrGroup->block !== 'menu-item') {
                        $iterateItems($content[$_]);
                        continue;
                    }

                    $refs->firstItem || ($refs->firstItem =& $content[$_]);
                    if ($containsVal($itemOrGroup->val)) {
                        $itemOrGroup->mods->checked = true;
                        $refs->checkedItems[] =& $content[$_];
                    }
                }
            };

            if(is_array($json->content)) throw new \Exception('menu: content must be an array of the menu items');

            $iterateItems($json->content);
        }

        $ctx
            ->tParam('refs', $refs)
            ->tParam('firstItem', $refs->firstItem)
            ->tParam('checkedItems', $refs->checkedItems);
    });

};
