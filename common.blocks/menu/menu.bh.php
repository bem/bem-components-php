<?php
return function ($bh) {

    $bh->match('menu', function($ctx, $json) {
        $mods = $ctx->mods();
        $attrs = [ 'role' => 'menu' ];

        $ctx
            ->js(true)
            ->tParam('menuMods', $mods)
            ->mix([ 'elem' => 'control' ]);

        $mods->disabled?
            $attrs['aria-disabled'] = 'true' :
            $attrs['tabindex'] = 0;

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
                        if ($itemOrGroup->content) {
                            $iterateItems($itemOrGroup->content);
                        }
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
