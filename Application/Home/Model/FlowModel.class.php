<?php
namespace Home\Model;
use Think\Model;

class FlowModel extends Model {
    // �����Զ����
    protected $_auto    =   array(
        array('create_time','time',1,'function'),
    );
}