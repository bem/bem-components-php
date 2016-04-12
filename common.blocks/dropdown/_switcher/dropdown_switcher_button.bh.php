<?php
return function ($bh) {

    $bh->match('dropdown_switcher_button__switcher', function($ctx, $json) {
        $dropdown = $ctx->tParam('dropdown');
        $switcher = $dropdown->switcher;

        // if (Array.isArray(content)) return content
        if ($ctx->isArray($switcher)) { // php!yolo. bug?
            return $switcher;
            // if (count($content) > 1) return $content;
            // $content = $content[0];
        }

        $res = $ctx->isSimple($switcher)?
            [ 'block' => 'button', 'text' => $switcher ] :
            $switcher;

        $res = $ctx->phpize($res);
        if (empty($res->attrs)) $res->attrs = [];

        if ($res->block === 'button') {
            $resMods = $res->mods;
            $resAttrs = &$res->attrs;
            $dropdownMods = $json->blockMods ?: $json->mods;
            $resMods->size || ($resMods->size = $dropdownMods->size);
            $resMods->theme || ($resMods->theme = $dropdownMods->theme);
            $resMods->disabled = $dropdownMods->disabled;

            $resAttrs['aria-haspopup'] = 'true';
            $resAttrs['aria-controls'] = $ctx->tParam('popupId');
            $resAttrs['aria-expanded'] = 'false';

            $res->mix = $ctx->tParam('mix');
        }

        return $res;
    });

};
