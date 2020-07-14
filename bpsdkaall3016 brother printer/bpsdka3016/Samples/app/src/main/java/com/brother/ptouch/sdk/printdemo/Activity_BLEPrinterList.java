package com.brother.ptouch.sdk.printdemo;

import android.Manifest;
import android.annotation.TargetApi;
import android.app.ListActivity;
import android.bluetooth.BluetoothAdapter;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.ListView;

import com.brother.ptouch.sdk.BLEPrinter;
import com.brother.ptouch.sdk.Printer;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;

import java.util.ArrayList;
import java.util.List;

public class Activity_BLEPrinterList extends ListActivity {
    private static final int REQUEST_CODE_COARSE_LOCATION = 1;
    private static final int SEARCH_TIME = 5000; // msec
    private final MsgDialog mMsgDialog = new MsgDialog(this);
    private List<BLEPrinter> mPrinterList;
    private List<String> mItemList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bleprinterlist);
        this.setTitle("BLE Printer");

        findViewById(R.id.btnRefresh).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                checkPermissionThenStartSearch();
            }
        });

        // TODO:
        findViewById(R.id.btPrinterSettings).setEnabled(false);

        checkPermissionThenStartSearch();
    }

    @Override
    protected void onListItemClick(ListView listView, View view, int position, long id) {
        final BLEPrinter printer = mPrinterList.get(position);
        final Intent settings = new Intent(this, Activity_Settings.class);
        settings.putExtra("ipAddress", "");
        settings.putExtra("macAddress", "");
        settings.putExtra("localName", printer.localName);
        settings.putExtra("printer", printer.localName);
        setResult(RESULT_OK, settings);
        finish();
    }

    @TargetApi(Build.VERSION_CODES.M)
    private void checkPermissionThenStartSearch() {
        // 実行時パーミッションが必要な OS でパーミッションが付加されていなければリクエストする
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M &&
                (checkSelfPermission(Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED)) {
            requestPermissions(new String[]{Manifest.permission.ACCESS_COARSE_LOCATION}, REQUEST_CODE_COARSE_LOCATION);
        } else {
            startSearch();
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, String permissions[], int[] grantResults) {
        switch (requestCode) {
            case REQUEST_CODE_COARSE_LOCATION: {
                if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    startSearch();
                }
            }

        }
    }

    private void startSearch() {
        mMsgDialog.showMsgNoButton("BLE Printer", "Searching...");
        new Thread(new Runnable(){
            public void run() {
                Printer printer = new Printer();
                mPrinterList = printer.getBLEPrinters(BluetoothAdapter.getDefaultAdapter(), SEARCH_TIME);
                handleSearchFinish(mPrinterList);
            }
        }).start();
    }

    private void handleSearchFinish(List<BLEPrinter> printerList) {
        mItemList = new ArrayList<>();
        for (BLEPrinter printer: printerList) {
            mItemList.add(printer.localName + "\n\n");
        }
        final ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.test_list_item, mItemList);
        this.runOnUiThread(new Runnable() {
            @Override
            public void run() {
                mMsgDialog.close();
                Activity_BLEPrinterList.this.setListAdapter(adapter);
            }
        });
    }
}
