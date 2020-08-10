<?php


namespace common\models;


use Yii;

trait UniqueLinkUtils
{
	/**
	 * @param string $previousLink
	 * @param string $newLink
	 * @param string $randomId
	 * @return array
	 */
	private function _processLink($previousLink, $newLink, $randomId)
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

	/**
	 * @param string $previousLink
	 * @param string $newLink
	 * @param string $randomId
	 * @return bool
	 */
	private function processLink($previousLink, $newLink, $randomId)
	{
		$result = $this->_processLink($previousLink, $newLink, $randomId);
		$this->setLinkAndId($result['newLink'], $result['randomId']);
		return $result['result'];
	}

	/**
	 * @param string $newLink
	 * @param string $randomId
	 */
	public abstract function setLinkAndId($newLink, $randomId);

	/**
	 * @param string $previousLink
	 * @return bool
	 */
	public abstract function linkChange($previousLink);
}