<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
    // �����Զ���֤
    protected $_validate    =   array(
        array('phone','require','�绰�������'),
    );
    // �����Զ����
    protected $_auto    =   array(
        array('create_time','time',1,'function'),
    );
}