/**
 * Base Activity for printing
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

@SuppressWarnings("ALL")
public class Activity_PrinterPreference extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_printer_preference);


        Button btnMWBluetoothSettings = (Button) findViewById(R.id.btnMWBluetoothSettings);
        btnMWBluetoothSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                MWBluetoothPreferenceButtonOnClick();

            }
        });

        Button btnBluetoothSettings = (Button) findViewById(R.id.btnBluetoothSettings);
        btnBluetoothSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                BluetoothPreferenceButtonOnClick();

            }
        });
        Button btnNetSettings = (Button) findViewById(R.id.btnNetSettings);
        btnNetSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                NetPreferenceButtonOnClick();

            }
        });
        Button btnDeviceSettings = (Button) findViewById(R.id.btnDeviceSettings);
        btnDeviceSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                PrinterPreferenceButtonOnClick();

            }
        });
        Button btnPrinterSettings = (Button) findViewById(R.id.btnPrinterSettings);
        btnPrinterSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                printerSettingsButtonOnClick();

            }
        });

    }

    /**
     * Called when [Printer Settings] button is tapped
     */
    private void MWBluetoothPreferenceButtonOnClick() {
        startActivity(new Intent(this,
                Activity_MWBluetoothPrinterPreference.class));
    }

    /**
     * Called when [Printer Settings] button is tapped
     */
    private void BluetoothPreferenceButtonOnClick() {
        startActivity(new Intent(this, Activity_BluetoothSettings.class));
    }

    /**
     * Called when [Printer Settings] button is tapped
     */
    private void PrinterPreferenceButtonOnClick() {
        startActivity(new Intent(this, Activity_PrinterSettings.class));
    }

    /**
     * Called when [Printer Settings] button is tapped
     */
    private void NetPreferenceButtonOnClick() {
        startActivity(new Intent(this, Activity_NetSettings.class));
    }

    /**
     * Called when [Printer Settings] button is tapped
     */
    private void printerSettingsButtonOnClick() {
        startActivity(new Intent(this, Activity_Settings.class));
    }

}