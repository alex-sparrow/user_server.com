<?php

/**
 * This is the model class for table "tbl_session_id".
 *
 * The followings are the available columns in table 'tbl_session_id':
 * @property integer $id
 * @property integer $user_id
 * @property string $session_key
 * @property string $exp_date
 */
class SessionId extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_session_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, session_key, exp_date', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('session_key', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, session_key, exp_date', 'safe', 'on'=>'search'),
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

    public function findActive($s_key){ //function set conditions to find active Session with set session key

        $c_time = time();

        $this->getDbCriteria()->mergeWith(array(
            //'condition'=>"session_key = '".$s_key."' AND exp_date < ".$c_time,
			'condition'=>"session_key = '".$s_key."'",
        ));
        return $this;
    }

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'session_key' => 'Session Key',
			'exp_date' => 'Exp Date',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('session_key',$this->session_key,true);
		$criteria->compare('exp_date',$this->exp_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SessionId the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
