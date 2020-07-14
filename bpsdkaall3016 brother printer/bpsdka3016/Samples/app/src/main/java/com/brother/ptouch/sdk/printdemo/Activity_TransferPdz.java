/**
 * Activity of transferring the pdz files into printers
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo;

import android.bluetooth.BluetoothAdapter;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.brother.ptouch.sdk.printdemo.common.Common;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.common.MsgHandle;
import com.brother.ptouch.sdk.printdemo.printprocess.TemplateTransfer;

public class Activity_TransferPdz extends BaseActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_transfer_pdz);

        Button btnSelectFile = (Button) findViewById(R.id.btnSelectFile);
        btnSelectFile.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                selectFileButtonOnClick();
            }
        });

        Button btnPrinterSettings = (Button) findViewById(R.id.btnPrinterSettings);
        btnPrinterSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                printerSettingsButtonOnClick();
            }
        });

        Button btnTransfer = (Button) findViewById(R.id.btnTransfer);
        btnTransfer.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                transferButtonOnClick();
            }
        });


        Button btnUpdateFirm = (Button) findViewById(R.id.btnUpdateFirm);
        btnUpdateFirm.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                updateFirmButtonOnClick();
            }
        });

        Button btnGetFirmVer = (Button) findViewById(R.id.btnGetFirmVer);
        btnGetFirmVer.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getFirmVerOnClick();
            }
        });
        Button btnSendFile = (Button) findViewById(R.id.btnSendFile);
        btnSendFile.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                sendFileOnClick();
            }
        });
        Button btnGetMediaVer = (Button) findViewById(R.id.btnGetMediaVer);
        btnGetMediaVer.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getMediaVerOnClick();
            }
        });
        Button btnCheckFirmFileVer = (Button) findViewById(R.id.btnCheckFirmFileVer);
        btnCheckFirmFileVer.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                checkFirmFileVerOnClick(view);
            }
        });
        Button btnCheckMediaFileVer = (Button) findViewById(R.id.btnCheckMediaFileVer);
        btnCheckMediaFileVer.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                checkMediaFileVerOnClick(view);
            }
        });

        Button btGetSerialNumber = (Button) findViewById(R.id.btGetSerialNumber);
        btGetSerialNumber.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getSerialNumberOnClick();
            }
        });

        Button btGetSystemReport = (Button) findViewById(R.id.btGetSystemReport);
        btGetSystemReport.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getSystemReportOnClick(view);
            }
        });

        Button btGetBatteryInfo = (Button) findViewById(R.id.btGetBatteryInfo);
        btGetBatteryInfo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getBatteryInfoOnClick(view);
            }
        });

        Button btSendDatabase = (Button) findViewById(R.id.btSendDatabase);
        btSendDatabase.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                sendDatabaseOnClick(view);
            }
        });

        // initialization for printing
        mDialog = new MsgDialog(this);
        mHandle = new MsgHandle(this, mDialog);
        myPrint = new TemplateTransfer(this, mHandle, mDialog);

        // when use bluetooth print set the adapter
        BluetoothAdapter bluetoothAdapter = super.getBluetoothAdapter();
        myPrint.setBluetoothAdapter(bluetoothAdapter);

        findViewById(R.id.btnTransfer).setEnabled(false);
        // get data from other application by way of intent sending
        final Bundle extras = getIntent().getExtras();
        if (extras != null) {
            String file = extras.getString(Common.INTENT_FILE_NAME);

            setPdzFile(file);
        }
    }

    /**
     * Called when an activity you launched exits, giving you the requestCode
     * you started it with, the resultCode it returned, and any additional data
     * from it.
     */
    @Override
    protected void onActivityResult(final int requestCode,
                                    final int resultCode, final Intent data) {

        super.onActivityResult(requestCode, resultCode, data);

        // get pdz File and set the new data to display
        if (resultCode == RESULT_OK && requestCode == Common.FILE_SELECT_PDZ) {
            final String strRtn = data.getStringExtra(Common.INTENT_FILE_NAME);
            setPdzFile(strRtn);
        }
    }

    /**
     * set the pdz file
     */
    private void setPdzFile(String file) {
        if (Common.isTemplateFile(file)) {
            TextView txt = (TextView) findViewById(R.id.tvSelectedPdz);
            txt.setText(file);
            ((TemplateTransfer) myPrint).setFile(file);

            findViewById(R.id.btnTransfer).setEnabled(true);
        }
    }

    /**
     * Called when [select] button is tapped
     */
    @Override
    public void selectFileButtonOnClick() {

        // call File Explorer Activity to select a pdz file
        SharedPreferences prefs = PreferenceManager
                .getDefaultSharedPreferences(this);
        final String pdzPath = prefs.getString(Common.PREFES_PDZ_PATH, "");
        final Intent fileList = new Intent(Activity_TransferPdz.this,
                Activity_FileList.class);
        fileList.putExtra(Common.INTENT_TYPE_FLAG, Common.FILE_SELECT_PDZ);
        fileList.putExtra(Common.INTENT_FILE_NAME, pdzPath);
        startActivityForResult(fileList, Common.FILE_SELECT_PDZ);

    }

    /**
     * Called when [Transfer] button is tapped
     */
    private void transferButtonOnClick() {
        if (!checkUSB())
            return;
        ((TemplateTransfer) myPrint).transfer();

    }

    /**
     * Called when [Transfer] button is tapped
     */
    private void updateFirmButtonOnClick() {
        if (!checkUSB())
            return;
        ((TemplateTransfer) myPrint).updateFirm();

    }

    /**
     * Called when [Transfer] button is tapped
     */
    private void getFirmVerOnClick() {
        if (!checkUSB())
            return;
        ((TemplateTransfer) myPrint).getFirmVer();

    }


    /**
     * Called when [Transfer] button is tapped
     */
    private void sendFileOnClick() {
        if (!checkUSB())
            return;
        myPrint.sendFile();

    }


    /**
     * Called when [Transfer] button is tapped
     */
    private void getMediaVerOnClick() {
        if (!checkUSB())
            return;
        ((TemplateTransfer) myPrint).getMediaVer();

    }

    /**
     * Called when [Transfer] button is tapped
     */
    private void getSerialNumberOnClick() {
        if (!checkUSB())
            return;
        ((TemplateTransfer) myPrint).getSerialNum();

    }

    /**
     * Called when [Transfer] button is tapped
     */
    private void checkFirmFileVerOnClick(View view) {
        if (!checkUSB())
            return;
        ((TemplateTransfer) myPrint).getFirmFileVer();

    }

    /**
     * Called when [Transfer] button is tapped
     */
    private void checkMediaFileVerOnClick(View view) {
        if (!checkUSB())
            return;
        ((TemplateTransfer) myPrint).getMediaFileVer();

    }

    /**
     * Called when [Transfer] button is tapped
     */
    private void getSystemReportOnClick(View view) {
        if (!checkUSB())
            return;
        ((TemplateTransfer) myPrint).getSystemReport();
    }

    /**
     * Called when [Get Battery Info] button is tapped
     */
    private void getBatteryInfoOnClick(View view) {
        if (!checkUSB())
            return;
        ((TemplateTransfer) myPrint).getBatteryInfo();
    }

    /**
     * Called when [Send Database] button is tapped
     */
    private void sendDatabaseOnClick(View view) {
        if (!checkUSB()) {
            return;
        }
        ((TemplateTransfer) myPrint).sendDatabase();
    }

    @Override
    public void printButtonOnClick() {
    }
}
