<?php
/**
 * @copyright Copyright (c) 2014 Orange Applications for Business
 * @link      http://github.com/kambalabs for the sources repositories
 *
 * This file is part of Kamba.
 *
 * Kamba is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * Kamba is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Kamba.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace KmbServers\View\Helper;

use KmbPuppetDb\Model;
use Zend\View\Helper\AbstractHelper;

class NodeBtnClass extends AbstractHelper
{
    public function __invoke(Model\NodeInterface $node)
    {
        if ($node->getReportedAt() != null && $node->getReportedAt()->diff(new \DateTime())->days >= 1) {
            return 'btn-primary';
        }
        switch ($node->getStatus()) {
            case Model\NodeInterface::UNCHANGED:
                return 'btn-success';
            case Model\NodeInterface::CHANGED:
                return 'btn-warning';
            case Model\NodeInterface::FAILED:
                return 'btn-danger';
            default:
                return 'btn-primary';
        }
    }
}
