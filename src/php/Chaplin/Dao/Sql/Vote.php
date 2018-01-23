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
 * @link      https://github.com/danwdart/projectchaplin
**/

namespace Chaplin\Dao\Sql;

use Chaplin\Dao\Sql\SqlAbstract;
use Chaplin\Dao\Interfaces\Vote as InterfaceVote;
use Chaplin\Model\User as ModelUser;
use Chaplin\Model\Video as ModelVideo;



class Vote extends SqlAbstract implements InterfaceVote
{
    const TABLE = 'Votes';

    protected function _getTable()
    {
        return self::TABLE;
    }

    protected function _getPrimaryKey()
    {
        // Not single
        return null;
    }

    public function addVote(ModelUser $modelUser, ModelVideo $modelVideo, $intVote)
    {
        $this->_getAdapter()->query(
            'INSERT INTO '.self::TABLE.' SET '.
            'Vote = ?, Username = ?, VideoId = ? ON DUPLICATE KEY UPDATE Vote = ?',
            [
                $intVote,
                $modelUser->getUsername(),
                $modelVideo->getVideoId(),
                $intVote
            ]
        );
    }

    protected function _sqlToModel(array $arrSql)
    {
    }

    protected function _modelToSql(array $arrModel)
    {
    }

    public function convertToModel($arrData)
    {
    }
}
