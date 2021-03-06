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

namespace Chaplin\Model;

use Chaplin\Model\Field\Hash;
use Chaplin\Model\User;
use Chaplin\Model\Video\Privacy;
use Chaplin\Model\Video\Licence;
use Chaplin\Gateway;
use Chaplin\Auth;
use Misd\Linkify\Linkify;

class Video extends Hash
{
    const FIELD_VIDEOID = 'VideoId';
    const FIELD_TIMECREATED = 'TimeCreated';
    const FIELD_USERNAME = 'Username';
    const FIELD_FILENAME = 'Filename';
    const FIELD_THUMBNAIL = 'Thumbnail';
    const FIELD_TITLE = 'Title';
    const FIELD_DESCRIPTION = 'Description';
    const FIELD_UPLOADER = 'Uploader';
    const FIELD_SOURCE = 'Source';
    const FIELD_LICENCE = 'Licence';
    const FIELD_LENGTH = 'Length';
    const FIELD_WIDTH = 'Width';
    const FIELD_HEIGHT = 'Height';
    const FIELD_FORMAT = 'Format';
    const FIELD_BITRATE = 'Bitrate';
    const FIELD_SIZE = 'Size';
    const FIELD_VIEWS = 'Views';
    const FIELD_PARTIALVIEWS = 'PartialViews';
    const FIELD_BOUNCES = 'Bounces';
    const FIELD_PRIVACY = 'Privacy';
    const FIELD_OBJ_FEEDBACK = 'Feedback';
    const FIELD_VOTESUP = 'VotesUp';
    const FIELD_VOTESDOWN = 'VotesDown';
    const FIELD_YOURVOTE = 'YourVote';
    const FIELD_ARRAY_TAGS = 'Tags';
    const FIELD_ARRAY_NOTTAGS = 'NotTags';
    const CHILD_ASSOC_COMMENTS = 'Comments';

    protected $arrFields = [
        self::FIELD_VIDEOID         => [
            'Class' => 'Chaplin\\Model\\Field\\FieldId'
        ],
        self::FIELD_TIMECREATED     => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_USERNAME        => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_FILENAME        => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_THUMBNAIL       => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_TITLE           => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_DESCRIPTION     => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_UPLOADER        => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_SOURCE          => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_LICENCE         => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_LENGTH          => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_WIDTH           => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_HEIGHT          => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_FORMAT          => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_BITRATE         => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_SIZE            => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_VIEWS           => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_PARTIALVIEWS    => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_BOUNCES         => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_PRIVACY         => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_VOTESUP         => [
            'Class' => 'Chaplin\\Model\\Field\\Readonly'
        ],
        self::FIELD_VOTESDOWN       => [
            'Class' => 'Chaplin\\Model\\Field\\Readonly'
        ],
        self::FIELD_YOURVOTE       => [
            'Class' => 'Chaplin\\Model\\Field\\Readonly'
        ],
        self::CHILD_ASSOC_COMMENTS  => [
            'Class' => 'Chaplin\\Model\\Field\\Collection',
            'Param' => 'Chaplin\\Model\\Video\\Comment'
        ]
    ];

    public static function create(
        User $modelUser,
        $strFilename,
        // form element?
        $strThumbURL,
        $strTitle,
        $strDescription,
        $strUploader,
        $strSource
    ) {

        $video = new self();
        $video->bIsNew = true;
        $video->setField(self::FIELD_VIDEOID, md5(uniqid()));
        $video->setField(self::FIELD_TIMECREATED, time());
        $video->setField(self::FIELD_USERNAME, $modelUser->getUsername());
        $video->setField(self::FIELD_FILENAME, $strFilename);
        $video->setField(self::FIELD_THUMBNAIL, $strThumbURL);
        $video->setField(self::FIELD_TITLE, $strTitle);
        $video->setField(
            self::FIELD_PRIVACY,
            Privacy::ID_PUBLIC
        );
        $video->setField(self::FIELD_DESCRIPTION, $strDescription);
        $video->setField(self::FIELD_UPLOADER, $strUploader);
        $video->setField(self::FIELD_SOURCE, $strSource);
        return $video;
    }

    public function getId()
    {
        return $this->getVideoId();
    }

    public function setFromAPIArray(array $arrVideo)
    {
        foreach ($arrVideo as $strKey => $strValue) {
            $strValue = (string)$strValue;
            $this->setField($strKey, $strValue);
        }
    }

    public function getVideoId()
    {
        return $this->getField(self::FIELD_VIDEOID, null);
    }

    public function getTitle()
    {
        return $this->getField(self::FIELD_TITLE, null);
    }

    public function getShortTitle()
    {
        return $this->getTitle();
    }

