<?php
/**
 * Created by PhpStorm.
 * User: Childe
 * Date: 2015/8/12
 * Time: 10:19
 */
namespace Home\Controller;

use Think\Controller;

class FlowController extends Controller
{
    /**
     * @param $user_id �����������û�id
     * @param $flow_num ��������������Ŀ
     */
    public function add($user_id, $flow_num)
    {
        try {
            $Flow = M("Flow");
            $result = $Flow->where('user_id=' . $user_id)->find();

            $result["flow_num"] += $flow_num;
            $Flow->save($result);
            echo json_encode("true");
        } catch (Exception $e) {
            echo json_encode("false");
        }

    }

    /**
     * @param $user_id �û�id
     */
    public function read($user_id)
    {
        $Flow = M("Flow");
        $result = $Flow->where('user_id=' . $user_id)->find();
        echo json_encode($result);
    }
}