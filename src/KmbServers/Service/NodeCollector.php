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
namespace KmbServers\Service;

use GtnDataTables\Model\Collection;
use GtnDataTables\Service\CollectorInterface;
use KmbDomain\Model\EnvironmentInterface;
use KmbPuppetDb\Model\NodeInterface;
use KmbPuppetDb\Service;

class NodeCollector implements CollectorInterface
{
    /** @var Service\Node */
    protected $nodeService;

    /**
     * @param array $params
     * @return Collection
     */
    public function findAll(array $params = null)
    {
        $offset = isset($params['start']) ? $params['start'] : null;
        $limit = isset($params['length']) ? $params['length'] : null;

        $queryEnvironment = null;
        if (isset($params['environment'])) {
            /** @var EnvironmentInterface $environment */
            $environment = $params['environment'];
            $queryEnvironment = [
                '=',
                ['fact', NodeInterface::ENVIRONMENT_FACT],
                $environment->getNormalizedName()
            ];
        }

        $queryFactFilter = null;
        $factName = isset($params['factName']) && $params['factName'] !== 'default' ? $params['factName'] : null;
        $factValue = isset($params['factValue']) ? $params['factValue'] : null;
        if (!empty($factName) && !empty($factValue)) {
            $queryFactFilter = [
                '~',
                ['fact', $factName],
                $factValue
            ];
        }

        $querySearch = null;
        if (isset($params['search']['value']) && !empty($params['search']['value'])) {
            $search = $params['search']['value'];
            $querySearch = [
                'or',
                [
                    '~',
                    ['fact', 'hostname'],
                    $search
                ],
                [
                    '~',
                    ['fact', NodeInterface::ENVIRONMENT_FACT],
                    $search
                ],
                [
                    '~',
                    ['fact', 'operatingsystem'],
                    $search
                ],
                [
                    '~',
                    ['fact', 'kernelversion'],
                    $search
                ],
                [
                    '~',
                    ['fact', 'lsbdistcodename'],
                    $search
                ],
            ];
        }

        $query = array_filter([$querySearch, $queryFactFilter, $queryEnvironment]);
        if (count($query) > 1) {
            array_unshift($query, 'and');
        } else {
            $query = array_shift($query);
        }

        $orderBy = [];
        if (isset($params['order'])) {
            foreach ($params['order'] as $clause) {
                $orderBy[] = [
                    'field' => $clause['column'],
                    'order' => $clause['dir'],
                ];
            }
        }

        $nodesCollection = $this->getNodeService()->getAll($query, $offset, $limit, $orderBy);

        return Collection::factory($nodesCollection->getData(), $nodesCollection->getTotal(), $nodesCollection->getTotal());
    }

    /**
     * Get NodeService.
     *
     * @return \KmbPuppetDb\Service\Node
     */
    public function getNodeService()
    {
        return $this->nodeService;
    }

    /**
     * Set NodeService.
     *
     * @param \KmbPuppetDb\Service\Node $nodeService
     * @return NodeCollector
     */
    public function setNodeService($nodeService)
    {
        $this->nodeService = $nodeService;
        return $this;
    }
}