    public function getFilename()
    {
        return $this->strURLPrefix.$this->getField(
            self::FIELD_FILENAME,
            null
        );
    }

    public function setFilename($strFilename)
    {
        return $this->setField(self::FIELD_FILENAME, $strFilename);
    }

    public function getFilenameRoot()
    {
        $arrPathInfo = pathinfo($this->getFilename());
        return $arrPathInfo['filename'];
    }

    public function getSuggestedTitle()
    {
        return ('' == $this->getTitle())?
            $this->getFilenameRoot():
            $this->getTitle();
    }

    public function getThumbnail()
    {
        return $this->strURLPrefix.$this->getField(
            self::FIELD_THUMBNAIL,
            null
        );
    }

    public function getDescription()
    {
        $strDescription = $this->getField(self::FIELD_DESCRIPTION, null);

        $linkify = new Linkify(
            [
                "attr" => [
                    "target" => "_blank"
                ]
            ]
        );

        $strLinkified = $linkify->process($strDescription);

        return nl2br($strLinkified);
    }

    public function setDescription($strDescription)
    {
        return $this->setField(self::FIELD_DESCRIPTION, $strDescription);
    }

    public function getSource()
    {
        return $this->getField(self::FIELD_SOURCE, null);
    }

    public function getUploader()
    {
        return $this->getField(self::FIELD_UPLOADER, null);
    }

    public function getComments()
    {
        return $this->getField(self::CHILD_ASSOC_COMMENTS, array());
    }

    public function setLicence($strLicence)
    {
        return $this->setField(self::FIELD_LICENCE, $strLicence);
    }

    public function getLicenceId()
    {
        return $this->getField(
            self::FIELD_LICENCE,
            Licence::ID_CCBYSA
        );
    }

    public function getLicence()
    {
        return new Licence($this->getLicenceId());
    }

    public function getPrivacyId()
    {
        return $this->getField(
            self::FIELD_PRIVACY,
            Privacy::ID_PUBLIC
        );
    }

    public function getPrivacy()
    {
        return new Privacy($this->getPrivacyId());
    }

    public function getTimeCreated()
    {
        return $this->getField(self::FIELD_TIMECREATED, null);
    }

    public function getUsername()
    {
        return $this->getField(self::FIELD_USERNAME, null);
    }

    public function getVotesUp()
    {
        return $this->getField(self::FIELD_VOTESUP, 0);
    }

    public function getVotesDown()
    {
        return $this->getField(self::FIELD_VOTESDOWN, 0);
    }

    public function getYourVote()
    {
        return $this->getField(self::FIELD_YOURVOTE, null);
    }

    public function isMine()
    {
        if (!Auth::getInstance()->hasIdentity()) {
            return false;
        }
        if (Auth::getInstance()->getIdentity()->getUser()->isGod()) {
            // God users own everything, mwuhahaha
            return true;
        }
        return Auth::getInstance()
            ->getIdentity()
            ->getUser()
            ->getUsername() ==
            $this->getUsername();
    }

    public function getTimeAgo()
    {
        $time = time();
        $timefrom = $this->getTimeCreated();

        $diff = $time - $timefrom;
        $suffix = 0 < $diff ? ' ago' : ' in the future';

        $diff = abs($diff);
        // hacky
        if (60 > $diff) {
            return $diff.($diff==1?' second':' seconds').$suffix;
        } elseif (60*60 > $diff) {
            $n = number_format(floor($diff/60));
            return $n.($n==1?' minute':' minutes').$suffix;
        } elseif (60*60*24 > $diff) {
            $n = number_format(floor(($diff/60)/60));
            return $n.($n==1?' hour':' hours').$suffix;
        } elseif (60*60*24*7 > $diff) {
            $n = number_format(floor((($diff/60)/60)/24));
            return $n.($n==1?' day':' days').$suffix;
        } elseif (60*60*24*30 > $diff) {
            $n = number_format(floor((($diff/60)/60)/24/7));
            return $n.($n==1?' week':' weeks').$suffix;
        } elseif (60*60*24*365 > $diff) {
            $n = number_format(floor((($diff/60)/60)/24/30));
            return $n.($n==1?' month':' months').$suffix;
        } else {
            $n = number_format(floor((($diff/60)/60)/24/365));
            return $n.($n==1?' year':' years').$suffix;
        }
    }

    public function delete()
    {
        $strFullPath = APPLICATION_PATH.'/../public'.$this->getFilename();
        unlink($strFullPath);
        $strThumbnailPath = APPLICATION_PATH.'/../public'.$this->getThumbnail();
        unlink($strThumbnailPath);
        return Gateway::getInstance()
            ->getVideo()
            ->delete($this);
    }

    public function save()
    {
        return Gateway::getInstance()
            ->getVideo()
            ->save($this);
    }
}
