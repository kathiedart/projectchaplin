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
 * @package   ProjectChaplin
 * @author    Dan Dart <chaplin@dandart.co.uk>
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/kathiedart/projectchaplin
**/
class Chaplin_Model_Field_FieldId
    extends Chaplin_Model_Field_Abstract
{
    private $_mixedValue;
    
    public function setFromData($mixedValue)
    {
        $this->_mixedValue = $mixedValue;
    }
    
    public function setValue($mixedValue)
    {
        if (!is_null($this->_mixedValue)) {
            throw new Exception('id fields are read-only');
        }
        $this->_mixedValue = $mixedValue;
        $this->_bIsDirty = true;
    }
    
    public function getValue($mixedDefault)
    {
        return $this->_mixedValue;
    }
}  