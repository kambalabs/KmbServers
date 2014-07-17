<?php
/**
 * @copyright Copyright (c) 2014 Orange Applications for Business
 * @link      http://github.com/multimediabs/kamba for the canonical source repository
 *
 * This file is part of kamba.
 *
 * kamba is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * kamba is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with kamba.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace KmbServers\View\Helper;

use DateTime;
use DateTimeZone;
use KmbPuppetDb\Model;
use Zend\View\Helper\AbstractHelper;

class FormatNodeReportTime extends AbstractHelper
{
    /**
     * Locale to use instead of the default
     *
     * @var string
     */
    protected $locale;

    /**
     * Timezone to use
     *
     * @var string
     */
    protected $timezone;

    public function __invoke(Model\Node $node)
    {
        if ($node->getReportedAt() == null) {
            return '??:??:??';
        }
        if ($node->getReportedAt()->diff(new DateTime())->days >= 1) {
            return '> 24h';
        }
        return $node->getReportedAt()->setTimezone($this->getTimezone())->format('H:i:s');
    }

    /**
     * Set timezone to use instead of the default
     *
     * @param  string $timezone
     * @return FormatNodeReportTime
     */
    public function setTimezone($timezone)
    {
        $this->timezone = (string) $timezone;
        return $this;
    }

    /**
     * Get the timezone to use
     *
     * @return DateTimeZone
     */
    public function getTimezone()
    {
        if (!$this->timezone) {
            $this->timezone = date_default_timezone_get();
        }

        return new DateTimeZone($this->timezone);
    }
}
