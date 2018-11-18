<?php
class Helper{
    //Constructor with DB
    // public function __construct($db){
    //     $this->conn = $db;
    // }
    public function cleanValue($value){
        return isset($value)?htmlspecialchars(strip_tags($value)):null;
    }

    public function getItemsHelper($arr_item){
        $list_items = array();
        foreach ($arr_item as $item) {
            $list_item = array(
                "ItemSKU"=> isset($item->strItemSKU)?$this->cleanValue($item->strItemSKU):null,
                "ItemDeclareType"=> isset($item->strItemDeclareType)?$this->cleanValue($item->strItemDeclareType):null,
                "ItemName"=> isset($item->strItemName)?$this->cleanValue($item->strItemName):null,
                "Specifications"=> isset($item->strItemSpecifications)?$this->cleanValue($item->strItemSpecifications):null,
                "ItemQuantity"=> isset($item->numItemQuantity)?$this->cleanValue($item->numItemQuantity):null,
                "ItemBrand"=> isset($item->strItemBrand)?$this->cleanValue($item->strItemBrand):null,
                "ItemUnitPrice"=>isset($item->numItemUnitPrice)?$this->cleanValue($item->numItemUnitPrice):null,
                "PreferentialSign"=> isset($item->strIsDiscounted)?$this->cleanValue($item->strIsDiscounted):null
            );

            array_push($list_items,$list_item);   
        }
        return $list_items;
    }

    public function getTrackingListHelper($trackingList){
        $formated_list = array();
        foreach ($trackingList as $list_item) {
            $new_node=array();
            $new_node['location'] = isset($list_item->TrackLocation)?$this->cleanValue($list_item->TrackLocation):null;
            $new_node['time'] = isset($list_item->TrackTime)?$this->cleanValue($list_item->TrackTime):null;
            $new_node['status'] = $this->translateStatus($list_item->TrackStatusCode);
            array_push($formated_list,$new_node);
        }
        return $formated_list;
    }

    private function translateStatus($code){
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
}