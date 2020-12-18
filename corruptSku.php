<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSICTEST");
    ini_set("memory_limit","50M");
    set_time_limit(600);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();

    $filenameList = ["Tc-Electronic-Go-Guitar-Pro","Tc-Electronic-Go-Twin","Tc-Electronic-PolyTune-3-Mini-Noir","Yamaha-CS3","Yamaha-TMS1465-Candy-Apple-Satin","Yamaha-TMS1465-Chocolate-Satin","Yamaha-TRBX174-DBM","Yamaha-TRBX305-MGR","AKAI-Fire-Midi-Controller","AKAI-MPK-mini-MKII-BK","AKAI-MPK-mini-MKII","AROMA-AG10A-FCS","AROMA-AG10A","Arborea-HR14H","Arborea-HR18","Arborea-HRMG16","Arborea-HRMG18","Arborea-HRMG20","Arborea-VK14","Aroma-AG15A","Aroma-AH85","BJ2","BM-32KH-BL","BOSS-FV-50L","BOSS-GT1-FPSA230S","BOSS-Kanata-Artist","BOSS-OD-3","BOSS-PSA-230S","BOSS-RC-505","BOSS-VE-20-FAD","BOSS-VE-500","BOSS-VO-1","BOSS-WAZA-AIR","Baracuda-OM-300EQ-S1","Belcat-BT-101BK","Belcat-DST501-FAD","Belcat-IRIS2","Belcat-PST-10","Belcat-SH-85","Bespeco-SH12NE","Beyerdynamic-DT-770-Pro-250Ohm","Blackstar-FS-11","Blackstar-ID-Core-Stereo-10","Blackstar-ID-Core-Stereo-150","Blackstar-ID-Core-Stereo-40","Blackstar-LT-Echo-10","Blake-GCJ601","Blake-LSB100","Boss-BC-2","Boss-EQ-200","Boss-GT-1B","Boss-OS2","Boss-SY-1","Boss-TU-3W","Bullet-AC45R-BR","Bullet-BB20BK","Bullet-BT-150R-212-BK","Bullet-BT20-WH","Bullet-DA15T-BK","Bullet-DA15T-WH","Bullet-DA20T-BK-REVB","Bullet-DA20T","CAD-Audio-AS16","CARLSBRO-CSD110P","CARLSBRO-CSD120-EDA30","CARLSBRO-CSD210-EDA30","Caline-Scuru-S1-Mini-Bass-Amp","Carlsbro-BC334-2M","Carlsbro-BC748-3M","Carlsbro-BJJ012T-3M","Carlsbro-BJJ032-6M","Carlsbro-BJJ033B-6M","Carlsbro-BJJ033T-3M","Carlsbro-BXJ007A-10M","Carlsbro-BXJ007A-5M","Carlsbro-BXJ013A-10M","Carlsbro-BXJ016A-10M","Carlsbro-BXX001A-10M","Carlsbro-CC310","Carlsbro-CC321","Carlsbro-CSD110-EDA50","Carlsbro-CSD110-FCSS1","Casio-PX160","Centent-TD-12S","Centent-TD-18C","Centent-XTT-16C","Centent-XTT-17CH","Centent-XTT-17C","Centent-XTT-17Z","Centent-XTT-18CH","Centent-XTT-18C","Centent-XTT-19C","Century-CB-22-5-SB-S2","Century-CB-22-BK-S2","Century-CB-22-BU-S2","Century-CB-22-IV-S2","Century-CE-A38-BK-S2","Century-CE-A38-IV-S2","Century-CE-A38-LH-BK-S2","Century-CE-A38-LH-SB-S2","Century-CE-A38-SB-S2","Century-CE-A38-SC-S2","Century-CE-A384-BK-Metallic-Black-S2","Century-CE-A384-BN-Metallic-Brown-S2","Century-CE-A384-MRD-Metallic-Red-S2","Cherub-DT20","Clevan-C10","Clevan-CBJ5-30-MD40BJ","Clevan-CTD20-BK","DR-Strings-FB40-DATDR-1FB40","DR-Strings-UMCSC-DATDR-3UMCSC","DW-5002","DW-9002P-P","Daddario-50BAL01","Daddario-50BAL02","Daddario-50BAL03","Daddario-50BAL06","Daddario-American-Stage-PW-AMSG-20","Daddario-American-Stage-PW-AMSGRA-20","Daddario-Core-BL","Daddario-Core-BR","Daddario-Core-YL","Daddario-EXL165TP","Daddario-EXL170TP","Daddario-EXL170","Daddario-EXL220","Daddario-EXL230","Daddario-EXP10","Daddario-EXP170-5","Daddario-EXP220","Daddario-EZ890-REVB","Daddario-EZ890","Daddario-EZ920","Daddario-EZ940","Daddario-Guitar-Rest-PW-GR-01","Daddario-Lock-Strap-50PLA02","Daddario-Lock-Strap-50PLA04","Daddario-Lock-Strap-50PLA05","Daddario-XTE0946","Daddario-XTE1046","Daddario-XTE1052","DaddarioEZ890-MD100BK-P039-POS-Pick","Danchoo-DFR-1-PK","Dandrea-Lemon-Oil-DCLDA-DAL2","ESI-U22-XT-cosMik-Set","Echoslap-DECS-EGGWOOD","Echoslap-GFX-LOVE","Epiphone-Les-Paul-SL-PBL-EB-ENOLPACH1","Epiphone-Les-Paul-SL-VSB-ENOLVSCH1","Epiphone-Les-Paul-SL-YS-ENOLSYCH1","Epiphone-PRO-1-BK-EAPREBCH1","Epiphone-PRO-1-N-EAPRNACH1-SET","Epiphone-PRO-1-SB-EAPRVSCH1","Epiphone-Pro-1-Classic","Ernie-Ball-Braided-Cable-Black-Neon-Orange-P06067","Ernie-Ball-Braided-Cable-Red-Blue-White-P06063","Ernie-Ball-Classic-Jacquard-P04142","Ernie-Ball-Classic-Jacquard-P04167","Ernie-Ball-Classic-Jacquard-P04667","Ernie-Ball-Cobalt-Regular-Slinky-P02721","Ernie-Ball-Coiled-Cable-White","Ernie-Ball-Ernesto-Palla-Black-and-Silver-P02406","Ernie-Ball-Hybrid-Slinky-Bass-P02833","Ernie-Ball-Mega-Slinky-P02213","Ernie-Ball-Not-Even-Slinky-P02626","Ernie-Ball-P02021-Paradigm-Regular-Slinky","Ernie-Ball-P02086-Paradigm-Bronze-80-20-Medium-Light","Ernie-Ball-P02088-Paradigm-Bronze-80-20-Light","Ernie-Ball-P02090-Paradigm-Bronze-80-20-Extra-Light","Ernie-Ball-P02230","Ernie-Ball-Polypro-P04044-Rainbow","Ernie-Ball-Polypro-P04047-WR","Ernie-Ball-Polypro-P04048-GA","Ernie-Ball-Polypro-P04050-GE","Ernie-Ball-Polypro-P04052-BR","Ernie-Ball-Prodigy-Black-set-6-P09342","Ernie-Ball-Prodigy-Large-Shield-white-P09338","Ernie-Ball-Prodigy-Sharp-Black-P09335","Ernie-Ball-Prodigy-Sharp-white-P09341","Ernie-Ball-Prodigy-Shield-Black-P09331","Ernie-Ball-Prodigy-Teardrop-white-P09336","Ernie-Ball-Prodigy-white-set-6-P09343","Ernie-Ball-Prodigy-white-set-6-P09343_2.png","Ernie-Ball-Super-Glow-S1","Ernie-Ball-Super-Lock-BK-P04601","Ernie-Ball-Super-Slinky-2223","Ernie-Ball-Tele-Knobs-CR","Ernieball-Earthwood-Light-P02004","Evans-B14HBG","Evans-BD22HR","Evans-EPP-EC2SHDD-R","Evans-ETP-G2CTD-R","Fantasia-AW392N","Fantasia-C41BK-FAT200D","Fantasia-C41BK-FMB07","Fantasia-C41N-FMB07","Fantasia-C41N-S1","Fantasia-C41N","Fantasia-C41RD-FAT200D","Fantasia-C41RD-FMB07","Fantasia-C41RD","Fantasia-C41SB-FMB07","Fantasia-DGM-10CBK","Fantasia-DGM10CBS-FBA","Fantasia-EA12EBK","Fantasia-EA12EN-FBA","Fantasia-EA12EN","Fantasia-EA12ESB-FBA","Fantasia-EA12ESB","Fantasia-F80-BL-S1-REV","Fantasia-F80-BL-S1","Fantasia-QAG401GBK-S2","Fantasia-QAG401GN-S2","Fantasia-QAG411M-BK-S1","Fantasia-QAG411M-BK","Fantasia-QAG411M-N-S1","Fender-50th-Woodstock-Picks","Fender-65-Twin-Reverb-Amp","Fender-70CL","Fender-7250-5M","Fender-Acoustic-100","Fender-Acoustic-200","Fender-Blues-Deluxe-KG","Fender-Blues-Deville-Harmonica-Key-A","Fender-Blues-Deville-Harmonica-Key-C","Fender-Blues-Deville-Harmonica-Key-D","Fender-Blues-Deville-Harmonica-Key-G","Fender-CD140SCEN","Fender-CD140SCESB","Fender-CD60CEBK","Fender-CD60N","Fender-CD60S-BK-0961701006","Fender-CD60SCEBK","Fender-Electric-Bass-Gig-Bag-FB610","Fender-FAS-610","Fender-FC-100","Fender-FE620","Fender-FU610","Fender-Mahogany-Black-Top-Strat-HHH-Olympic-White","Fender-Malibu-California-Aqua-Splash-0970722008","Fender-Mustang-GT-200","Fender-Mustang-GTX50","Fender-Mustang-I-V2","Fender-Roadhouse-Strat-Olympic-White-0147302305","Fender-Roadhouse-Strat-Sunburst-0147300300","Fender-Rumble-100","Fender-Rumble-40","Fender-Rumble25","Fender-T-Bucket-300CESB-0968079021","Fender-T-Bucket-400CE","Fender-Turbo-Tune-String-Winder","Fender-Zuma-Concert-Ukulele-Lake-Placid-Blue-S1","Fender-Zuma-Concert-Ukulele-Lake-Placid-Blue","Fishman-INK300","Fishman-ISYS-301-PSY-BAA-AAA","Focusrite-Scarlett-Solo","GSMINI1FEQ","Gecko-K17BB","Gecko-K17BP","Gecko-K17K","Gecko-K17M","Gecko-MC-BL","Gibson-Les-Paul-Classic-2018-043-01286-EB","Gibson-Les-Paul-Classic-2018-043-01286-PB","Gibson-Les-Paul-Classic-Player-Plus-2018-043-01294-OB","Gibson-Les-Paul-Classic-T-2017-043-01280-1GT","Gibson-Les-Paul-Standard-50s-043-01406-HS","Gibson-Les-Paul-Standard-60s-043-01407-IT","Gibson-PRPR-015","Gibson-PRSK-010","Gibson-PRSK-020","Gibson-PRTK-030","Gibson-PRWA-020","Gibson-Regular-Style-Jet-Black-REVB","Golden-Cup-JYCL1301","Gretsch-G5022CBFE","Gretsch-G9531","Guitar-Bag-DCGT-41-LB","Guitar-Bag-DCGT-41-OR","Guitar-Bag-DCGT-41-PK","Guitto-GGC-02-SV","Guitto-GGS-04","HUN-HIC-2B-BK","HUN-HIC-5AFG-BLY","HUN-HIC-5AFG-RDY","HUN-HIC-5AFG-WH","HUN-HIC-5AFG-YL","HUN-HIC-5AST-GR","HUN-HIC-5AST-VL","HUN-HIC-5AST-YL","HUN-HIC-7AST-BL","HUN-HIC-7AST-RD","HUN-HIC-7AST-VL","Hohner-ACE48","Hohner-Amadeus","Hohner-Big-River-Harp-A","Hohner-Big-River-Harp-B","Hohner-Big-River-Harp-C","Hohner-Big-River-Harp-D","Hohner-Big-River-Harp-Set","Hohner-Bravo-III-80RD","Hohner-Bravo-III-96BK","Hohner-Echo-Tremolo","Hohner-Golden-Melody-Key-A","Hohner-Happy-Color-Harp-Blue","Hohner-Hot-Metal-D","Hohner-Hot-Metal-E","Hohner-KM1700","Hohner-Larry-Adler-48C","Hohner-Larry-Adler-64C","Hohner-MEISTERKLASSE","Hohner-MZ2010","Hohner-Marine-Band-1896-Bb","Hohner-Marine-Band-1896-C","Hohner-Marine-Band-1896-D","Hohner-Marine-Band-1896-E","Hohner-Marine-Band-1896-F","Hohner-Marine-Band-1896-G","Hohner-Meisterklasse-C","Hohner-Melody-Star","Hohner-Ocean-Star","Hohner-Ozzy-Osbourne","Hohner-Remaster-Vol-2-REVB","Hohner-Rocket-Amp-EN","Hohner-Rocket-Amp-KA","Hohner-Rocket-Amp-KE","Hohner-Rocket-Amp-KF","Hohner-Rocket-Amp","Hun-3SD-S5","Icon-RF-01","JOYO-JM-90","JOYO-JMD05","JOYO-JT01","JOYO-JW-01","Jackson-Adrian-Smith-SDX-Snow-White-2913054576","Jackson-JS1X-RR-Minion-Neon-Yellow-2913334504","Jackson-JS2-Spectra-Metallic-Blue-2919004527","Jackson-JS22-7-DKA-HT-2910132568","Jackson-JS22-Dinky-RW-BK","Jackson-JS22-Dinky-RW-BL","Jackson-JS22-Dinky-RW-N","Jackson-JS3-Spectra-Metallic-Red-2919904573","Jackson-JS32-7-DKA-HT-Snow-White-2910113576","Jackson-JS32-Dinky-DKA-Arch-Top-Black-Satin-2910248568","Jackson-JS32-Dinky-DKA-Arch-Top-Neon-Orange-2910148580","Jackson-JS32-Dinky-DKA-Arch-Top-Pavo-Purple-2910238552","Jackson-JS32-King-V-2910224577","Jackson-JS32T-BK-2910147586","JoJo-AW460SB","JoJo-AW760BL","JoJo-AW760N","Joyo-GEM-BOX-III","Joyo-I-Plug","Joyo-JBA100","Joyo-JBA35","Joyo-JPA-862","Junior-ST112T-N","KNA-AP-1","KNA-UP-1","KORG-LP-380-WH","KORG-microKEY-Air-25","Kawai-KDP110-INT","Kazoo-USA-ORG","Kazoo-USA-PKG","Kazoo-USA-RDV","Kazoo-USA-WH","Kazuk-BKZ-TLTW2","Kazuki-41DCE","Kazuki-41DCMG","Kazuki-41OME","Kazuki-AKZ-ALLSOULOME","Kazuki-All-Soul-D","Kazuki-BKZ-ST01SB","Kazuki-BKZ-ST01WH","Kazuki-CKZ-HS24","Kazuki-Chimes-DG-LCH25B","Kazuki-DB41BK-FBA41","Kazuki-DLKZ41CE-BK-S4","Kazuki-DLKZ41CE-N-S1","Kazuki-DLKZ41CE-N","Kazuki-DST-ST1","Kazuki-EOV41E-BK-S1","Kazuki-EOV41E-BK-S3","Kazuki-EOV41E-BK-S4","Kazuki-EOV41E-BK","Kazuki-EOV41E-BLS-S1","Kazuki-EOV41E-BLS-S3","Kazuki-EOV41E-BLS-S4","Kazuki-EOV41E-N-S1","Kazuki-KNY41CN-S1","Kazuki-KOV381CEN","Kazuki-KOV381CN","Kazuki-KZ30BK-S1","Kazuki-KZ30N-S1","Kazuki-KZ30N","Kazuki-KZ389CN-FMB05","Kazuki-KZ389CN","Kazuki-KZ38BL-REVB","Kazuki-KZ38C-BK-S1","Kazuki-KZ38C-BK","Kazuki-KZ38C-CS","Kazuki-KZ38C-N","Kazuki-KZ38C-SB-S1","Kazuki-KZ38CS-FMB05-S2","Kazuki-KZ38SB-FMB05-S2","Kazuki-KZ38SB-REVB","Kazuki-KZ38WR-S3","Kazuki-KZ38YW-FMB05-S2","Kazuki-KZ390BK","Kazuki-KZ390CBK-FBA39","Kazuki-KZ390CBK","Kazuki-KZ390CEBK-FBA39","Kazuki-KZ390CEN-FBA39","Kazuki-KZ390CN-FBA39","Kazuki-KZ39C-CS-S2","Kazuki-KZ39C-MG-S2","Kazuki-KZ39C-N-S2","Kazuki-KZ39C-SB-S2","Kazuki-KZ39C-SB-S3","Kazuki-KZ39C-WR-S3","Kazuki-KZ39CEBK-FBA39","Kazuki-KZ39CEN-FBA39","Kazuki-KZ39CESB","Kazuki-KZ409C-BK-S1","Kazuki-KZ409C-BK-S3","Kazuki-KZ409C-N-S1","Kazuki-KZ410BK","Kazuki-KZ410N-FBA41","Kazuki-KZ410N","Kazuki-KZ410SB-FBA41","Kazuki-KZ410SB","Kazuki-KZ41CCS-FBA","Kazuki-KZ41CCS-S1","Kazuki-KZ41CESB-FBA41","Kazuki-KZ41CESB-S1","Kazuki-KZ41CESB","Kazuki-KZ41CEWR-FBA41","Kazuki-KZ41CEWR-S1","Kazuki-KZ41CEWR","Kazuki-KZ41CN-FBA-Brown","Kazuki-KZ41CN","Kazuki-KZ41CSB-FBA","Kazuki-KZ41CWR-FBA41","Kazuki-KZ41CWR-FBA","Kazuki-KZ41CWR","Kazuki-KZ68CN","Kazuki-KZ900C","Kazuki-SOV41E-BLS-S1","Kazuki-SOV41E-BLS-S3","Kazuki-SOV41E-BLS-S4","Kazuki-SOV41E-BLS","Kazuki-SOV41E-N-S1","Kazuki-SOV41E-N-S4","Kazuki-SOV41E-N","Kazuki-SOV41E-RDS-S3","Kazuki-SOV41E-SB","Kazuki-WB-DBR","Line-6-Powercab-112-Plus","Line-6-Powercab-112","Line-6-Powercab-212-Plus","Line-6-Relay-G10T","Line-6-Relay-G10","Line-6-Relay-G50","Line-6-Spider-V-120-MkII","Line-6-Spider-V-20-MkII","Line-6-Spider-V-20","Line-6-Spider-V-30-MkII","Line-6-Spider-V-60-MkII","MEGA-GL15","MEGA-GL20","MEGA-GL30R","MEGA-GX100B","MEGA-GX10","MEGA-GX15R","MEGA-LN-GX35R","MEGA-TB62RS-RD","MOOER-GE100-N","MOOER-POGO","MOSRITE-GT-GRO","MOSRITE-GT-ORG","Mantic-AG-1CEL-S1","Mantic-AG-1CLH-Set-A","Mantic-AG-1CN-S2","Mantic-AG370-N-S2","Mantic-AM-1CBK","Mantic-AM-1CSB","Mantic-GT-10AC-N-S3","Mantic-GT-10DC-BK-S3","Mantic-GT-10DC-N-S3","Mantic-GT-10DC-SB-Full-Set","Mantic-MG-1C-SB","Marina-C101-20","Marina-C129-1-20","Marine-Band-Crossover-F","Marth-D400CBU","Marth-D400CGN","Marth-D90C","Martin-Lee-AMTL-M38B-BK-S1","Martin-Lee-AMTL-M38B-BK-S2","Martin-Lee-MD4145C-S2","Martin-Lee-Z-4016C-S1","Medeli-DD315-S1","Medeli-Electric-Pedal","Mooer-Red-Truck","Motion-E120-Super-Light-Set-009-042","Musedo-MC-1-BK","Musedo-MC-1-GD","Musedo-MC-5","Musedo-T-11","Musedo-T2","NA210-FBH","NA212","NAE312T","NAE412T","NE-314","NE212","NE311","NE312TM","NE312","NE410","NE412TR","NE413TM","NUX-Acoustic-30","NUX-MG-20","NUX-MG-300","NUX-MP1-Footswitch","NUX-MTCDL-Metal-Core-Deluxe","NUX-Mighty-Lite-BT","NUX-NAP5-Floor-Acoustic-Preamp","NUX-NDL-5","NUX-PLS-4-Four-Channel-Line-Switcher","NUX-TPCDL-Tape-Core-Deluxe","NUX-Tube-Man-MKII-NOD-2","Novation-Launchkey-37-MkIII","Novation-Launchkey-49-MKIII","Novation-Launchkey-61-MKII","Novation-Launchkey-Mini-MK3","Novation-Launchpad-Mini-MK3","OEM-NA210","OEM-NE110","OEM-T20","On-Stage-DSB6700","On-Stage-GS7140","On-Stage-GS7655","Orange-Crush-12","Orange-Crush-20RT","Orange-Crush-35RT","Orange-Crush-Bass-25","Pacifica212VFM-BK","Pacifica212VFM-CB","Paiste-Cleaner","Paramont-Pick-Mix-Set-A-BK","Paramont-Pick-Mix-Set-A-GD","Paramont-Pick-Mix-Set-A-GR","Paramont-Pick-Mix-Set-A-WH","Paramount-A2016-S1","Paramount-BL004CR","Paramount-BM37K-BL","Paramount-BOM403-S1","Paramount-BOM403-S2","Paramount-BOM403","Paramount-BOM406ET5-S1","Paramount-C28","Paramount-C33CEQN-S1","Paramount-C33CEQN","Paramount-C33CEQSB-S1","Paramount-C33CSB","Paramount-C33N","Paramount-CD60CM-S1","Paramount-CE-50","Paramount-CL39-S1","Paramount-CL39","Paramount-CTS-S","Paramount-Car-Racer-Danchoo-KFQ-32K-BL-REVB","Paramount-DE049","Paramount-DH108R","Paramount-DHM-NUTB4B","Paramount-DK8","Paramount-ED95-S2","Paramount-G3002","Paramount-G4N","Paramount-GS-Mini-7","Paramount-GSMINI1-S1","Paramount-GSMINI2FEQ","Paramount-GSMINI3-S1","Paramount-GSMINI3FEQ","Paramount-GSMINI5","Paramount-GSMINI6-S1","Paramount-GSMINI6","Paramount-GX50CEQ","Paramount-J01CR","Paramount-J07CR","Paramount-J112CEN","Paramount-J112CN","Paramount-J44CR","Paramount-JB38EN","Paramount-KSP11GD","Paramount-KSP13BK","Paramount-KSP21GD","Paramount-KST42BK","Paramount-KSV41GD","Paramount-KSV42BK","Paramount-LSW-30","Paramount-MA005VS","Paramount-MB25B","Paramount-MB25E","Paramount-MD100BR","Paramount-MD20U","Paramount-MD25TN","Paramount-MI-01-S1","Paramount-MI-01","Paramount-Melody-Danchoo-KFQ-32K-PK-REVB","Paramount-Melody-Danchoo-KFQ32K-PK","Paramount-NB001BK","Paramount-NB001CR","Paramount-NK100RG","Paramount-NK200MG","Paramount-NK200M","Paramount-NS002BK","Paramount-NT-EG","Paramount-PMM605","Paramount-PS-001","Paramount-PS-002","Paramount-PS012L","Paramount-QAG402G-S1","Paramount-QAG412G-S1","Paramount-QAG412G-S2","Paramount-QAG501-N-S1","Paramount-QAG501-W-S2","Paramount-QB-MB-15","Paramount-QZ04","Paramount-R206","Paramount-R208","Paramount-S450CE","Paramount-SH117R-MBL-S5","Paramount-SH117R-MRD-S5","Paramount-SH117R-MRD","Paramount-SH118R-MBK-S6","Paramount-SH118R-MBL-S6","Paramount-SH118R-MRD-S3","Paramount-SH118R-MRD-S6","Paramount-SH8R-MBK","Paramount-SP723CEQSB-FBA","Paramount-SPE2295-BK","Paramount-SPE2295-WH","Paramount-TN20U","Paramount-TN25CM","Paramount-TN33BR","Paramount-TN49AB","Paramount-TParamount-Thunder-HJ-12","Paramount-TS001CR","Paramount-Thunder-HJ-16","Paramount-UB24-Concert-Ukulele-Bag","Paramount-UB26-Tenor-Ukulele-Bag","Paramount-W758","Pastel-K-154","Pastel-P9BK","Pastel-P9WH","Pastel-Siamkey61","Pickguard-DPG-HB050","Play-Drumboy-GPD-AT02-Danube","Play-Drumboy-GPD-DGS01-GN","Play-Drumboy-GPD-DGS01-PP","Play-Drumboy-GPD-DGS01-YW","Play-Drumboy-PB03-Blowout-Dazzle-Colour","Play-Drumboy-PB10-Red-Bird","Player-Cymbal-Cleaner-CM250","Prima-P-200A16","Prima-P-280","Prima-P-360","Prima-P-480","Proline-PB100BK","Proline-PB205WH","Proline-PB90BK","Proline-PB90BL","Proline-PB90RD","Promark-TX2BN","Promark-TX5BW","Promark-X5AXW","Remo-EN-0312-PS","Remo-EN-0313-BA","Remo-EN-0313-PS","Remo-EN-0314-PS","Remo-EN-1220-CT","Remo-ES-1622-PS","Rock-RM1","RockaRhythm-FZGGP-10","RockaRhythm-FZGGP-8","RockaRhythm-G16-4","RockaRhythm-HB8-10","RockaRhythm-YSH245","Rockarhythm-G16-6","Rockarhythm-KSU-0-GR","Rockarhythm-KSU-BL","Roland-SPD-30V2-PDS-10","Roland-TD-17KVX","Roland-TD-17KV","Roland-TD-1DMK","Roland-TD-1K","Roland-VDRUM-TD1KV","Roland-XPS-10","Roland-XPS30","Roli-Songmaker-Kit","Rowin-WS-20","SAMSON-C01","SE-Electronics-X1-S-Studio-Bundle","SHURE-PGA48","SHURE-PGA58LC","SHURE-SV100-2","SHURE-SV100","SQOE-SEIB500RD","Squier-Affinity-Tele-BK-0310202506","Squier-Affinity-Tele-GR-0310200592","Squier-Affinity-Tele-SV-S1-0310200581","Squier-Bullet-Strat-HSS-BK-0310005506","Squier-Bullet-Strat-HSS-WH-S1-0310005580","Squier-Bullet-Strat-SB-0310001532","Squier-Bullet-Strat-Sonic-Grey","Squier-PNML-Super-Sonic-0377015569-GRM","Squier-PNML-Super-Sonic-0377015583-IBM","Squier-PNML-Toronado-0377000502-Lake-Placid-Blue","Squier-PNML-Toronado-0377000506-Black","Squier-SFR-Affinity-Strat-LRL-Olympic-White","Squier-Vintage-Mod-Jazz-Bass-70s-WH","Squier-Vintage-Mod-Jazz-Bass-77s-BK-0307702506","Squier-Vintage-Mod-Jazz-Bass-77s-SB","Stable-PD-1","Sterling-CT-30HSS-VC","Sterling-CT30SSS-DBL","Studiologic-Numa-Compact-2","Studiomaster-CM50","Studiomaster-CM51","Studiomaster-KM102","Sure-E12MM-BK","Switchcraft-280","TAMA-BCM40","Tascam-DR-05X","Tascam-TM-80-BK","Tascam-US-1x2-1","Tascam-US-1x2","Vic-Firth-SRHTSW","Vic-Firth-SRH","Vic-Firth-STATH","Vic-Firth-VICKEY3","Vic-Firth-VICKEY","VicFirth-5A-Hickory","VicFirth-5B-Hickory","VicFirth-5BB-Hickory","Vintage-V100-AFD-Paradise","Vox-AC2RV","Vox-Mini5-CL","Vox-VXII","Vox-amPlug2-Cab-and-Blues-S1","Vox-amPlug2-Cab-and-Classic-Rock-S1","Wilkinson-WOGB1","Wilkinson-WOGB2","Wilkinson-WOHAS-B","Wilkinson-WOHHB-B-BK","Wilkinson-WOHHB-B-WH","Wilkinson-WOHZB-N","Wilkinson-WOT01","Xvive-U4","YS-MS-B4","Yamaha-A1M-SB","Yamaha-A1R-NT","Yamaha-A1R-SB","Yamaha-A3M-SB","Yamaha-BB235-Yellow-Natural-Satin","Yamaha-BB434-Black","Yamaha-BB734A-Matte-Translucent-Black","Yamaha-BB735A-Dark-Coffee-Sunburst","Yamaha-BB735A-Matte-Translucent-Black","Yamaha-BBP34-Midnight-Blue","Yamaha-BBP35-Midnight-Blue","Yamaha-BBP35-Vintage-Sunburst","Yamaha-Bag-YB20-VIP","Yamaha-C40","Yamaha-C80","Yamaha-CG-TA","Yamaha-CG142C","Yamaha-CG182C","Yamaha-CSF1M-Tabacco-Brown-Sunburat","Yamaha-CX40","Yamaha-FC4A","Yamaha-FC7","Yamaha-FG-TA-BK","Yamaha-FG-TA-NT","Yamaha-FG5","Yamaha-FP9C","Yamaha-FS-TA-NT","Yamaha-FS100CBK","Yamaha-FS800","Yamaha-FSX315CN-S1","Yamaha-FSX315CN","Yamaha-FSX315CSB","Yamaha-FSX3","Yamaha-HW680W","Yamaha-HW780","Yamaha-HW880","Yamaha-JR2-NT","Yamaha-LJ26","Yamaha-LJ36","Yamaha-P115BK-LP5A","Yamaha-P115BK","Yamaha-P115WH-LP5A-INT","Yamaha-P115WH-LP5A","Yamaha-P115WH","Yamaha-PAC212VQM-Tobacco-Brown-Sunburst","Yamaha-PAC612VIIFM-Root-Beer","Yamaha-PAC612VIIFM-Translucent-Black","Yamaha-Pacifica012-BL-Nux-Mighty-Lite-BT","Yamaha-Pacifica012-BL-amPlug2-Metal","Yamaha-Pacifica012-DBM-Blue","Yamaha-Pacifica012-DBM-amPlug2-Metal","Yamaha-Pacifica012-RD-Laney-Mini-Superg","Yamaha-Pacifica012-WH-Laney-Mini-Superg","Yamaha-Pacifica012-WH-White","Yamaha-Pacifica112J-Black","Yamaha-RGX420DZII-White","Yamaha-RS420-Maya-Gold","Yamaha-RS502T-Black","Yamaha-RS502T-Bowden-Green","Yamaha-RS620-Brick-Burst","Yamaha-RS620-Burnt-Charcoal","Yamaha-RS620-Snake-Eye-Green","Yamaha-RS720B-Ash-Grey","Yamaha-RS720B-Shop-Black","Yamaha-RS720B-Vintage-Japanese-Denim","Yamaha-RS820CR-Burst-Rat"];
    
        for($i=0; $i<sizeof($filenameList); $i++)
        {
            $sku = $filenameList[$i];
            $sql = "insert into webskutodelete (sku) values('$sku')";
            $ret = doQueryTask($con,$sql,$modifiedUser);
            if($ret != "")
            {
                writeToLog("INSERT INTO webskutodelete fail [sku]:[$sku]");
                echo $sql;
                exit();
            }
        }
        exit();
        
    
    
    
   ?>
