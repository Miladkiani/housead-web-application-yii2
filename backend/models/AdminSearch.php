<?php


namespace backend\models;


use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AdminSearch extends User
{

    public function rules()
    {
        return [
            [['id','status'],'integer'],
            [['first_name','last_name','username','email','phone','role.item_name'],'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios(); // TODO: Change the autogenerated stub
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),
            ['avatar.image_web_filename','role.item_name']);
    }

    public function search($params){
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query'=>$query
        ]);


        $query->joinWith(['role'=>function($query){
            $query->from(['role'=>'auth_assignment']);
        }]);

        $dataProvider->sort->attributes['role.item_name'] = [
            'asc' => ['role.item_name' => SORT_ASC],
            'desc' => ['role.item_name' => SORT_DESC],
        ];



        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['like', 'first_name', $this->first_name]);
        $query->andFilterWhere(['like', 'last_name', $this->last_name]);
        $query->andFilterWhere(['like', 'username',$this->username]);
        $query->andFilterWhere(['like','email',$this->email]);
        $query->andFilterWhere(['like', 'phone',$this->phone]);
        $query->andFilterWhere(['role.item_name'=>$this->getAttribute('role.item_name')]);
        return $dataProvider;
    }

}