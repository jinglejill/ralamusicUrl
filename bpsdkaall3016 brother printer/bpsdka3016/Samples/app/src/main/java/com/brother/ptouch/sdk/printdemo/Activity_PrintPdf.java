/**
 * Activity of printing pdf files
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
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.Spinner;
import android.widget.TextView;

import com.brother.ptouch.sdk.printdemo.common.Common;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.common.MsgHandle;
import com.brother.ptouch.sdk.printdemo.printprocess.PdfPrint;

public class Activity_PrintPdf extends BaseActivity {

    private Spinner mSpinnerStartPage;
    private Spinner mSpinnerEndPage;
    private CheckBox mChkAllPages;
    private Button mBtnPrint;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_print_pdf);

        // initialization for Activity
        mBtnPrint = (Button) findViewById(R.id.btnPrint);
        mBtnPrint.setEnabled(false);
        mBtnPrint.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                printButtonOnClick();
            }
        });

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


        // initialization for printing
        mDialog = new MsgDialog(this);
        mHandle = new MsgHandle(this, mDialog);
        myPrint = new PdfPrint(this, mHandle, mDialog);

        // set the adapter when printing by way of Bluetooth
        BluetoothAdapter bluetoothAdapter = super.getBluetoothAdapter();
        myPrint.setBluetoothAdapter(bluetoothAdapter);

        mSpinnerStartPage = (Spinner) findViewById(R.id.spinnerStartPage);
        mSpinnerEndPage = (Spinner) findViewById(R.id.spinnerEndPage);
        mSpinnerStartPage.setEnabled(false);
        mSpinnerEndPage.setEnabled(false);

        mChkAllPages = (CheckBox) this.findViewById(R.id.chkAllPages);
        mChkAllPages.setEnabled(false);
        mChkAllPages.setOnCheckedChangeListener(new OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton arg0, boolean arg1) {

                mSpinnerStartPage.setEnabled(!arg1);
                mSpinnerEndPage.setEnabled(!arg1);
            }
        });

        // get data from other application by way of intent sending
        final Bundle extras = getIntent().getExtras();
        if (extras != null) {
            String file = extras.getString(Common.INTENT_FILE_NAME);
            setPdfFile(file);
        }
    }

    /**
     * Called when [Select] button is tapped
     */
    @Override
    public void selectFileButtonOnClick() {

        // call File Explorer Activity to select a pdf file
        SharedPreferences prefs = PreferenceManager
                .getDefaultSharedPreferences(this);
        final String pdfPath = prefs.getString(Common.PREFES_PDF_PATH, "");
        final Intent fileList = new Intent(Activity_PrintPdf.this,
                Activity_FileList.class);
        fileList.putExtra(Common.INTENT_TYPE_FLAG, Common.FILE_SELECT_PDF);
        fileList.putExtra(Common.INTENT_FILE_NAME, pdfPath);
        startActivityForResult(fileList, Common.FILE_SELECT_PDF);

    }

    /**
     * Called when [Print] button is tapped
     */
    @Override
    public void printButtonOnClick() {
        if (!checkUSB())
            return;
        int startPage;
        int endPage;

        // All pages
        if (mChkAllPages.isChecked()) {
            startPage = 1;
            endPage = mSpinnerEndPage.getCount();
        } else { // set pages
            startPage = Integer.parseInt((String) mSpinnerStartPage
                    .getSelectedItem());
            endPage = Integer.parseInt((String) mSpinnerEndPage
                    .getSelectedItem());
        }

        // error if startPage > endPage
        if (startPage > endPage) {
            mDialog.showAlertDialog(getString(R.string.msg_title_warning),
                    getString(R.string.error_input));
            return;
        }

        // call function to print
        ((PdfPrint) myPrint).setPrintPage(startPage, endPage);
        myPrint.print();

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

        // get pdf File and set the new data to display
        if (resultCode == RESULT_OK && requestCode == Common.FILE_SELECT_PDF) {
            final String strRtn = data.getStringExtra(Common.INTENT_FILE_NAME);
            setPdfFile(strRtn);
        }
    }

    /**
     * set the pdf file for printing
     */
    private void setPdfFile(String file) {

        if (Common.isPdfFile(file)) {
            TextView txt = (TextView) findViewById(R.id.tvSelectedPdf);
            txt.setText(file);
            setSpinnerData(file);
            mChkAllPages.setEnabled(true);
            mChkAllPages.setChecked(true);
            mBtnPrint.setEnabled(true);
            ((PdfPrint) myPrint).setFiles(file);
        }
    }

    /**
     * set the data of Spinners
     */
    private void setSpinnerData(String pdfFile) {

        // get the pages info. of the pdf file
        int pages = ((PdfPrint) myPrint).getPdfPages(pdfFile);
        String data[] = new String[pages];
        for (int i = 0; i < pages; i++) {
            data[i] = String.valueOf(i + 1);
        }

        // set the pages info. to display
        ArrayAdapter<CharSequence> adapter = new ArrayAdapter<CharSequence>(
                this, android.R.layout.simple_spinner_item, data);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        mSpinnerStartPage.setAdapter(adapter);
        mSpinnerStartPage.setSelection(0);

        mSpinnerEndPage.setAdapter(adapter);
        mSpinnerEndPage.setSelection(pages - 1);

    }
}
