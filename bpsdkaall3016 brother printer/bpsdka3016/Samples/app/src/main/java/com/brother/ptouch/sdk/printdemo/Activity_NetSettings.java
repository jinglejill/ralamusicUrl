/**
 * Activity of printer settings
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo;

import android.os.Bundle;
import android.preference.PreferenceManager;
import android.view.View;
import android.widget.Button;

import com.brother.ptouch.sdk.PrinterInfo.PrinterSettingItem;

import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;

public class Activity_NetSettings extends BasePrinterSettingActivity {

    @SuppressWarnings("deprecation")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.preference_cutome_layout);
        addPreferencesFromResource(R.xml.net_settings);

        Button btGetPrinterSettings = (Button) findViewById(R.id.btGetPrinterSettings);
        btGetPrinterSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getPrinterSettingsButtonOnClick();

            }
        });

        Button btSetPrinterSettings = (Button) findViewById(R.id.btSetPrinterSettings);
        btSetPrinterSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                setPrinterSettingsButtonOnClick();

            }
        });


        sharedPreferences = PreferenceManager.getDefaultSharedPreferences(this);

        updateValue();

        mList = Arrays.asList(
                PrinterSettingItem.NET_BOOTMODE,
                PrinterSettingItem.NET_INTERFACE,
                PrinterSettingItem.NET_USED_IPV6,
                PrinterSettingItem.NET_PRIORITY_IPV6,
                PrinterSettingItem.NET_IPV4_BOOTMETHOD,
                PrinterSettingItem.NET_STATIC_IPV4ADDRESS,
                PrinterSettingItem.NET_SUBNETMASK,
                PrinterSettingItem.NET_GATEWAY,
                PrinterSettingItem.NET_DNS_IPV4_BOOTMETHOD,
                PrinterSettingItem.NET_PRIMARY_DNS_IPV4ADDRESS,
                PrinterSettingItem.NET_SECOND_DNS_IPV4ADDRESS,
                PrinterSettingItem.NET_IPV6_BOOTMETHOD,
                PrinterSettingItem.NET_STATIC_IPV6ADDRESS,
                PrinterSettingItem.NET_PRIMARY_DNS_IPV6ADDRESS,
                PrinterSettingItem.NET_SECOND_DNS_IPV6ADDRESS,
                PrinterSettingItem.NET_IPV6ADDRESS_LIST,
                PrinterSettingItem.NET_COMMUNICATION_MODE,
                PrinterSettingItem.NET_SSID, PrinterSettingItem.NET_CHANNEL,
                PrinterSettingItem.NET_AUTHENTICATION_METHOD,
                PrinterSettingItem.NET_ENCRYPTIONMODE,
                PrinterSettingItem.NET_WEPKEY,
                PrinterSettingItem.NET_PASSPHRASE,
                PrinterSettingItem.NET_USER_ID,
                PrinterSettingItem.NET_PASSWORD,
                PrinterSettingItem.NET_NODENAME,
                PrinterSettingItem.WIRELESSDIRECT_KEY_CREATE_MODE,
                PrinterSettingItem.WIRELESSDIRECT_SSID,
                PrinterSettingItem.WIRELESSDIRECT_NETWORK_KEY
        );

    }

    private void updateValue() {

        setPreferenceValue("net_bootmode");
        setPreferenceValue("net_interface");
        setPreferenceValue("net_priority_ipv6");
        setPreferenceValue("net_ip4_staticmode");
        setPreferenceValue("net_used_ipv6");
        setPreferenceValue("net_ip6_staticmode");
        setPreferenceValue("net_communication_mode");
        setPreferenceValue("wirelessdirect_key_create_mode");
        setPreferenceValue("net_channel");
        setPreferenceValue("net_authentication_method");
        setPreferenceValue("net_encryptionmode");
        setEditValue("net_ipv4_static_address");
        setEditValue("net_subnetmask");
        setEditValue("net_gateway");
        setPreferenceValue("net_dns_ipv4_bootmethod");
        setEditValue("net_primary_dns_ipv4address");
        setEditValue("net_second_dns_ipv4address");
        setEditValue("net_ipv6_static_address");
        setEditValue("net_primary_dns_ipv6address");
        setEditValue("net_second_dns_ipv6address");
        setEditValue("net_ipv6address_list");
        setEditValue("net_ssid");
        setEditValue("net_wepkey");
        setEditValue("net_passphrase");
        setEditValue("net_user_Id");
        setEditValue("net_password");
        setEditValue("net_nodenam");
        setEditValue("wirelessdirect_ssid");
        setEditValue("wirelessdirect_network_key");
    }

    protected Map<PrinterSettingItem, String> createSettingsMap() {

        Map<PrinterSettingItem, String> settings = new HashMap<PrinterSettingItem, String>();

        settings.put(PrinterSettingItem.NET_BOOTMODE,
                sharedPreferences.getString("net_bootmode", ""));

        settings.put(PrinterSettingItem.NET_INTERFACE,
                sharedPreferences.getString("net_interface", ""));

        settings.put(PrinterSettingItem.NET_USED_IPV6,
                sharedPreferences.getString("net_used_ipv6", ""));
        settings.put(PrinterSettingItem.NET_PRIORITY_IPV6,
                sharedPreferences.getString("net_priority_ipv6", ""));
        settings.put(PrinterSettingItem.NET_IPV4_BOOTMETHOD,
                sharedPreferences.getString("net_ip4_staticmode", ""));
        settings.put(PrinterSettingItem.NET_STATIC_IPV4ADDRESS,
                sharedPreferences.getString("net_ipv4_static_address", ""));

        settings.put(PrinterSettingItem.NET_SUBNETMASK,
                sharedPreferences.getString("net_subnetmask", ""));
        settings.put(PrinterSettingItem.NET_GATEWAY,
                sharedPreferences.getString("net_gateway", ""));
        settings.put(PrinterSettingItem.NET_DNS_IPV4_BOOTMETHOD,
                sharedPreferences.getString("net_dns_ipv4_bootmethod", ""));
        settings.put(PrinterSettingItem.NET_PRIMARY_DNS_IPV4ADDRESS,
                sharedPreferences.getString("net_primary_dns_ipv4address", ""));
        settings.put(PrinterSettingItem.NET_SECOND_DNS_IPV4ADDRESS,
                sharedPreferences.getString("net_second_dns_ipv4address", ""));
        settings.put(PrinterSettingItem.NET_IPV6_BOOTMETHOD,
                sharedPreferences.getString("net_ip6_staticmode", ""));
        settings.put(PrinterSettingItem.NET_STATIC_IPV6ADDRESS,
                sharedPreferences.getString("net_ipv6_static_address", ""));
        settings.put(PrinterSettingItem.NET_PRIMARY_DNS_IPV6ADDRESS,
                sharedPreferences.getString("net_primary_dns_ipv6address", ""));
        settings.put(PrinterSettingItem.NET_SECOND_DNS_IPV6ADDRESS,
                sharedPreferences.getString("net_second_dns_ipv6address", ""));
        settings.put(PrinterSettingItem.NET_IPV6ADDRESS_LIST,
                sharedPreferences.getString("net_ipv6address_list", ""));
        settings.put(PrinterSettingItem.NET_COMMUNICATION_MODE,
                sharedPreferences.getString("net_communication_mode", ""));
        settings.put(PrinterSettingItem.NET_SSID,
                sharedPreferences.getString("net_ssid", ""));
        settings.put(PrinterSettingItem.NET_CHANNEL,
                sharedPreferences.getString("net_channel", ""));
        settings.put(PrinterSettingItem.NET_AUTHENTICATION_METHOD,
                sharedPreferences.getString("net_authentication_method", ""));
        settings.put(PrinterSettingItem.NET_ENCRYPTIONMODE,
                sharedPreferences.getString("net_encryptionmode", ""));
        settings.put(PrinterSettingItem.NET_WEPKEY,
                sharedPreferences.getString("net_wepkey", ""));
        settings.put(PrinterSettingItem.NET_PASSPHRASE,
                sharedPreferences.getString("net_passphrase", ""));
        settings.put(PrinterSettingItem.NET_USER_ID,
                sharedPreferences.getString("net_user_Id", ""));
        settings.put(PrinterSettingItem.NET_PASSWORD,
                sharedPreferences.getString("net_password", ""));
        settings.put(PrinterSettingItem.NET_NODENAME,
                sharedPreferences.getString("net_nodenam", ""));
        settings.put(PrinterSettingItem.WIRELESSDIRECT_KEY_CREATE_MODE,
                sharedPreferences.getString("wirelessdirect_key_create_mode",
                        ""));
        settings.put(PrinterSettingItem.WIRELESSDIRECT_SSID,
                sharedPreferences.getString("wirelessdirect_ssid", ""));
        settings.put(PrinterSettingItem.WIRELESSDIRECT_NETWORK_KEY,
                sharedPreferences.getString("wirelessdirect_network_key", ""));

        return settings;

    }

    protected void saveSettings(Map<PrinterSettingItem, String> settings) {

        for (PrinterSettingItem str : settings.keySet()) {
            switch (str) {
                case NET_BOOTMODE:
                    setPreferenceValue("net_bootmode", settings.get(str));
                    break;
                case NET_INTERFACE:
                    setPreferenceValue("net_interface", settings.get(str));
                    break;
                case NET_USED_IPV6:
                    setPreferenceValue("net_used_ipv6", settings.get(str));
                    break;
                case NET_PRIORITY_IPV6:
                    setPreferenceValue("net_priority_ipv6", settings.get(str));
                    break;
                case NET_IPV4_BOOTMETHOD:
                    setPreferenceValue("net_ip4_staticmode", settings.get(str));
                    break;
                case NET_STATIC_IPV4ADDRESS:
                    setEditValue("net_ipv4_static_address", settings.get(str));
                    break;
                case NET_SUBNETMASK:
                    setEditValue("net_subnetmask", settings.get(str));
                    break;
                case NET_GATEWAY:
                    setEditValue("net_gateway", settings.get(str));
                    break;
                case NET_DNS_IPV4_BOOTMETHOD:
                    setPreferenceValue("net_dns_ipv4_bootmethod", settings.get(str));
                    break;
                case NET_PRIMARY_DNS_IPV4ADDRESS:
                    setEditValue("net_primary_dns_ipv4address",
                            settings.get(str));
                    break;
                case NET_SECOND_DNS_IPV4ADDRESS:
                    setEditValue("net_second_dns_ipv4address",
                            settings.get(str));
                    break;
                case NET_IPV6_BOOTMETHOD:
                    setPreferenceValue("net_ip6_staticmode", settings.get(str));
                    break;
                case NET_STATIC_IPV6ADDRESS:
                    setEditValue("net_ipv6_static_address", settings.get(str));
                    break;
                case NET_PRIMARY_DNS_IPV6ADDRESS:
                    setEditValue("net_primary_dns_ipv6address",
                            settings.get(str));
                    break;
                case NET_SECOND_DNS_IPV6ADDRESS:
                    setEditValue("net_second_dns_ipv6address",
                            settings.get(str));
                    break;
                case NET_IPV6ADDRESS_LIST:
                    setEditValue("net_ipv6address_list", settings.get(str));
                    break;
                case NET_COMMUNICATION_MODE:
                    setPreferenceValue("net_communication_mode", settings.get(str));
                    break;
                case NET_SSID:
                    setEditValue("net_ssid", settings.get(str));
                    break;

                case NET_CHANNEL:
                    setPreferenceValue("net_channel", settings.get(str));
                    break;
                case NET_AUTHENTICATION_METHOD:
                    setPreferenceValue("net_authentication_method", settings.get(str));
                    break;
                case NET_ENCRYPTIONMODE:
                    setPreferenceValue("net_encryptionmode", settings.get(str));
                    break;
                case NET_WEPKEY:
                    setEditValue("net_wepkey", settings.get(str));
                    break;
                case NET_PASSPHRASE:
                    setEditValue("net_passphrase", settings.get(str));
                    break;
                case NET_USER_ID:
                    setEditValue("net_user_Id", settings.get(str));
                    break;
                case NET_PASSWORD:
                    setEditValue("net_password", settings.get(str));
                    break;

                case NET_NODENAME:
                    setEditValue("net_nodenam", settings.get(str));
                    break;
                case WIRELESSDIRECT_KEY_CREATE_MODE:
                    setPreferenceValue("wirelessdirect_key_create_mode",
                            settings.get(str));
                    break;
                case WIRELESSDIRECT_SSID:
                    setEditValue("wirelessdirect_ssid", settings.get(str));
                    break;
                case WIRELESSDIRECT_NETWORK_KEY:
                    setEditValue("wirelessdirect_network_key",
                            settings.get(str));
                    break;
                default:
                    break;
            }
        }

    }

}
