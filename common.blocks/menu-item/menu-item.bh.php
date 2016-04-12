<?php
return function ($bh) {
    $bh->match('menu-item', function($ctx, $json) {
        $menuMods = (array)$ctx->tParam('menuMods');
        $menuMode = @$menuMods['mode'];
        $role = $menuMode?
            ($menuMode === 'check' ? 'menuitemcheckbox' : 'menuitemradio') :
            'menuitem';

        empty($menuMods) || $ctx->mods([
            'theme' => @$menuMods['theme'],
            'disabled' => @$menuMods['disabled']
        ]);

        $ctx
            ->js([ 'val' => $json->val ])
            ->attrs([
                'role' => $role,
                'id' => $json->id ?: $ctx->generateId(),
                'aria-disabled' => $ctx->mod('disabled') ? 'true' : null,
                'aria-checked' => $menuMode ? ($ctx->mod('checked')? 'true' : 'false') : null
            ]);
    });
};
