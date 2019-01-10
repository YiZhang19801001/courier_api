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
            $new_node['location'] = isset($list_item->TrackLocation) ? $this->cleanValue($list_item->TrackLocation) : "";
            $new_node['time'] = isset($list_item->TrackTime) ? $this->cleanValue($list_item->TrackTime) : "";
            $new_node['status'] = $this->translateStatus($list_item->TrackStatusCode);
            array_push($formated_list, $new_node);
        }
        return $formated_list;
    }

    public function getDateTime()
    {
        $tz = 'Australia/Sydney';
        $timestamp = time();
        $dt = new DateTime("now", new DateTimeZone($tz));
        $dt->setTimestamp($timestamp);
        $logRowTime = $dt->format('d-m-Y, H:i:s');
        $logFileDate = $dt->format('d-m-Y');
        return json_decode(json_encode(array('time' => $logRowTime, 'date' => $logFileDate)));
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
        try {
            $client = new SoapClient($wsdl, array('trace' => 1));
        } catch (\Throwable $th) {
            echo 'not good';
        }
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

        if (is_array($data)) {
            foreach ($data as $list_item) {
                $new_node = array();
                $new_node['location'] = "";
                $new_node['time'] = isset($list_item->time) ? $list_item->time : "";
                $new_node['status'] = isset($list_item->ztai) ? $list_item->ztai : "";
                array_push($formated_list, $new_node);
            }
        } else {
            $new_node = array();
            $new_node['location'] = "";
            $new_node['time'] = isset($list_item->time) ? $list_item->time : "";
            $new_node['status'] = isset($list_item->ztai) ? $list_item->ztai : "";

            array_push($formated_list, $new_node);
        }
        return $formated_list;
    }

    public function AUEXCreateArray($data_raw)
    {
        $request_array = array(
            "OrderId" => isset($data_raw->strOrderNo) ? $this->cleanValue($data_raw->strOrderNo) : "",
            "MemberId" => 2742,
            "BrandId" => 1,
            // "TerminalCode" => isset($data_raw->strShopCode) ? $Helper->cleanValue($data_raw->strShopCode) : null,
            "SenderName" => isset($data_raw->strSenderName) ? $this->cleanValue($data_raw->strSenderName) : "",
            "SenderPhone" => isset($data_raw->strSenderMobile) ? $this->cleanValue($data_raw->strSenderMobile) : "",
            "SenderProvince" => isset($data_raw->strSenderProvinceName) ? $this->cleanValue($data_raw->strSenderProvinceName) : "",
            "SenderCity" => isset($data_raw->strSenderCityName) ? $this->cleanValue($data_raw->strSenderCityName) : "",
            "SenderAddr1" => isset($data_raw->strSenderAddress) ? $this->cleanValue($data_raw->strSenderAddress) : "",
            "SenderPostCode" => isset($data_raw->strSenderPostCode) ? $this->cleanValue($data_raw->strSenderPostCode) : "",
            // "ItemDeclareCurrency" => isset($data_raw->strItemCurrency) ? $Helper->cleanValue($data_raw->strItemCurrency) : null,
            "ReceiverName" => isset($data_raw->strReceiverName) ? $this->cleanValue($data_raw->strReceiverName) : "",
            "ReceiverPhone" => isset($data_raw->strReceiverMobile) ? $this->cleanValue($data_raw->strReceiverMobile) : "",
            // "CountryISO2" => isset($data_raw->strCountryISO2) ? $Helper->cleanValue($data_raw->strCountryISO2) : null,
            "ReceiverProvince" => isset($data_raw->strReceiverProvince) ? $this->cleanValue($data_raw->strReceiverProvince) : "",
            "ReceiverCity" => isset($data_raw->strReceiverCity) ? $this->cleanValue($data_raw->strReceiverCity) : "",
            // "District" => isset($data_raw->strReceiverDistrict) ? $Helper->cleanValue($data_raw->strReceiverDistrict) : null,
            "ReceiverAddr1" => isset($data_raw->strReceiverDoorNo) ? $this->cleanValue($data_raw->strReceiverDoorNo) : "",
            "ReceiverEmail" => "",
            "ReceiverCountry" => "",
            "ReceiverPostCode" => "",
            "ReceiverPhotoId" => isset($data_raw->strReceiverIDNumber) ? $this->cleanValue($data_raw->strReceiverIDNumber) : "",
            // "ConsigneeIDFrontCopy" => isset($data_raw->strReceiverIDFrontCopy) ? $Helper->cleanValue($data_raw->strReceiverIDFrontCopy) : null,
            // "ConsigneeIDBackCopy" => isset($data_raw->strReceiverIDBackCopy) ? $Helper->cleanValue($data_raw->strReceiverIDBackCopy) : null,
            "ChargeWeight" => isset($data_raw->strOrderWeight) ? $this->cleanValue($data_raw->strOrderWeight) : "",
            // "WeightUnit" => isset($data_raw->strWeightUnit) ? $Helper->cleanValue($data_raw->strWeightUnit) : null,
            // "EndDeliveryType" => isset($data_raw->strEndDelivertyType) ? $Helper->cleanValue($data_raw->strEndDelivertyType) : null,
            // "InsuranceTypeCode" => isset($data_raw->strInsuranceTypeCode) ? $Helper->cleanValue($data_raw->strInsuranceTypeCode) : "",
            // "InsuranceExpense" => isset($data_raw->numInsuranceExpense) ? $Helper->cleanValue($data_raw->numInsuranceExpense) : null,
            // "TraceSourceNumber" => isset($data_raw->strTraceNumber) ? $Helper->cleanValue($data_raw->strTraceNumber) : null,
            // "Marks" => isset($data_raw->strRemarks) ? $Helper->cleanValue($data_raw->strRemarks) : "",
            "ShipmentContent" => $this->getAuexItemsHelper(isset($data_raw->items) ? $data_raw->items : ""),
            "ShipmentCustomContent" => "",
            "Value" => "",
            "IsPaid" => "",
            "PayTime" => "",
            "Marks" => "",
            "Volume" => "",
            "Notes" => "",
            "OrderTime" => "",
            "ShipmentStatus" => "",

        );

        return $request_array;
    }

    public function getAuexItemsHelper($items)
    {
        $content_string = "";
        if (isset($items) && count($items) > 0) {
            foreach ($items as $item) {
                $orderItem = isset($item->strItemName) ? $item->strItemName : "";
                $quantity = isset($item->numItemQuantity) ? $item->numItemQuantity : "";
                $newOrderItem = $orderItem . '*' . $quantity;
                $content_string .= $newOrderItem;
            }
        }

        return $content_string;

    }

    public function getAuexTrackingList($trackList)
    {
        $formated_list = array();
        if (count($trackList) > 0) {
            foreach ($trackList as $trackListItem) {
                $new_node = array();
                $new_node['location'] = isset($trackListItem->Location) ? $this->cleanValue($trackListItem->Location) : "";
                $new_node['time'] = isset($trackListItem->StatusTime) ? $this->cleanValue($trackListItem->StatusTime) : "";
                $new_node['status'] = isset($trackListItem->StatusDetail) ? $this->cleanValue($trackListItem->StatusDetail) : "";

                array_push($formated_list, $new_node);
            }
        }
        return $formated_list;
    }
}
