package com.brother.ptouch.sdk.printdemo;

import android.annotation.TargetApi;
import android.app.AlertDialog;
import android.app.PendingIntent;
import android.bluetooth.BluetoothAdapter;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.hardware.usb.UsbDevice;
import android.hardware.usb.UsbManager;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.preference.EditTextPreference;
import android.preference.ListPreference;
import android.preference.Preference;
import android.preference.Preference.OnPreferenceChangeListener;
import android.preference.PreferenceActivity;
import android.view.KeyEvent;

import com.brother.ptouch.sdk.PrinterInfo;
import com.brother.ptouch.sdk.PrinterInfo.ErrorCode;
import com.brother.ptouch.sdk.PrinterInfo.PrinterSettingItem;
import com.brother.ptouch.sdk.PrinterStatus;
import com.brother.ptouch.sdk.printdemo.common.Common;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.common.MsgHandle;
import com.brother.ptouch.sdk.printdemo.printprocess.BasePrint;
import com.brother.ptouch.sdk.printdemo.printprocess.PrinterModelInfo;
import com.brother.ptouch.sdk.printdemo.printprocess.PrinterPreference;
import com.brother.ptouch.sdk.printdemo.printprocess.PrinterPreference.PrinterPreListener;

import java.util.List;
import java.util.Map;

