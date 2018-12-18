<?php

class Helper
{
    //Constructor with DB
    // public function __construct($db){
    //     $this->conn = $db;
    // }
    public function cleanValue($value)
    {
        return isset($value) ? htmlspecialchars(strip_tags($value)) : null;
    }

    public function getItemsHelper($arr_item)
    {
        $list_items = array();
        foreach ($arr_item as $item) {
            $list_item = array(
                "ItemSKU" => isset($item->strItemSKU) ? $this->cleanValue($item->strItemSKU) : null,
                "ItemDeclareType" => isset($item->strItemDeclareType) ? $this->cleanValue($item->strItemDeclareType) : null,
                "ItemName" => isset($item->strItemName) ? $this->cleanValue($item->strItemName) : null,
                "Specifications" => isset($item->strItemSpecifications) ? $this->cleanValue($item->strItemSpecifications) : null,
                "ItemQuantity" => isset($item->numItemQuantity) ? $this->cleanValue($item->numItemQuantity) : null,
                "ItemBrand" => isset($item->strItemBrand) ? $this->cleanValue($item->strItemBrand) : null,
                "ItemUnitPrice" => isset($item->numItemUnitPrice) ? $this->cleanValue($item->numItemUnitPrice) : null,
                "PreferentialSign" => isset($item->strIsDiscounted) ? $this->cleanValue($item->strIsDiscounted) : null,
            );

            array_push($list_items, $list_item);
        }
        return $list_items;
    }

    public function getTrackingListHelper($trackingList)
    {
        $formated_list = array();
        foreach ($trackingList as $list_item) {
            $new_node = array();
            $new_node['location'] = isset($list_item->TrackLocation) ? $this->cleanValue($list_item->TrackLocation) : null;
            $new_node['time'] = isset($list_item->TrackTime) ? $this->cleanValue($list_item->TrackTime) : null;
            $new_node['status'] = $this->translateStatus($list_item->TrackStatusCode);
            array_push($formated_list, $new_node);
        }
        return $formated_list;
    }

    private function translateStatus($code)
    {
        switch ($code) {
            case 'PU':
                return "The goods have been taken from the sender";
            case 'CL':
                return "Site collection";
            case 'AO':
                return "arrived oversea warehouse";
            case 'OC':
                return "operation complete";
            case 'LO':
                return "leave oversea warehouse";
            case 'FT':
                return "departure";
            case 'FL':
                return "arrived";
            case 'TRM':
                return "Being sent to customs clearance port";
            case 'CCE':
                return "clearance port complete";
            case 'OK':
                return "Delivery Complete";
            case 'CP':
                return "await";
            case 'CCMC':
                return "product lost";
            case 'CCSD':
                return "The goods have been destroyed";
            case 'HC':
                return "Customs fastener";
            case 'IDCS':
                return "ID card information collection";
            case 'IS':
                return "Handed over domestic delivery service provider";
            case 'PL':
                return "Internal operation of the operation center";
            case 'PO':
                return "Overseas warehouse made orders";
            case 'RT':
                return "The goods have been returned to the place of delivery";
            case 'SD':
                return "Damaged goods";
            case 'SH':
                return "Temporary deduction of goods";
            case 'PTW':
                return "The parcel is taken from the airport and transferred to the customs supervision warehouse.";
            case 'WA':
                return "Waiting to arrange a flight";
            case 'WT':
                return "Waiting for a transfer";
            case "WD":
                return "Waiting for customs clearance";
            default:
                return "unkown status";
        }

    }
    public function getAuexToken()
    {
        $url = "http://aueapi.auexpress.com/api/token";
        // 登录ID：2742
        // 密码：A09062742
        $member_id = "2742";
        $password = "A09062742";

        $data_arr = array("member_id" => $member_id, "password" => $password);

        $data_string = json_encode($data_arr);

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

        $curl_response = curl_exec($curl);

        curl_close($curl);

        return $curl_response->Token;
    }

    public function CQCHSCreateString($data)
    {
        $wsdl = "http://www.zhonghuan.com.au:8085/API/cxf/au/recordservice?wsdl";
        $client = new SoapClient($wsdl, array('trace' => 1));
        $receiverAddress = $data->strReceiverProvince . $data->strReceiverProvince . $data->strReceiverDistrict . $data->strReceiverDoorNo;
        $stock = "<ydjbxx>";
        $stock .= "<chrusername>0104</chrusername>";
        $stock .= "<chrstockcode>au</chrstockcode>";
        $stock .= "<chrpassword>123456</chrpassword>";
        // $stock.="<chryyrmc>2082</chryyrmc>";
        // $stock.="<chrzydhm>160-91239396</chrzydhm>";
        // $stock.="<chrhbh>CX110/CX052</chrhbh>";
        // $stock.="<chrjckrq>2015-06-25</chrjckrq>";
        $stock .= "<chrzl>$data->strOrderWeight</chrzl>";
        $stock .= "<chrsjr>$data->strReceiverName</chrsjr>";
        $stock .= "<chrsjrdz>$receiverAddress</chrsjrdz>";
        $stock .= "<chrsjrdh>$data->strReceiverMobile</chrsjrdh>";
        $stock .= "<chrjjr>$data->strSenderName</chrjjr>";
        $stock .= "<chrjjrdh>$data->strSenderMobile</chrjjrdh>";
        $stock .= "<chrsfzhm>352227198407180525</chrsfzhm>";
        $stock .= "<ydhwxxlist>";
        $stock .= "<ydhwxx>";
        $stock .= $this->CQCHSItemList($data);
        $stock .= "</ydhwxx>";
        $stock .= "</ydhwxxlist>";
        $stock .= "</ydjbxx>";

        return $stock;
    }

    private function CQCHSItemList($data)
    {
        $list_items_string = "";
        if (isset($data->items) && count($data->items) > 0) {
            foreach ($data->items as $item) {
                $list_items_string .= "<chrpm>$item->strItemName</chrpm>";
                $list_items_string .= "<chrpp>$item->strItemBrand</chrpp>";
                $list_items_string .= "<chrggxh>$item->strItemSpecifications</chrggxh>";
                $list_items_string .= "<chrjz>$item->numItemUnitPrice</chrjz>";
                $list_items_string .= "<chrjs>$item->numItemQuantity</chrjs>";
            }
        }
        return $list_items_string;
    }

    public function getTrackingListCQCHS($data, $kdgsname)
    {
        $formated_list = array();
        $flag = json_encode($kdgsname);
        if ($flag != "{}") {
            foreach ($data as $list_item) {
                $new_node = array();
                $new_node['location'] = "";
                $new_node['time'] = $list_item->time;
                $new_node['status'] = $list_item->ztai;
                array_push($formated_list, $new_node);
            }
        } else {
            $new_node = array();
            $new_node['location'] = "";
            $new_node['time'] = $data->time;
            $new_node['status'] = $data->ztai;
            array_push($formated_list, $new_node);
        }
        return $formated_list;
    }
}
