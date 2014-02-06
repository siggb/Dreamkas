<?php

namespace Lighthouse\ReportsBundle\Document\GrossMargin\Store;

use Lighthouse\CoreBundle\Document\AbstractCollection;

class DayGrossMarginCollection extends AbstractCollection
{
    /**
     * @param array $elements
     */
    public function append(array $elements)
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }
}