<?php


namespace common\models;


use Yii;

trait UniqueLinkUtils
{
    private function _processLink(string $previousLink, string $newLink, string $randomId) : array
	{
		if ($newLink === $previousLink)
		{
			return ['result' => true, 'newLink' => $newLink, 'randomId' => $randomId];
		}
		else
		{
			$duplicate = UniqueId::findOne($newLink);
			if ($randomId !== $previousLink && $duplicate == null)
			{
				UniqueId::tryDelete($previousLink);
			}
			if (!($newLink))
			{
				$newLink = $randomId;
				return ['result' => true, 'newLink' => $newLink, 'randomId' => $randomId];
			}
			if ($duplicate != null)
			{
				if ($duplicate->id === $randomId)
				{
					return ['result' => true, 'newLink' => $newLink, 'randomId' => $randomId];
				}
				Yii::$app->session->setFlash('linkExists', 'This link is already in use');
				return ['result' => false, 'newLink' => $newLink, 'randomId' => $randomId];
			}

			$uid = new UniqueId();
			$uid->id = $newLink;
			return ['result' => $uid->save(), 'newLink' => $newLink, 'randomId' => $randomId];
		}
	}

    private function processLink(string $previousLink, string $newLink, string $randomId) : bool
	{
		$result = $this->_processLink($previousLink, $newLink, $randomId);
		$this->setLinkAndId($result['newLink'], $result['randomId']);
		return $result['result'];
	}

    public abstract function setLinkAndId(string $newLink, string $randomId);

	public abstract function linkChange(string $previousLink) : bool;
}