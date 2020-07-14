/**
 * Activity of printer settings
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo;

import android.os.Bundle;
import android.preference.ListPreference;
import android.preference.PreferenceGroup;
import android.preference.PreferenceManager;
import android.view.View;
import android.widget.Button;

import com.brother.ptouch.sdk.PrinterInfo.PrinterSettingItem;

import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;

public class Activity_PrinterSettings extends BasePrinterSettingActivity {

    @SuppressWarnings("deprecation")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.preference_cutome_layout);
        addPreferencesFromResource(R.xml.printer_settings);

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

        mList = Arrays.asList(PrinterSettingItem.PRINT_SPEED,
                PrinterSettingItem.PRINT_DENSITY,
                PrinterSettingItem.PRINT_JPEG_SCALE,
                PrinterSettingItem.PRINT_JPEG_HALFTONE,
                PrinterSettingItem.PRINTER_POWEROFFTIME_BATTERY,
                PrinterSettingItem.PRINTER_POWEROFFTIME);


    }

    private void updateValue() {

        setPreferenceValue("print_jpeg_halftone");
        String modelName = sharedPreferences.getString("printerModel", "");
        if (modelName.contains("PJ")) {
            setPreferenceValue("pj_print_density");
            ListPreference printerValuePreference = (ListPreference) getPreferenceScreen()
                    .findPreference("print_density");
            ((PreferenceGroup) findPreference("printer_setting_category")).removePreference(printerValuePreference);
        } else {
            setPreferenceValue("print_density");
            ListPreference printerValuePreference = (ListPreference) getPreferenceScreen()
                    .findPreference("pj_print_density");
            ((PreferenceGroup) findPreference("printer_setting_category")).removePreference(printerValuePreference);
        }
        setPreferenceValue("print_jpeg_scale");
        setPreferenceValue("print_speed");
        setEditValue("printer_power_off_time_battery");
        setEditValue("printer_power_off_time");
    }

    protected Map<PrinterSettingItem, String> createSettingsMap() {

        Map<PrinterSettingItem, String> settings = new HashMap<PrinterSettingItem, String>();

        settings.put(PrinterSettingItem.PRINTER_POWEROFFTIME,
                sharedPreferences.getString("printer_power_off_time", ""));
        settings.put(PrinterSettingItem.PRINTER_POWEROFFTIME_BATTERY,
                sharedPreferences.getString("printer_power_off_time_battery", ""));
        settings.put(PrinterSettingItem.PRINT_JPEG_HALFTONE,
                sharedPreferences.getString("print_jpeg_halftone", ""));
        settings.put(PrinterSettingItem.PRINT_JPEG_SCALE,
                sharedPreferences.getString("print_jpeg_scale", ""));

        String modelName = sharedPreferences.getString("printerModel", "");
        if (modelName.contains("PJ")) {
            settings.put(PrinterSettingItem.PRINT_DENSITY,
                    sharedPreferences.getString("pj_print_density", ""));
        } else {
            settings.put(PrinterSettingItem.PRINT_DENSITY,
                    sharedPreferences.getString("print_density", ""));
        }
        settings.put(PrinterSettingItem.PRINT_SPEED,
                sharedPreferences.getString("print_speed", ""));
        return settings;

    }

    protected void saveSettings(Map<PrinterSettingItem, String> settings) {

        for (PrinterSettingItem str : settings.keySet()) {
            switch (str) {

                case PRINTER_POWEROFFTIME:
                    setEditValue("printer_power_off_time", settings.get(str));
                    break;
                case PRINTER_POWEROFFTIME_BATTERY:
                    setEditValue("printer_power_off_time_battery", settings.get(str));
                    break;
                case PRINT_JPEG_HALFTONE:
                    setPreferenceValue("print_jpeg_halftone", settings.get(str));
                    break;
                case PRINT_JPEG_SCALE:
                    setPreferenceValue("print_jpeg_scale", settings.get(str));
                    break;

                case PRINT_DENSITY:
                    String modelName = sharedPreferences.getString("printerModel", "");
                    if (modelName.contains("PJ")) {
                        setPreferenceValue("pj_print_density", settings.get(str));
                    } else {
                        setPreferenceValue("print_density", settings.get(str));
                    }
                    break;
                case PRINT_SPEED:
                    setPreferenceValue("print_speed", settings.get(str));
                    break;

                default:
                    break;
            }
        }

    }

}
