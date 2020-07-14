/**
 * Launcher Activity
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo;

import android.Manifest;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.preference.PreferenceManager;
import android.provider.MediaStore;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.Toast;

import com.brother.ptouch.sdk.Printer;
import com.brother.ptouch.sdk.PrinterInfo;
import com.brother.ptouch.sdk.printdemo.common.Common;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.printprocess.PrinterModelInfo;

import java.io.BufferedInputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Activity_StartMenu extends Activity {

    private final int PERMISSION_WRITE_EXTERNAL_STORAGE = 10001;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_startmenu);
        init();
        setListView();
        getDataFromIntent();
        // initialize the SharedPreferences
        setPreferences();
    }

    private void init() {
            /*
         * copy .bin file (RJ paper size info.) to
		 * /mnt/sdcard/customPaperFileSet/ .bin file is made by Printer Setting
		 * Tool which can be downloaded from the Brother Net Site
		 */
        try {
            raw2file("RJ4030_102mm.bin", R.raw.rj4030_102mm);
            raw2file("RJ4030_102mm152mm.bin", R.raw.rj4030_102mm152mm);
            raw2file("RJ4040_102mm.bin", R.raw.rj4040_102mm);
            raw2file("RJ4040_102mm152mm.bin", R.raw.rj4040_102mm152mm);
            raw2file("RJ4030Ai_102mm.bin", R.raw.rj4030ai_102mm);
            raw2file("RJ4030Ai_102mm152mm.bin", R.raw.rj4030ai_102mm152mm);

            raw2file("RJ3050_76mm.bin", R.raw.rj3050_76mm);
            raw2file("RJ3150_76mm.bin", R.raw.rj3150_76mm);
            raw2file("RJ3150_76mm44mm.bin", R.raw.rj3150_76mm44mm);
            raw2file("RJ3050Ai_76mm.bin", R.raw.rj3050ai_76mm);
            raw2file("RJ3150Ai_76mm.bin", R.raw.rj3150ai_76mm);
            raw2file("RJ3150Ai_76mm44mm.bin", R.raw.rj3150ai_76mm44mm);
            raw2file("RJ2030_50mm.bin", R.raw.rj2030_50mm);
            raw2file("RJ2050_50mm.bin", R.raw.rj2050_50mm);
            raw2file("RJ2030_58mm.bin", R.raw.rj2030_58mm);
            raw2file("RJ2050_58mm.bin", R.raw.rj2050_58mm);
            raw2file("RJ2140_58mm.bin", R.raw.rj2140_58mm);
            raw2file("RJ2140_50x85mm.bin", R.raw.rj2140_50x85mm);
            raw2file("RJ2150_58mm.bin", R.raw.rj2150_58mm);
            raw2file("RJ2150_50x85mm.bin", R.raw.rj2150_50x85mm);


            raw2file("TD2020_57mm.bin", R.raw.td2020_57mm);
            raw2file("TD2020_40mm40mm.bin", R.raw.td2020_40mm40mm);
            raw2file("TD2120_57mm.bin", R.raw.td2120_57mm);
            raw2file("TD2120_40mm40mm.bin", R.raw.td2120_40mm40mm);
            raw2file("TD2130_57mm.bin", R.raw.td2130_57mm);
            raw2file("TD2130_40mm40mm.bin", R.raw.td2130_40mm40mm);
            raw2file("TD4100N_102mm.bin", R.raw.td4100n_102mm);
            raw2file("TD4100N_102mm152mm.bin", R.raw.td4100n_102mmx152mm);
            raw2file("TD4000_102mm.bin", R.raw.td4000_102mm);
            raw2file("TD4000_102mm152mm.bin", R.raw.td4000_102mmx152mm);
            raw2file("TD4410D_102mm.bin", R.raw.td4410d_102mm);
            raw2file("TD4410D_102mm152mm.bin", R.raw.td4410d_102mm152mm);
            raw2file("TD4420DN_102mm.bin", R.raw.td4420dn_102mm);
            raw2file("TD4420DN_102mm152mm.bin", R.raw.td4420dn_102mm152mm);
            raw2file("TD4510D_102mm.bin", R.raw.td4510d_102mm);
            raw2file("TD4510D_102mm152mm.bin", R.raw.td4510d_102mm152mm);
            raw2file("TD4520DN_102mm.bin", R.raw.td4520dn_102mm);
            raw2file("TD4520DN_102mm152mm.bin", R.raw.td4520dn_102mm152mm);
            raw2file("TD4550DNWB_102mm.bin", R.raw.td4550dnwb_102mm);
            raw2file("TD4550DNWB_102mm152mm.bin", R.raw.td4550dnwb_102mm152mm);

            raw2file("RJ4230B_58mm.bin", R.raw.rj4230b_58mm);
            raw2file("RJ4230B_102mm.bin", R.raw.rj4230b_102mm);
            raw2file("RJ4230B_102mm152mm.bin", R.raw.rj4230b_102mm152mm);
            raw2file("RJ4250WB_58mm.bin", R.raw.rj4250wb_58mm);
            raw2file("RJ4250WB_102mm.bin", R.raw.rj4250wb_102mm);
            raw2file("RJ4250WB_102mm152mm.bin", R.raw.rj4250wb_102mm152mm);
        } catch (Exception ignored) {
        }

    }

    private boolean isPermitWriteStorage() {

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            if (checkSelfPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE)
                    != PackageManager.PERMISSION_GRANTED) {
                return false;
            }
        }
        return true;
    }

    @TargetApi(Build.VERSION_CODES.M)
    @Override
    protected void onStart() {
        super.onStart();
        if (!isPermitWriteStorage()) {
            requestPermissions(
                    new String[]{Manifest.permission.WRITE_EXTERNAL_STORAGE},
                    PERMISSION_WRITE_EXTERNAL_STORAGE);
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode,
                                           String permissions[], int[] grantResults) {
        switch (requestCode) {
            case PERMISSION_WRITE_EXTERNAL_STORAGE: {
                if (grantResults.length == 0
                        || grantResults[0] != PackageManager.PERMISSION_GRANTED) {
                    Toast.makeText(this, getString(R.string.unable_access),
                            Toast.LENGTH_SHORT).show();
                } else {
                    init();
                }
            }

        }
    }

    private void setPreferences() {
        SharedPreferences sharedPreferences = PreferenceManager
                .getDefaultSharedPreferences(this);
        // initialization for print
        Printer printer = new Printer();
        PrinterInfo printerInfo = printer.getPrinterInfo();
        if (printerInfo == null) {
            printerInfo = new PrinterInfo();
            printer.setPrinterInfo(printerInfo);

        }
        if (sharedPreferences.getString("printerModel", "").equals("")) {
            String printerModel = printerInfo.printerModel.toString();
            PrinterModelInfo.Model model = PrinterModelInfo.Model.valueOf(printerModel);

            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.putString("printerModel", printerModel);
            editor.putString("port", printerInfo.port.toString());
            editor.putString("address", printerInfo.ipAddress);
            editor.putString("macAddress", printerInfo.macAddress);
            editor.putString("localName", printerInfo.getLocalName());

            // Override SDK default paper size
            editor.putString("paperSize", model.getDefaultPaperSize());

            editor.putString("orientation", printerInfo.orientation.toString());
            editor.putString("numberOfCopies",
                    Integer.toString(printerInfo.numberOfCopies));
            editor.putString("halftone", printerInfo.halftone.toString());
            editor.putString("printMode", printerInfo.printMode.toString());
            editor.putString("pjCarbon", Boolean.toString(printerInfo.pjCarbon));
            editor.putString("pjDensity",
                    Integer.toString(printerInfo.pjDensity));
            editor.putString("pjFeedMode", printerInfo.pjFeedMode.toString());
            editor.putString("align", printerInfo.align.toString());
            editor.putString("leftMargin",
                    Integer.toString(printerInfo.margin.left));
            editor.putString("valign", printerInfo.valign.toString());
            editor.putString("topMargin",
                    Integer.toString(printerInfo.margin.top));
            editor.putString("customPaperWidth",
                    Integer.toString(printerInfo.customPaperWidth));
            editor.putString("customPaperLength",
                    Integer.toString(printerInfo.customPaperLength));
            editor.putString("customFeed",
                    Integer.toString(printerInfo.customFeed));
            editor.putString("paperPosition",
                    printerInfo.paperPosition.toString());
            editor.putString("customSetting",
                    sharedPreferences.getString("customSetting", ""));
            editor.putString("rjDensity",
                    Integer.toString(printerInfo.rjDensity));
            editor.putString("rotate180",
                    Boolean.toString(printerInfo.rotate180));
            editor.putString("dashLine", Boolean.toString(printerInfo.dashLine));

            editor.putString("peelMode", Boolean.toString(printerInfo.peelMode));
            editor.putString("mode9", Boolean.toString(printerInfo.mode9));
            editor.putString("pjSpeed", Integer.toString(printerInfo.pjSpeed));
            editor.putString("pjPaperKind", printerInfo.pjPaperKind.toString());
            editor.putString("printerCase",
                    printerInfo.rollPrinterCase.toString());
            editor.putString("printQuality", printerInfo.printQuality.toString());
            editor.putString("skipStatusCheck",
                    Boolean.toString(printerInfo.skipStatusCheck));
            editor.putString("checkPrintEnd", printerInfo.checkPrintEnd.toString());
            editor.putString("imageThresholding",
                    Integer.toString(printerInfo.thresholdingValue));
            editor.putString("scaleValue",
                    Double.toString(printerInfo.scaleValue));
            editor.putString("trimTapeAfterData",
                    Boolean.toString(printerInfo.trimTapeAfterData));
            editor.putString("enabledTethering",
                    Boolean.toString(printerInfo.enabledTethering));


            editor.putString("processTimeout",
                    Integer.toString(printerInfo.timeout.processTimeoutSec));
            editor.putString("sendTimeout",
                    Integer.toString(printerInfo.timeout.sendTimeoutSec));
            editor.putString("receiveTimeout",
                    Integer.toString(printerInfo.timeout.receiveTimeoutSec));
            editor.putString("connectionTimeout",
                    Integer.toString(printerInfo.timeout.connectionWaitMSec));
            editor.putString("closeWaitTime",
                    Integer.toString(printerInfo.timeout.closeWaitDisusingStatusCheckSec));

            editor.putString("overwrite",
                    Boolean.toString(printerInfo.overwrite));
            editor.putString("savePrnPath", printerInfo.savePrnPath);
            editor.putString("softFocusing",
                    Boolean.toString(printerInfo.softFocusing));
            editor.putString("rawMode",
                    Boolean.toString(printerInfo.rawMode));
            editor.putString("workPath", printerInfo.workPath);
            editor.putString("useLegacyHalftoneEngine",
                    Boolean.toString(printerInfo.useLegacyHalftoneEngine));
            editor.apply();
        }

    }

    /**
     * set the launcher's items
     */
    private void setListView() {
        final Map<Object, Object> activityClass = new HashMap<Object, Object>();
        activityClass.put(0, Activity_PrintImage.class);
        activityClass.put(1, Activity_PrintPdf.class);
        activityClass.put(2, Activity_PrintTemplate.class);
        activityClass.put(3, Activity_ManageTemplate.class);
        activityClass.put(4, Activity_PrinterPreference.class);

        List<Map<String, Object>> listItems = new ArrayList<Map<String, Object>>();
        ArrayList<String> mListItems = new ArrayList<String>();
        mListItems.add(getString(R.string.text_print_image));
        mListItems.add(getString(R.string.text_print_pdf));
        mListItems.add(getString(R.string.text_print_template));
        mListItems.add(getString(R.string.text_transfer_manager));
        mListItems.add(getString(R.string.text_device_setting));

        int count = mListItems.size();
        Map<String, Object> listItem;

        for (int i = 0; i < count; i++) {
            listItem = new HashMap<String, Object>();
            listItem.put(Common.INTENT_FILE_NAME, mListItems.get(i));
            listItems.add(listItem);
        }

        SimpleAdapter adapter = new SimpleAdapter(this, listItems,
                R.layout.xml_list_item,
                new String[]{Common.INTENT_FILE_NAME},
                new int[]{R.id.text});

        ListView lvFiles = (ListView) this.findViewById(R.id.list);
        lvFiles.setAdapter(adapter);
        lvFiles.setOnItemClickListener(new OnItemClickListener() {
            @TargetApi(Build.VERSION_CODES.M)
            @Override
            public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
                                    long arg3) {
                if (!isPermitWriteStorage()) {
                    requestPermissions(
                            new String[]{Manifest.permission.WRITE_EXTERNAL_STORAGE},
                            PERMISSION_WRITE_EXTERNAL_STORAGE);
                    return;
                }

                Intent intent = new Intent(arg0.getContext(),
                        (Class<?>) activityClass.get(arg2));
                arg0.getContext().startActivity(intent);
            }
        });
    }

    private String parseFileName(String fileName) {
        if (fileName.contains("content://")) {
            if (getIntent().getExtras() == null) {
                return "";
            }
            final Uri imageUri = Uri.parse(
                    getIntent().getExtras().get("android.intent.extra.STREAM").toString());
            String[] projection = {MediaStore.Images.Media.DATA};
            Cursor cursor = getContentResolver().query(imageUri, projection, null, null, null);
            if (cursor == null) {
                return "";
            }
            int columnIndex = 0;
            try {
                columnIndex = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
            } catch (IllegalArgumentException e) {
                return "";
            }
            cursor.moveToFirst();
            fileName = cursor.getString(columnIndex);
            cursor.close();
        } else if (fileName.contains("file://")) {
            fileName = Uri.decode(fileName);
            fileName = fileName.substring(7);
        }
        return fileName;
    }

    private String saveDataFromIntent(Intent intent) {
        String fileName = "";

        try {
            Uri uri;
            if (Intent.ACTION_SEND.equals(intent.getAction())) {
                uri = intent.getParcelableExtra(Intent.EXTRA_STREAM);
            } else {
                uri = getIntent().getData();
            }
            if (uri == null) {
                return "";
            }
            Cursor cursor = getContentResolver().query(uri, new String[]{
                    MediaStore.MediaColumns.DISPLAY_NAME
            }, null, null, null);
            if (cursor == null) {
                return "";
            }
            cursor.moveToFirst();
            int nameIndex = cursor.getColumnIndex(MediaStore.MediaColumns.DISPLAY_NAME);

            String folder = Environment.getExternalStorageDirectory()
                    .toString() + "/com.brother.ptouch.sdk/";
            File newdir = new File(folder);
            if (!newdir.exists()) {
                newdir.mkdir();
            }

            if (nameIndex >= 0) {
                fileName = folder + cursor.getString(nameIndex);
            }
            cursor.close();
            File dstFile = new File(fileName);
            OutputStream output = new FileOutputStream(dstFile);
            InputStream input = new BufferedInputStream(getContentResolver().openInputStream(uri));

            int DEFAULT_BUFFER_SIZE = 1024 * 4;
            byte[] buffer = new byte[DEFAULT_BUFFER_SIZE];
            int n;
            while (-1 != (n = input.read(buffer))) {
                output.write(buffer, 0, n);
            }
            input.close();
            output.close();
        } catch (IOException e) {
            fileName = "";
        }


        return fileName;
    }

    /**
     * launch by intent sending
     */
    private void getDataFromIntent() {

        Intent intent = getIntent();
        if (intent == null) {
            return;
        }
        // Get file path from intent
        if (Intent.ACTION_SEND.equals(intent.getAction())
                || Intent.ACTION_VIEW.equals(intent.getAction())) {

            String fileName = "";
            // get the data of Intent.ACTION_SEND from other application
            if (Intent.ACTION_SEND.equals(intent.getAction())) {
                if (intent.getExtras() == null) {
                    return;
                }
                fileName = intent.getExtras()
                        .get("android.intent.extra.STREAM").toString();
                fileName = parseFileName(fileName);

            } else {

                Uri uri = intent.getData();
                if (uri == null) {
                    return;
                }
                fileName = uri.toString();
                if (fileName == null) {
                    return;
                }
                fileName = parseFileName(fileName);
            }
            if (fileName == null || "".equals(fileName)) {
                fileName = saveDataFromIntent(intent);
            }

            if (fileName == null || fileName.equals("")) {
                return;
            }
            // launch the PrintImage Activity when it is a image file or prn
            // file
            if (Common.isImageFile(fileName) || Common.isPrnFile(fileName)) {
                Intent printerList = new Intent(this, Activity_PrintImage.class);
                printerList.putExtra(Common.INTENT_FILE_NAME, fileName);
                startActivity(printerList);
            }
            // launch the PrintPdf Activity when it is a pdf file
            else if (Common.isPdfFile(fileName)) {
                Intent printerList = new Intent(this, Activity_PrintPdf.class);
                printerList.putExtra(Common.INTENT_FILE_NAME, fileName);
                startActivity(printerList);
            }
            // launch the TransferPdz Activity when it is a pdz file
            else if (Common.isTemplateFile(fileName)) {
                Intent printerList = new Intent(this,
                        Activity_TransferPdz.class);
                printerList.putExtra(Common.INTENT_FILE_NAME, fileName);
                startActivity(printerList);
            }
        }
    }

    /**
     * Called when the menu key is tapped
     */
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.mainmenu_option, menu);
        return super.onCreateOptionsMenu(menu);
    }

    /**
     * Called when the menu's item is tapped
     */
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case R.id.option_menu_setting: // printer settings
                startActivity(new Intent(this, Activity_Settings.class));
                break;
            case R.id.option_menu_about: // about dialog
                MsgDialog msgDialog = new MsgDialog(this);
                msgDialog.showAlertDialog(getString(R.string.about_title),
                        getString(R.string.about_text));
                break;
            default:
                break;
        }
        return super.onOptionsItemSelected(item);
    }

    /**
     * Called when the application is closed
     */
    @Override
    protected void onDestroy() {
        super.onDestroy();
        android.os.Process.killProcess(android.os.Process.myPid());
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
     * show the closing message
     */
    private void showTips() {

        final AlertDialog alertDialog = new AlertDialog.Builder(this)
                .setTitle(R.string.end_title)
                .setMessage(R.string.close_message)
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
     * copy from raw in resource
     */
    private void raw2file(String fileName, int fileID) {

        File newdir = new File(Common.CUSTOM_PAPER_FOLDER);
        if (!newdir.exists()) {
            newdir.mkdir();
        }
        File dstFile = new File(Common.CUSTOM_PAPER_FOLDER + fileName);
        if (!dstFile.exists()) {
            try {
                InputStream input;
                OutputStream output;
                input = this.getResources().openRawResource(fileID);
                output = new FileOutputStream(dstFile);
                int DEFAULT_BUFFER_SIZE = 1024 * 4;
                byte[] buffer = new byte[DEFAULT_BUFFER_SIZE];
                int n;
                while (-1 != (n = input.read(buffer))) {
                    output.write(buffer, 0, n);
                }
                input.close();
                output.close();
            } catch (IOException ignored) {
            }
        }
    }
}