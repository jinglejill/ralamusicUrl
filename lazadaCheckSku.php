TE-960821001
TE-960700001
TE-060807001
TE-960801001
TE-960840001
TE-960661001
TE-GO-XLR
TE-996130001
TE-DHQ0000010
TE-960814001
TE-964100002
TE-996016001
TE-996000905
TE-996006005
TE-996012005
TE-960816001
ADVANCED-ACCESSPORT-AIR-Wireless
ADVANCED-Alpha-Planar-Magnetic-Headphones
ADVANCED-ELISE
ADVANCED-MODEL-2
x MODEL-3-BA2
ADVANCED-Custom-M5-5D-(UIEM)
Advanced-M5-TWS-CUSTOM-green
ADVANCED-Evo-X-Sports-Wireless-In-ear
ADVANCED-M5-TWS-black
ADVANCED-Model3
ADVANCED-MODEL-X-True-Wireless-Earbuds
ADVANCED-Model-X-Supercharged
x FIFINE-MICROPHONE-T669
x FIFINE-K669B-USB-Microphones Details Gaming-experience-is-off-the-scale
x FIFINE-K678-USB-Microphone Features Setup-with-ease-and-control-of-a-pro
radius-HP-DME03K
radius-HP-DME04K-(XS)
Radius SP-S10BT-black
Radius SP-S10BT-orange
Radius SP-S10BT-white
radius-HC-SPC25K
Radius-HC-M100BTK
radius-HP-N200BT
Radius-HP-NEF21
Radius-HP-NEF31
radius-HP-NHA21
radius-HP-NHR11
radius-HP-NHR21
radius-HP-NHR31
radius-HP-NX100
radius-HP-TWF00
Radius-HP-E50BT-Dark-blue
Radius-HP-E50BT-Green
Radius-HP-G200BT-Red
radius-HP-N100BT-Black
radius-HP-N100BT-Red
radius-HP-N100BT-White
radius-HP-N200BT-Red
radius-HP-N300BT
Radius-HP-T50BT-Gold
x Radius-HP-T50BT-Red



FIFINE-K669B-USB-Microphones Details Gaming-experience-is-off-the-scale
<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    set_time_limit(600);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    
    $limit = $_GET["limit"];
    $page = $_GET["page"];
//    $sql = "select * from lazadaProduct where ProductID > ($page-1)*$limit order by ProductID limit $limit";
    
    $sql = "select * from scrapeProductTest";
    $selectedRow = getSelectedRow($sql);
    
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
//        $productID = $selectedRow[$i]["ProductID"];
        $sku = $selectedRow[$i]["Sku"];
        

        $ret = hasLazadaProduct($sku);
        if(!$ret)
        {
            echo "<br>",$sku;
        }
    }
?>
