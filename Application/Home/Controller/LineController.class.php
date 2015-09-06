<?php/** * Created by PhpStorm. * User: xzjs * Date: 15/8/18 * Time: 下午9:56 */namespace Home\Controller;use Think\Controller;class LineController extends Controller{    public function read($id){        //$this->test($no);        $Line=D("Line");        $line=$Line->relation(true)->find($id);        //var_dump($line);        $str='{"name":"'.$line['Tline']['name'].'","start_time":"'.date("H:i:s",strtotime($line["start_time"])).'","end_time":"'.date("H:i:s",strtotime($line["end_time"])).'","type":"'.$line['Tline']['type'].'","price":"'.$line['Tline']['price'].'","stations":[';        $LS=M("Line_station");        foreach ($line['Station'] as $station) {            $str.='{"id":'.$station['id'].',"name":"'.$station['name'].'","sort":'.$LS->where('line_id='.$id.' and station_id='.$station['id'])->getField('sort').'},';        }        $str=substr($str,0,-1);        $str.=']}';        echo $str;    }    public function index(){        echo 'hello';    }    //填充测试数据    private function test($i){        $Line=M("Line");        $data=$Line->where("line_no=$i")->find();        if($data){            $Bus=M("Bus");            //获取所有的公交车信息            $str='line_id='.$data['id'];            $busdatas=$Bus->where($str)->select();            foreach($busdatas as $busdata){                //修改公交车的位置信息                $Bus->position_x=$busdata['position_x']+5*$busdata['direction'];                $Bus->position_y=$busdata['position_y']+5*$busdata['direction'];                //到达终点站变向                if($Bus->position_x==40||$Bus->position==10){                    $Bus->direction=-1*$busdata['direction'];                }                $Station=M("Station");                //获得所有的站点信息                $station_datas=$Station->where('line_id='.$data['id'])->select();                //计算站点位置和公交位置确定公交车在什么地方                foreach ($station_datas as $station_data) {                    if(abs($Bus->position_x-$station_data['position_x'])<1){                        $Bus->station_id=$station_data['id'];                        $Bus->time=date("y-m-d h:i:sa");                        break;                    }                }                $Bus->where('id='.$busdata['id'])->save();            }        }    }    /**     * 公交模糊搜索     * @param $key 查询的关键字     */    public function search($key){        $Tline=D('Tline');        $tlines=$Tline->relation(true)->where("name like '$key%'")->select();        //var_dump($tlines);        $data_stations=array();        foreach ($tlines as $tline) {            foreach ($tline['Line'] as $line) {                $data_station=array(                    "id"=>$line['id'],                    "name"=>$tline['name'],                    "start_station"=>$line['start_station'],                    "end_station"=>$line['end_station']                );                array_push($data_stations,$data_station);            }        }        $data['lines']=$data_stations;        echo json_encode($data);    }    /**     * 用户点击站点后返回制定数量的公交车     * @param $lid 线路id     * @param $sid 用户点击的站得id     * @param $num 用户需要的数量     */    public function station($lid,$sid,$num){        $Line_station=M('Line_station');        $ls=$Line_station->where("line_id=$lid and station_id=$sid")->find();        $Bus=D("Bus");        $bs=$Bus->relation(true)->where("line_id=$lid and sort<".$ls['sort'])->order('sort')->limit($num)->select();        //var_dump($bs);        $data_buses=array();        $str='{"bus":[';        foreach ($bs as $b) {            $data_bus=array(                "no"=>$b['no'],                "station_id"=>$b["station_id"],                "time"=>$b['time']            );            array_push($data_buses,$data_bus);        }        $data["bus"]=$data_buses;        echo json_encode($data);    }}