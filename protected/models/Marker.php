<?php

/**
 * This is the model class for table "tbl_markers".
 *
 * The followings are the available columns in table 'tbl_markers':
 * @property integer $id
 * @property integer $user_id
 * @property string $latlng
 * @property string $name
 */
class Marker extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_markers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, latlng, name', 'required'),
            array('name', 'unique'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, latlng, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'latlng' => 'Latlng',
			'name' => 'Name',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
    public function limitted($u_id,$limit=5,$offset=0){

        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'user_id = '.$u_id,
            'limit'=>$limit,
            'offset'=>$offset,
        ));

        return $this;
    }
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('latlng',$this->latlng,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Marker the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    public function beforeSave(){
        if(parent::beforeSave()) {
            $this->latlng = serialize($this->latlng);
            return true;
        }
        else
        {
            return false;
        }

    }
    public function afterFind(){
        if(parent::beforeSave()) {
            $this->latlng = unserialize($this->latlng);
            return true;
        }
        else
        {
            return false;
        }
    }
}
