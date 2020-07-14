/**
 * Activity of printing pdz files which is already transfered into the printer.
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo;

import android.bluetooth.BluetoothAdapter;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioGroup;
import android.widget.RadioGroup.OnCheckedChangeListener;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TextView;

import com.brother.ptouch.sdk.printdemo.common.Common;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.common.MsgHandle;
import com.brother.ptouch.sdk.printdemo.printprocess.TemplatePrint;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class Activity_PrintTemplate extends BaseActivity {

    private final ArrayList<HashMap<String, Object>> listItems = new ArrayList<HashMap<String, Object>>();

    private boolean currentInput = false;
    // Called when Template key's data has been changed
    private final TextWatcher watcher = new TextWatcher() {

        @Override
        public void onTextChanged(CharSequence s, int start, int before,
                                  int count) {

        }

        @Override
        public void beforeTextChanged(CharSequence s, int start, int count,
                                      int after) {

        }

        /**
         * Called when Template key's data has been changed
         */
        @Override
        public void afterTextChanged(Editable arg0) {

            if (currentInput) {
                addEndFlg();
                showInputData();
            }
        }
    };
    private Spinner mSpinnerEncoding;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_print_template);

        mDialog = new MsgDialog(this);
        mHandle = new MsgHandle(this, mDialog);
        myPrint = new TemplatePrint(this, mHandle, mDialog);

        // when use bluetooth print set the adapter
        BluetoothAdapter bluetoothAdapter = super.getBluetoothAdapter();
        myPrint.setBluetoothAdapter(bluetoothAdapter);

        // set index & ObjectName EditView invisible
        LinearLayout layoutIndex = (LinearLayout) findViewById(R.id.LinearLayoutIndex);
        LinearLayout layoutObjectName = (LinearLayout) findViewById(R.id.LinearLayoutObjectName);
        layoutIndex.setVisibility(TableLayout.GONE);
        layoutObjectName.setVisibility(TableLayout.GONE);


        Button addButton = (Button) findViewById(R.id.addButton);
        addButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                addOnClick();
            }
        });

        Button deleteButton = (Button) findViewById(R.id.deleteButton);
        deleteButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                deleteOnClick();
            }
        });

        Button nextPrintButton = (Button) findViewById(R.id.nextPrintButton);
        nextPrintButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                nextTemplatePrintOnClick();
            }
        });


        Button btnPrinterSettings = (Button) findViewById(R.id.btnPrinterSettings);
        btnPrinterSettings.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                printerSettingsButtonOnClick();
            }
        });


        Button btnPrint = (Button) findViewById(R.id.btnPrint);
        btnPrint.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                printButtonOnClick();
            }
        });
        btnPrint.setEnabled(false);

        // initialization for RadioGroup
        RadioGroup radioGroup = (RadioGroup) this
                .findViewById(R.id.radioGroupForReplaceText);
        radioGroup.setOnCheckedChangeListener(new OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(RadioGroup group, int checkedId) {

                LinearLayout layoutIndex = (LinearLayout) findViewById(R.id.LinearLayoutIndex);
                LinearLayout layoutObjectName = (LinearLayout) findViewById(R.id.LinearLayoutObjectName);

                switch (checkedId) {
                    case R.id.radio0: // for replaceText
                        layoutIndex.setVisibility(TableLayout.GONE);
                        layoutObjectName.setVisibility(TableLayout.GONE);
                        break;
                    case R.id.radio1: // for replaceTextIndex
                        layoutIndex.setVisibility(TableLayout.VISIBLE);
                        layoutObjectName.setVisibility(TableLayout.GONE);
                        break;
                    case R.id.radio2: // for replaceTextName
                        layoutIndex.setVisibility(TableLayout.GONE);
                        layoutObjectName.setVisibility(TableLayout.VISIBLE);
                        break;
                    default:
                        break;
                }
            }
        });

        // add listener to EditTextView of [Template key]
        ((EditText) this.findViewById(R.id.edtTemplateKey))
                .addTextChangedListener(watcher);
        mSpinnerEncoding = (Spinner) findViewById(R.id.spinnerEncoding);

        String data[] = new String[3];
        data[0] = Common.ENCODING_ENG;
        data[1] = Common.ENCODING_JPN;
        data[2] = Common.ENCODING_CHN;

        // set the pages info. to display
        ArrayAdapter<CharSequence> adapter = new ArrayAdapter<CharSequence>(
                this, android.R.layout.simple_spinner_item, data);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        mSpinnerEncoding.setAdapter(adapter);
        mSpinnerEncoding.setSelection(0);

    }

    /**
     * Called when [Add] button is tapped
     */
    private void addOnClick() {

        if (!checkNewInputData()) {
            if (!currentInput) {
                addStartFlg();
            }

            setNewInputData();
            showInputData();
        }
    }

    /**
     * Called when [Delete] button is tapped
     */
    private void deleteOnClick() {

        int lastItemIndex = listItems.size() - 1;

        // delete the latest input data
        if (lastItemIndex == 1) {
            listItems.clear();
            currentInput = false;
        } else if (lastItemIndex > 1) {

            Map<String, Object> mapData;
            mapData = listItems.get(lastItemIndex);

            if (Integer.parseInt(mapData.get(Common.TEMPLATE_REPLACE_TYPE)
                    .toString()) == Common.TEMPLATE_REPLACE_TYPE_END) {
                currentInput = true;
            } else if (Integer.parseInt(mapData.get(
                    Common.TEMPLATE_REPLACE_TYPE).toString()) == Common.TEMPLATE_REPLACE_TYPE_START) {
                currentInput = false;
            }

            listItems.remove(lastItemIndex);

            // delete the start flag if it is the first input data in each
            // template's input data
            mapData = listItems.get(lastItemIndex - 1);
            if (Integer.parseInt(mapData.get(Common.TEMPLATE_REPLACE_TYPE)
                    .toString()) == Common.TEMPLATE_REPLACE_TYPE_START) {
                currentInput = false;
                listItems.remove(lastItemIndex - 1);
            }
        }

        // update the input data for screen display
        showInputData();
    }

    /**
     * Called when [Next Print] button is tapped
     */
    private void nextTemplatePrintOnClick() {

        // set the end flag and refresh the display
        if (currentInput) {
            addEndFlg();
            showInputData();
        }
    }

    /**
     * Called when [Print] button is tapped
     */
    @Override
    public void printButtonOnClick() {
        if (!checkUSB())
            return;
        // set the end flag and refresh the display
        if (currentInput) {
            addEndFlg();
            showInputData();
        }

        // do the print
        if (listItems.size() > 0) {

            ((TemplatePrint) myPrint).setEncoding((String) mSpinnerEncoding
                    .getSelectedItem());

            ((TemplatePrint) myPrint).setPrintData(listItems);
            myPrint.print();
        }
    }

    /**
     * Check the input data. Show error if no index or object name is input.
     */
    private boolean checkNewInputData() {

        RadioGroup radioGroup = (RadioGroup) this
                .findViewById(R.id.radioGroupForReplaceText);
        boolean errorInput = false;

        switch (radioGroup.getCheckedRadioButtonId()) {
            case R.id.radio0: // for replaceText
                break;

            case R.id.radio1: // for replaceTextIndex
                String index = ((EditText) findViewById(R.id.edtIndex)).getText()
                        .toString();

                // error if no index is input
                if (index.equalsIgnoreCase("")) {
                    errorInput = true;
                }
                break;

            case R.id.radio2: // for replaceTextName
                String objectName = ((EditText) findViewById(R.id.edtObjectName))
                        .getText().toString();

                // error if no object name is input
                if (objectName.equalsIgnoreCase("")) {
                    errorInput = true;
                }
                break;

            default:
                break;
        }

        // show the wrong input message
        if (errorInput) {
            mDialog.showAlertDialog(getString(R.string.msg_title_warning),
                    getString(R.string.error_input));
        }

        return errorInput;
    }

    /**
     * Store the input data for printing
     */
    private void setNewInputData() {

        RadioGroup radioGroup = (RadioGroup) this
                .findViewById(R.id.radioGroupForReplaceText);
        HashMap<String, Object> mapData = new HashMap<String, Object>();

        switch (radioGroup.getCheckedRadioButtonId()) {
            case R.id.radio0: // for replaceText
                mapData.put(Common.TEMPLATE_REPLACE_TYPE,
                        Common.TEMPLATE_REPLACE_TYPE_TEXT);
                break;

            case R.id.radio1: // for replaceTextIndex
                mapData.put(Common.TEMPLATE_REPLACE_TYPE,
                        Common.TEMPLATE_REPLACE_TYPE_INDEX);
                String index = ((EditText) findViewById(R.id.edtIndex)).getText()
                        .toString();

                mapData.put(Common.TEMPLATE_OBJECTNAME_INDEX, index);

                break;

            case R.id.radio2: // for replaceTextName
                mapData.put(Common.TEMPLATE_REPLACE_TYPE,
                        Common.TEMPLATE_REPLACE_TYPE_NAME);
                String objectName = ((EditText) findViewById(R.id.edtObjectName))
                        .getText().toString();

                mapData.put(Common.TEMPLATE_OBJECTNAME_INDEX, objectName);

                break;

            default:
                break;
        }

        EditText edtTextForReplace = (EditText) findViewById(R.id.edtTextForReplace);
        mapData.put(Common.TEMPLATE_REPLACE_TEXT, edtTextForReplace.getText()
                .toString());

        listItems.add(mapData);

    }

    /**
     * Add start flag for multiple pdz's print
     */
    private void addStartFlg() {

        String key = getTemplateKey();

        if (!key.equalsIgnoreCase("")) {
            HashMap<String, Object> mapData = new HashMap<String, Object>();
            mapData.put(Common.TEMPLATE_REPLACE_TYPE,
                    Common.TEMPLATE_REPLACE_TYPE_START);

            mapData.put(Common.TEMPLATE_KEY, key);
            listItems.add(mapData);
            currentInput = true;
        }
    }

    /**
     * Add end flag for multiple pdz's print
     */
    private void addEndFlg() {

        HashMap<String, Object> mapData = new HashMap<String, Object>();
        mapData.put(Common.TEMPLATE_REPLACE_TYPE,
                Common.TEMPLATE_REPLACE_TYPE_END);
        listItems.add(mapData);
        currentInput = false;
    }

    /**
     * Get the template key
     */
    private String getTemplateKey() {

        String strKey = ((EditText) this.findViewById(R.id.edtTemplateKey))
                .getText().toString();
        if (strKey.equalsIgnoreCase("")) {
            mDialog.showAlertDialog(getString(R.string.msg_title_warning),
                    getString(R.string.error_input));
        }

        return strKey;
    }

    /**
     * set the input data for replace
     */
    private void showInputData() {

        int count = listItems.size();
        String strInputData = "";

        for (int i = 0; i < count; i++) {
            Map<String, Object> map;
            map = listItems.get(i);

            switch (Integer.parseInt(map.get(Common.TEMPLATE_REPLACE_TYPE)
                    .toString())) {
                case Common.TEMPLATE_REPLACE_TYPE_TEXT: // for replaceText
                    strInputData = strInputData + "Text:"
                            + map.get(Common.TEMPLATE_REPLACE_TEXT).toString()
                            + "\n";
                    break;
                case Common.TEMPLATE_REPLACE_TYPE_INDEX: // for replaceTextIndex
                    strInputData = strInputData + "Index:"
                            + map.get(Common.TEMPLATE_OBJECTNAME_INDEX).toString()
                            + " " + "Text:"
                            + map.get(Common.TEMPLATE_REPLACE_TEXT).toString()
                            + "\n";
                    break;
                case Common.TEMPLATE_REPLACE_TYPE_NAME: // for replaceTextName
                    strInputData = strInputData + "ObjectName:"
                            + map.get(Common.TEMPLATE_OBJECTNAME_INDEX).toString()
                            + " " + "Text:"
                            + map.get(Common.TEMPLATE_REPLACE_TEXT).toString()
                            + "\n";
                    break;

                case Common.TEMPLATE_REPLACE_TYPE_START: // for replaceText
                    strInputData = strInputData + "Start template key:"
                            + map.get(Common.TEMPLATE_KEY).toString() + "\n";
                    break;
                case Common.TEMPLATE_REPLACE_TYPE_END: // for replaceText
                    strInputData = strInputData + "End \n";
                    break;
                default:
                    break;
            }
        }

        TextView tvInputData = (TextView) findViewById(R.id.tvInputData);
        tvInputData.setText(strInputData);

        // set the [print] button enable if the replace data is input, otherwise
        // disable
        if (count > 1) {
            findViewById(R.id.btnPrint).setEnabled(true);
        } else {
            findViewById(R.id.btnPrint).setEnabled(false);
        }
    }

    @Override
    public void selectFileButtonOnClick() {

    }
}
