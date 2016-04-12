<?php
return function ($bh) {

    $bh->match('select', function($ctx, $json) {
        if (!$ctx->mod('mode')) throw new \Exception('Can\'t build select without mode modifier');

        $isValDef = key_exists('val', $json);
        $isModeCheck = $ctx->mod('mode') === 'check';

        // php!yolo
        $refs = new StdClass();
        $refs->firstOption = null;
        $refs->checkedOptions = [];
        $refs->optionIds = [];

        $containsVal = function ($val) use ($isValDef, $isModeCheck, $json) {
            return $isValDef &&
                ($isModeCheck?
                    in_array($val, $json->val) :
                    $json->val === $val);
        };

        $iterateOptions = function (&$content) use ($containsVal, &$iterateOptions, $refs, $ctx) {
            foreach ($content as $_ => $option) {
                if(isset($option['group'])) {
                    $iterateOptions($content[$_]['group']);
                } else {
                    $refs->firstOption || ($refs->firstOption =& $content[$_]);
                    $refs->optionIds[] = $content[$_]['id'] = $ctx->generateId();
                    if(isset($option['val']) and $containsVal($option['val'])) {
                        $content[$_]['checked'] = true;
                        $refs->checkedOptions[] =& $content[$_];
                    }
                }
            }
        };

        $iterateOptions($json->options);

        $ctx
            ->js([
                'name' => $json->name,
                'optionsMaxHeight' => $json->optionsMaxHeight
            ])
            ->tParam('select', $json)
            ->tParam('refs', $refs)
            ->tParam('firstOption', $refs->firstOption)
            ->tParam('checkedOptions', $refs->checkedOptions)
            ->content([
                [ 'elem' => 'button' ],
                [
                    'block' => 'popup',
                    'mods' => [ 'target' => 'anchor', 'theme' => $ctx->mod('theme'), 'autoclosable' => true ],
                    'directions' => ['bottom-left', 'bottom-right', 'top-left', 'top-right'],
                    'content' => [ 'block' => $json->block, 'mods' => $ctx->mods(), 'elem' => 'menu' ]
                ]
            ]);
    });

};