@SuppressWarnings("ALL")
public abstract class BasePrinterSettingActivity extends PreferenceActivity
        implements OnPreferenceChangeListener {

    private static final String ACTION_USB_PERMISSION = "com.android.example.USB_PERMISSION";
    private final BroadcastReceiver mUsbReceiver = new BroadcastReceiver() {
        @TargetApi(12)
        @Override
        public void onReceive(Context context, Intent intent) {
            String action = intent.getAction();
            if (ACTION_USB_PERMISSION.equals(action)) {
                synchronized (this) {
                    if (intent.getBooleanExtra(
                            UsbManager.EXTRA_PERMISSION_GRANTED, false))
                        Common.mUsbRequest = 1;
                    else
                        Common.mUsbRequest = 2;
                }
            }
        }
    };
    private final String INVALID_PARAM_VALUE = "";
    SharedPreferences sharedPreferences;
    PrinterModelInfo modelInfo = new PrinterModelInfo();
    List<PrinterSettingItem> mList;
    private BasePrint myPrint = null;
    private MsgHandle mHandle;
    private MsgDialog mDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        mDialog = new MsgDialog(this);
        mHandle = new MsgHandle(this, mDialog);
        myPrint = new PrinterPreference(this, mHandle, mDialog);

        BluetoothAdapter bluetoothAdapter = getBluetoothAdapter();
        myPrint.setBluetoothAdapter(bluetoothAdapter);

    }

    /**
     * Called when [Printer Settings] button is tapped
     */
    public void printerSettingsButtonOnClick() {
        startActivity(new Intent(this, Activity_Settings.class));
    }

    /**
     * show message when BACK key is clicked
     */
    @Override
    public boolean onKeyDown(final int keyCode, final KeyEvent event) {

        if (keyCode == KeyEvent.KEYCODE_BACK && event.getRepeatCount() == 0) {
            showTips();
        }
        return false;
    }

    /**
     * show the BACK message
     */
    private void showTips() {

        final AlertDialog alertDialog = new AlertDialog.Builder(this)
                .setTitle(R.string.end_title)
                .setMessage(R.string.end_message)
                .setCancelable(false)
                .setPositiveButton(R.string.button_ok,
                        new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(final DialogInterface dialog,
                                                final int which) {

                                finish();
                            }
                        })
                .setNegativeButton(R.string.button_cancel,
                        new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(final DialogInterface dialog,
                                                final int which) {
                            }
                        }).create();
        alertDialog.show();
    }

    /**
     * get the BluetoothAdapter
     */
    private BluetoothAdapter getBluetoothAdapter() {
        final BluetoothAdapter bluetoothAdapter = BluetoothAdapter
                .getDefaultAdapter();
        if (bluetoothAdapter != null && !bluetoothAdapter.isEnabled()) {
            final Intent enableBtIntent = new Intent(
                    BluetoothAdapter.ACTION_REQUEST_ENABLE);
            enableBtIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(enableBtIntent);
        }
        return bluetoothAdapter;
    }

    @TargetApi(12)
    protected UsbDevice getUsbDevice(UsbManager usbManager) {
        if (myPrint.getPrinterInfo().port != PrinterInfo.Port.USB) {
            return null;
        }
        if (Build.VERSION.SDK_INT < Build.VERSION_CODES.HONEYCOMB_MR1) {
            Message msg = mHandle.obtainMessage(Common.MSG_WRONG_OS);
            mHandle.sendMessage(msg);
            return null;
        }
        UsbDevice usbDevice = myPrint.getUsbDevice(usbManager);
        if (usbDevice == null) {
            Message msg = mHandle.obtainMessage(Common.MSG_NO_USB);
            mHandle.sendMessage(msg);
            return null;
        }

        return usbDevice;
    }

    /**
     * A USB driver is acquired.
     *
     * @param activity
     * @return
     */
    @TargetApi(12)
    private boolean createUsbDevice(BaseActivity activity) {

        UsbManager usbManager = (UsbManager) getSystemService(Context.USB_SERVICE);
        UsbDevice usbDevice = activity.getUsbDevice(usbManager);
        if (usbDevice == null) {
            return false;
        }
        PendingIntent permissionIntent = PendingIntent.getBroadcast(this, 0,
                new Intent(BaseActivity.ACTION_USB_PERMISSION), 0);
        usbManager.requestPermission(usbDevice, permissionIntent);
        return true;
    }

    @TargetApi(12)
    private boolean checkUSB() {
        if (myPrint.getPrinterInfo().port != PrinterInfo.Port.USB) {
            return true;
        }
        if (Build.VERSION.SDK_INT < Build.VERSION_CODES.HONEYCOMB_MR1) {
            Message msg = mHandle.obtainMessage(Common.MSG_WRONG_OS);
            mHandle.sendMessage(msg);
            return false;
        }
        UsbManager usbManager = (UsbManager) getSystemService(Context.USB_SERVICE);
        UsbDevice usbDevice = myPrint.getUsbDevice(usbManager);
        if (usbDevice == null) {
            Message msg = mHandle.obtainMessage(Common.MSG_NO_USB);
            mHandle.sendMessage(msg);
            return false;
        }
        PendingIntent permissionIntent = PendingIntent.getBroadcast(this, 0,
                new Intent(ACTION_USB_PERMISSION), 0);
        registerReceiver(mUsbReceiver, new IntentFilter(ACTION_USB_PERMISSION));
        if (!usbManager.hasPermission(usbDevice)) {
            Common.mUsbRequest = 0;
            usbManager.requestPermission(usbDevice, permissionIntent);
        } else {
            Common.mUsbRequest = 1;
        }
        return true;
    }

    /**
     * set data of a particular ListPreference
     */
    @SuppressWarnings("deprecation")
    void setPreferenceValue(String key, String value) {

        ListPreference printerValuePreference = (ListPreference) getPreferenceScreen()
                .findPreference(key);

        int index = printerValuePreference.findIndexOfValue(value);
        if (index >= 0) {
            printerValuePreference.setSummary(printerValuePreference
                    .getEntries()[index]);
            printerValuePreference.setValue(value);
        } else {
            printerValuePreference.setSummary(getResources().getString(
                    R.string.text_no_data));
            printerValuePreference.setValue("");
        }

    }

    /**
     * set data of a particular EditTextPreference
     */
    @SuppressWarnings("deprecation")
    void setEditValue(String key, String value) {
        EditTextPreference printerValuePreference = (EditTextPreference) getPreferenceScreen()
                .findPreference(key);

        if (!value.equals("")) {
            printerValuePreference.setSummary(value);
            printerValuePreference.setText(value);
        } else {
            printerValuePreference.setSummary(getResources().getString(
                    R.string.text_no_data));
            printerValuePreference.setText("");
        }
    }

    /**
     * set data of a particular ListPreference
     */
    @SuppressWarnings("deprecation")
    void setPreferenceValue(String value) {
        String data = sharedPreferences.getString(value, "");

        ListPreference printerValuePreference = (ListPreference) getPreferenceScreen()
                .findPreference(value);
        printerValuePreference.setOnPreferenceChangeListener(this);
        if (data.equals("")) {
            printerValuePreference.setValue(INVALID_PARAM_VALUE);
            sharedPreferences.edit().putString(value, INVALID_PARAM_VALUE)
                    .commit();
        }

        int index = printerValuePreference.findIndexOfValue(data);
        if (index >= 0) {
            printerValuePreference.setSummary(printerValuePreference
                    .getEntries()[index]);
        } else {
            printerValuePreference.setSummary(getResources().getString(
                    R.string.text_no_data));
        }

    }

    /**
     * set data of a particular EditTextPreference
     */
    @SuppressWarnings("deprecation")
    void setEditValue(String value) {
        String name = sharedPreferences.getString(value, "");
        EditTextPreference printerValuePreference = (EditTextPreference) getPreferenceScreen()
                .findPreference(value);
        printerValuePreference.setOnPreferenceChangeListener(this);

        if (!name.equals("")) {
            printerValuePreference.setSummary(name);
        } else {
            printerValuePreference.setSummary(getResources().getString(
                    R.string.text_no_data));
        }
    }

    /**
     * Called when a Preference has been changed by the user. This is called
     * before the state of the Preference is about to be updated and before the
     * state is persisted.
     */
    @Override
    public boolean onPreferenceChange(Preference preference, Object newValue) {

        if (newValue != null) {

            if (preference instanceof ListPreference) {

                ((ListPreference) preference).setValue(newValue.toString());
                preference.setSummary(((ListPreference) preference).getEntry());

            } else if (preference instanceof EditTextPreference) {
                String data = newValue.toString();
                if ("".equals(data)) {
                    preference.setSummary(getResources().getString(
                            R.string.text_no_data));

                } else {
                    preference.setSummary(newValue.toString());

                }
            }

            return true;
        }

        return false;

    }

    abstract Map<PrinterSettingItem, String> createSettingsMap();

    abstract void saveSettings(Map<PrinterSettingItem, String> settings);

    /**
     * Called when [Printer Settings] button is tapped
     */
    void setPrinterSettingsButtonOnClick() {

        Map<PrinterSettingItem, String> settings = createSettingsMap();
        if (!checkUSB())
            return;
        ((PrinterPreference) myPrint).updatePrinterSetting(settings);

    }

    /**
     * Called when [Printer Settings] button is tapped
     */
    void getPrinterSettingsButtonOnClick() {

        if (!checkUSB())
            return;
        final Handler handler = new Handler();
        PrinterPreListener listener = new PrinterPreListener() {
            @Override
            public void finish(final PrinterStatus status,
                               final Map<PrinterSettingItem, String> settings) {
                handler.post(new Runnable() {

                    @Override
                    public void run() {
                        if (status.errorCode == ErrorCode.ERROR_NONE) {
                            saveSettings(settings);
                        }

                    }
                });
            }
        };

        ((PrinterPreference) myPrint).getPrinterSetting(mList, listener);

    }

}
