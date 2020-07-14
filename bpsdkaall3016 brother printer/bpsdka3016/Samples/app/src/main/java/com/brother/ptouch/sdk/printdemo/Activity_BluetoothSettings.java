/**
 * Activity of printer settings
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.view.View;
import android.widget.Button;

import com.brother.ptouch.sdk.PrinterInfo.PrinterSettingItem;

import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;

public class Activity_BluetoothSettings extends BasePrinterSettingActivity {

    @SuppressWarnings("deprecation")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.preference_cutome_layout);
        addPreferencesFromResource(R.xml.bluetooth_settings);


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
        mList = Arrays.asList(PrinterSettingItem.BT_ISDISCOVERABLE,
                PrinterSettingItem.BT_DEVICENAME,
                 PrinterSettingItem.BT_BOOTMODE, PrinterSettingItem.BT_AUTO_CONNECTION);

    }

    private void updateValue() {

        setPreferenceValue("bt_isdiscoverable");
        setPreferenceValue("bt_bootmode");
        setEditValue("bt_devicename");
        setPreferenceValue("bt_auto_connection");
    }

    protected Map<PrinterSettingItem, String> createSettingsMap() {

        Map<PrinterSettingItem, String> settings = new HashMap<PrinterSettingItem, String>();
        settings.put(PrinterSettingItem.BT_ISDISCOVERABLE,
                sharedPreferences.getString("bt_isdiscoverable", ""));
        settings.put(PrinterSettingItem.BT_DEVICENAME,
                sharedPreferences.getString("bt_devicename", ""));

        settings.put(PrinterSettingItem.BT_BOOTMODE,
                sharedPreferences.getString("bt_bootmode", ""));

        settings.put(PrinterSettingItem.BT_AUTO_CONNECTION,
                sharedPreferences.getString("bt_auto_connection", ""));

        return settings;

    }

    protected void saveSettings(Map<PrinterSettingItem, String> settings) {
        final SharedPreferences.Editor editor = sharedPreferences.edit();

        for (PrinterSettingItem str : settings.keySet()) {
            switch (str) {
                case BT_ISDISCOVERABLE:
                    setPreferenceValue("bt_isdiscoverable", settings.get(str));
                    break;

                case BT_DEVICENAME:
                    setEditValue("bt_devicename", settings.get(str));
                    break;
                case BT_BOOTMODE:
                    setPreferenceValue("bt_bootmode", settings.get(str));
                    break;
                case BT_AUTO_CONNECTION:
                    setPreferenceValue("bt_auto_connection", settings.get(str));
                    break;
                default:
                    break;
            }
        }
        editor.apply();
        updateValue();

    }

}
