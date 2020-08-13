<?php

namespace common\models;

use common\exceptions\FileUploadException;
use common\models\query\FileQuery;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property int $id
 * @property string $extension
 * @property string $md5
 * @property string $source_id
 * @property int $type
 * @property int|null $created_at
 */
class File extends ActiveRecord
{
	use TypeArrayUtils;

	/**
	 * @var array<string, int>
	 */
	public static $TYPE = [
		'image' => 1,
		'video' => 2,
	];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::class,
				'updatedAtAttribute' => false,
			]
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['extension', 'md5', 'source_id'], 'required'],
            [['type'], 'integer'],
            [['extension'], 'string', 'max' => 8],
            [['md5'], 'string', 'max' => 32],
            [['source_id'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'extension' => 'Extension',
            'md5' => 'Md5',
            'source_id' => 'Source ID',
            'type' => 'Type',
        ];
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }

	/**
	 * @param string $md5
	 * @param string $typeNumber
	 * @return File
	 */
	public static function findFile($md5, $typeNumber)
	{
		return static::find()->andWhere(['md5' => $md5, 'type' => $typeNumber])->one();
	}

	/**
	 * @param string $alias
	 * @return string
	 * @throws \yii\base\Exception
	 */
	public static function getAlias($alias)
	{
		$a = Yii::getAlias($alias);
		if (!is_dir(dirname($a)))
		{
			FileHelper::createDirectory(dirname($a));
		}
		return $a;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getDirPath()
	{
		return static::getAlias(sprintf('@frontend/web/storage/%s/', static::getTypeName($this->type)));
	}

	/**
	 * @return string
	 * @throws \yii\base\Exception
	 * @throws Exception
	 */
	public function getTempRandomPath()
	{
		return static::getAlias(sprintf('@frontend/web/storage/%s/%s.%s',
			static::getTypeName($this->type),
			Yii::$app->security->generateRandomString(8),
			$this->extension));
	}

	/**
	 * @return string
	 */
	public function getFileName()
	{
		return sprintf("%s.%s", $this->md5, $this->extension);
    }

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getFilePath()
	{
		return static::getAlias(sprintf("@frontend/web/storage/%s/%s", static::getTypeName($this->type), $this->getFileName()));
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getFileDir()
	{
		return dirname($this->getFilePath());
	}

	/**
	 * @param UploadedFile $file
	 * @return bool
	 * @throws FileUploadException
	 * @throws \yii\base\Exception
	 */
	public function upload($file)
	{
		list($typeName, $this->extension) = explode('/', $file->type, 2);
		if (static::typeNameExists($typeName) == false)
		{
			throw new FileUploadException('This type of file is not supported');
		}
		$this->type = static::getTypeNumber($typeName);

		$repeated = static::findFile(md5_file($file->tempName), $this->type);
		if ($repeated != null)
		{
			$this->repeated($repeated);
		}


		switch ($typeName)
		{
			case 'image':
				return $this->uploadImage($file) && $this->save();
				break;
			case 'video':
				return $this->uploadVideo($file) && $this->save();
				break;
			default:
				throw new FileUploadException('This type of file is not supported');
		}
	}

	/**
	 * @param UploadedFile $file
	 * @return bool
	 * @throws \yii\base\Exception
	 * @throws Exception
	 */
	private function uploadImage($file)
	{
		$this->extension = $this->extension == 'gif' ? 'gif' : 'jpeg';

		$tempRandomName = $this->getTempRandomPath();

		$image = Image::getImagine()->open($file->tempName);
		list($imgHeight, $imgWidth) = [$image->getSize()->getHeight(), $image->getSize()->getWidth()];
		$scale = min(max($imgHeight, $imgWidth) / 1000, 1);
		$image->resize($image->getSize()->scale($scale))
			->save($tempRandomName, ($this->extension == 'gif' ? ['animated' => true] : ['flatten' => true]));

		$this->md5 = md5_file($tempRandomName);
		$repeated = static::findFile($this->md5, $this->type);
		if ($repeated != null)
		{
			return unlink($tempRandomName) && $this->repeated($repeated);
		}
		return rename($tempRandomName, $this->getFilePath());
	}

	/**
	 * @param UploadedFile $file
	 * @return bool
	 * @throws \yii\base\Exception
	 * @throws Exception
	 */
	private function uploadVideo($file)
	{
		if ($file->size > 40000000)
		{
			throw new FileUploadException('Video file is too big');
		}
		$this->md5 = md5_file($file->tempName);

		return $file->saveAs($this->getFilePath());
	}

	/**
	 * @param File $file
	 * @return bool false if identical
	 */
	private function repeated($file)
	{
		$this->md5 = $file->md5;
		$this->type = $file->type;
		$this->extension = $file->extension;

		return ($this->source_id !== $file->source_id);
	}
}
