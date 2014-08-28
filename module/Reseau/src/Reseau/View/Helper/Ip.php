<?php

namespace Reseau\View\Helper;

use Zend\View\Helper\AbstractHelper;
/**
 *
 * @author alexandre.tranchant
 *
 */
class Ip extends AbstractHelper {
	public function __invoke($proper_address)
    {
        return long2ip($proper_address);
    }
}

?>