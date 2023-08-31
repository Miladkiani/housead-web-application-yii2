<?php


namespace backend\rbac;
use yii\db\Query;
use yii\rbac\Rule;

class AuthorOrByEmployeeRule extends Rule
{
    public $name = "isAuthorOrByEmployee";
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['post'])){
            $author_id = $params['post']->author_id;
            if($author_id == $user){
                return true;
            }else{
                $query = (new Query())
                    ->select('item_name')
                    ->from('auth_assignment')
                    ->where('user_id = :author_id',[':author_id'=>$author_id])
                    ->andwhere(['item_name'=>'employee']);
                   $row = $query->count();
                        if ($row>0){
                            return true;
                        }
            }
        }
        return false;
    }
}