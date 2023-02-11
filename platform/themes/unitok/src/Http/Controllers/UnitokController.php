<?php

namespace Theme\Unitok\Http\Controllers;

use Botble\Theme\Http\Controllers\PublicController;
use Theme;

class UnitokController extends PublicController
{
    /**
     * {@inheritDoc}
     */
    public function getIndex()
    {
        return redirect('analysis/market-overview/btc-overview');
        $theme = Theme::uses('unitok')->layout('vuejs');
        return $theme->scope('analysis.index')->render();
        //return parent::getIndex();
    }

    /**
     * {@inheritDoc}
     */
    public function getView($key = null)
    {
        return parent::getView($key);
    }

    /**
     * {@inheritDoc}
     */
    public function getSiteMap()
    {
        return parent::getSiteMap();
    }
}
