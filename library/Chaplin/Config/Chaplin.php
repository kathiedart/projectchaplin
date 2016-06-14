<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
class Chaplin_Config_Chaplin
    extends Chaplin_Config_Abstract
{
    const CONFIG_TYPE = 'Ini';

    protected function _getConfigType()
    {
        return self::CONFIG_TYPE;
    }

    protected function _getConfigFile()
    {
        return realpath(APPLICATION_PATH.'/../config/chaplin.ini');
    }

    public function getLocale()
    {
        return $this->_getValue(
            $this->_zendConfig->locale,
            'locale'
        );
    }

    public function getVhost()
    {
        return $this->_getValue(
            $this->_zendConfig->vhost,
            'vhost'
        );
    }

    public function isSSL()
    {
        return $this->_getValue(
            $this->_zendConfig->ssl,
            'ssl'
        );
    }

    public function getScheme()
    {
        return $this->isSSL()?'https':'http';
    }

    public function getFullVhost()
    {
        return $this->getScheme().'://'.$this->getVhost();
    }

    public function getYouTubeAPIKey()
    {
        return $this->_getValue(
            $this->_zendConfig->youtube->youtubeapi,
            ''
        );
    }
}
