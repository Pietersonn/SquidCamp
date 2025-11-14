<?php

namespace App\Helpers;

class Helper
{
    /**
     * Update page configuration dan kembalikan HTML yang dibutuhkan.
     *
     * @param array $pageConfigs
     * @return string
     */
    public static function updatePageConfig(array $pageConfigs = [])
    {
        $html = '';

        // Contoh: set layout class
        if (isset($pageConfigs['myLayout'])) {
            $layoutClass = $pageConfigs['myLayout'];
            $html .= "<script>document.body.classList.add('layout-{$layoutClass}');</script>";
        }

        // Contoh: set page name untuk JS atau title
        if (isset($pageConfigs['pageName'])) {
            $pageName = $pageConfigs['pageName'];
            $html .= "<script>document.body.dataset.pageName = '{$pageName}';</script>";
        }

        return $html;
    }
}
