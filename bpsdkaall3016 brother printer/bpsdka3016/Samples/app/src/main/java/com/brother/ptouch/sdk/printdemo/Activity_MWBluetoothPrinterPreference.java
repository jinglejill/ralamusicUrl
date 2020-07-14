/**
 * Activity of Device Settings
 *
 * @author Brother Industries, Ltd.
 * @version 2.5.1
 */

package com.brother.ptouch.sdk.printdemo;

import android.bluetooth.BluetoothAdapter;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;

import com.brother.ptouch.sdk.BluetoothPreference;
import com.brother.ptouch.sdk.BluetoothPreference.PowerSaveMode;
import com.brother.ptouch.sdk.BluetoothPreference.SSP;
import com.brother.ptouch.sdk.PrinterInfo.ErrorCode;
import com.brother.ptouch.sdk.PrinterStatus;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.common.MsgHandle;
import com.brother.ptouch.sdk.printdemo.printprocess.MWBluetoothPrinterPreference;
import com.brother.ptouch.sdk.printdemo.printprocess.MWBluetoothPrinterPreference.PrinterPreListener;

public class Activity_MWBluetoothPrinterPreference extends BaseActivity {

    private final int SSP_ENABLE_KEY = 1;
    private final int SSP_DISABLE_KEY = 2;

    private final int PSM_ECO_KEY = 1;
    private final int PSM_STANDARD_KEY = 2;
    private final int PSM_CONNECTION_KEY = 3;

    private final int INDEFINITE = 0;

    private Spinner sspSpinner;
    private Spinner pmSpinner;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mw_bluetooth_preference);


        mDialog = new MsgDialog(this);
        mHandle = new MsgHandle(this, mDialog);
        myPrint = new MWBluetoothPrinterPreference(this, mHandle, mDialog);

        BluetoothAdapter bluetoothAdapter = super.getBluetoothAdapter();
        myPrint.setBluetoothAdapter(bluetoothAdapter);

        sspSpinner = (Spinner) findViewById(R.id.spinner_ssp);
        String[] sspStates = getResources().getStringArray(R.array.ssp_array);
        ArrayAdapter<String> sspAdapter = new ArrayAdapter<String>(this,
                android.R.layout.simple_spinner_item, sspStates);
        sspAdapter
                .setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        sspSpinner.setAdapter(sspAdapter);

        pmSpinner = (Spinner) findViewById(R.id.spinner_powermode);

        String[] pmStates = getResources().getStringArray(
                R.array.power_mode_array);
        ArrayAdapter<String> pmAdapter = new ArrayAdapter<String>(this,
                android.R.layout.simple_spinner_item, pmStates);
        pmAdapter
                .setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        pmSpinner.setAdapter(pmAdapter);


        Button btGetPrinterSetting = (Button) findViewById(R.id.btGetPrinterSetting);
        btGetPrinterSetting.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getPrinterSettingButtonOnClick();

            }
        });

        Button btUpdatePrinterSetting = (Button) findViewById(R.id.btUpdatePrinterSetting);
        btUpdatePrinterSetting.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                updatePrinterSettingButtonOnClick();

            }
        });

        Button btPrinterSettings = (Button) findViewById(R.id.btPrinterSettings);
        btPrinterSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                printerSettingsButtonOnClick();

            }
        });

    }

    /**
     * Getting printer settings button listener
     */
    private void getPrinterSettingButtonOnClick() {
        if (!checkUSB())
            return;
        final Handler handler = new Handler();
        PrinterPreListener listener = new PrinterPreListener() {

            @Override
            public void finish(final PrinterStatus status,
                               final BluetoothPreference btPre) {
                handler.post(new Runnable() {

                    @Override
                    public void run() {
                        if (status.errorCode == ErrorCode.ERROR_NONE) {
                            int pmId = getPowerSaveModeKey(btPre.powerMode);
                            pmSpinner.setSelection(pmId);
                            int sspId = getSspKey(btPre.enableSsp);
                            sspSpinner.setSelection(sspId);
                        } else {
                            pmSpinner.setSelection(INDEFINITE);
                            sspSpinner.setSelection(INDEFINITE);
                        }

                    }
                });
            }
        };

        ((MWBluetoothPrinterPreference) myPrint).getPrinterSetting(listener);

    }

    /**
     * Updating printer settings button listener
     */
    private void updatePrinterSettingButtonOnClick() {
        if (!checkUSB())
            return;

        BluetoothPreference btPref = new BluetoothPreference();

        int idx = sspSpinner.getSelectedItemPosition();
        btPref.enableSsp = getSspValue(idx);
        idx = pmSpinner.getSelectedItemPosition();
        btPref.powerMode = getPowerSaveModeValue(idx);

        ((MWBluetoothPrinterPreference) myPrint).updatePrinterSetting(btPref
        );

    }

    /**
     * Get the setting value of SSP corresponding to the key
     *
     * @param key
     * @return
     */
    private SSP getSspValue(int key) {
        if (key == SSP_ENABLE_KEY) {
            return SSP.ENABLE;
        } else if (key == SSP_DISABLE_KEY) {
            return SSP.DISABLE;
        } else {
            return SSP.NOCHANGE;
        }
    }

    /**
     * Get the key that corresponds to the setting value of SSP
     *
     * @param value
     * @return
     */
    private int getSspKey(SSP value) {

        if (value.equals(SSP.ENABLE)) {
            return SSP_ENABLE_KEY;
        } else if (value.equals(SSP.DISABLE)) {
            return SSP_DISABLE_KEY;
        } else {
            return INDEFINITE;
        }
    }

    /**
     * Get the setting value of Power-saving corresponding to the key
     *
     * @param key
     * @return
     */
    private PowerSaveMode getPowerSaveModeValue(int key) {
        if (key == PSM_ECO_KEY) {
            return PowerSaveMode.ECO_MODE;
        } else if (key == PSM_STANDARD_KEY) {
            return PowerSaveMode.STANDARD_MODE;
        } else if (key == PSM_CONNECTION_KEY) {
            return PowerSaveMode.CONNECTION_MODE;
        } else {
            return PowerSaveMode.NOCHANGE;

        }
    }

    /**
     * Get the key that corresponds to the setting value of Power-saving
     *
     * @param value
     * @return
     */
    private int getPowerSaveModeKey(PowerSaveMode value) {
        if (value.equals(PowerSaveMode.ECO_MODE)) {
            return PSM_ECO_KEY;
        } else if (value.equals(PowerSaveMode.STANDARD_MODE)) {
            return PSM_STANDARD_KEY;
        } else if (value.equals(PowerSaveMode.CONNECTION_MODE)) {
            return PSM_CONNECTION_KEY;
        } else {
            return INDEFINITE;
        }
    }

    @Override
    public void selectFileButtonOnClick() {
        // TODO Auto-generated method stub

    }

    @Override
    public void printButtonOnClick() {
        // TODO Auto-generated method stub

    }

}
