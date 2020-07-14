CREATE TABLE `lazadaproduct2` (
`Shop SKU` varchar(50) NOT NULL,
`variation(read only)` varchar(50) NOT NULL,
`PrimaryCategory` varchar(50) NOT NULL,
`name` text NOT NULL,
`short_description` text NOT NULL,
`description` text NOT NULL,
`video` varchar(50) NOT NULL,
`warranty_type` varchar(50) NOT NULL,
`warranty` text NOT NULL,
`name_en` text NOT NULL,
`product_warranty` varchar(50) NOT NULL,
`product_warranty_en` text NOT NULL,
`description_en` text NOT NULL,
`Hazmat` varchar(50) NOT NULL,
`short_description_en` text NOT NULL,
`brand` varchar(500) NOT NULL,
`zal_present` varchar(500) NOT NULL,
`color_family` varchar(50) NOT NULL,
`model` varchar(500) NOT NULL,
`headphone_acc_types` varchar(50) NOT NULL,
`headphone_features` varchar(50) NOT NULL,
`bluetooth` varchar(50) NOT NULL,
`speakers_features` varchar(50) NOT NULL,
`output_connectivity` varchar(50) NOT NULL,
`number_of_channels` varchar(50) NOT NULL,
`karaoke_features` varchar(50) NOT NULL,
`karaoke_types` varchar(50) NOT NULL,
`mic_connectivity` varchar(50) NOT NULL,
`microphone_inputs` varchar(50) NOT NULL,
`receiver_feature` varchar(50) NOT NULL,
`subwoofer` varchar(50) NOT NULL,
`woofer_size` varchar(50) NOT NULL,
`cable_type_tagg` varchar(50) NOT NULL,
`cable_length` varchar(50) NOT NULL,
`built_in_battery` varchar(50) NOT NULL,
`radio_cdplayer_feature` varchar(50) NOT NULL,
`radio_cdplayer_types` varchar(50) NOT NULL,
`voice_recorder_types` varchar(50) NOT NULL,
`ptb_speaker_features` varchar(50) NOT NULL,
`cable_type` varchar(50) NOT NULL,
`compatible_devices` varchar(50) NOT NULL,
`dac_connection_type` varchar(50) NOT NULL,
`mic_acc_types` varchar(50) NOT NULL,
`mic_types` varchar(50) NOT NULL,
`pa_no_of_channels` varchar(50) NOT NULL,
`os_compatibility` varchar(50) NOT NULL,
`turntable_types` varchar(50) NOT NULL,
`number_of_speeds` varchar(50) NOT NULL,
`SellerSku` varchar(50) NOT NULL,
`quantity` varchar(50) NOT NULL,
`price` varchar(50) NOT NULL,
`special_price` varchar(50) NOT NULL,
`special_from_date` varchar(50) NOT NULL,
`special_to_date` varchar(50) NOT NULL,
`package_content` varchar(500) NOT NULL,
`package_weight` varchar(50) NOT NULL,
`package_length` varchar(50) NOT NULL,
`package_width` varchar(50) NOT NULL,
`package_height` varchar(50) NOT NULL,
`MainImage` varchar(200) NOT NULL,
`Image2` varchar(200) NOT NULL,
`Image3` varchar(200) NOT NULL,
`Image4` varchar(200) NOT NULL,
`Image5` varchar(200) NOT NULL,
`Image6` varchar(200) NOT NULL,
`Image7` varchar(200) NOT NULL,
`Image8` varchar(200) NOT NULL,
`package_contents_en` varchar(500) NOT NULL,
`color_thumbnail` varchar(50) NOT NULL,
`cable_connection` varchar(50) NOT NULL,
`delivery_option_sof` varchar(50) NOT NULL,
`Status` varchar(50) NOT NULL,
`FulfillmentBySellable` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;