package com.brother.ptouch.sdk.printdemo;

import android.app.Activity;
import android.bluetooth.BluetoothAdapter;
import android.content.res.Configuration;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;

import com.brother.ptouch.sdk.PrinterInfo.ErrorCode;
import com.brother.ptouch.sdk.PrinterStatus;
import com.brother.ptouch.sdk.TemplateInfo;
import com.brother.ptouch.sdk.printdemo.TemplateListAdapter.TempInfo;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.common.MsgHandle;
import com.brother.ptouch.sdk.printdemo.printprocess.TemplateRemove;
import com.brother.ptouch.sdk.printdemo.printprocess.TemplateRemove.TemplateRemoveListener;

import java.util.ArrayList;
import java.util.List;

@SuppressWarnings("ALL")
public class Activity_RemoveTemplate extends BaseActivity {

    private final Handler handler = new Handler();
    private TemplateListAdapter mListAdapter = null;
    private ListView mListView = null;
    private TextView mNoDataTextView;
    private Activity mActivity;
    private final TemplateRemoveListener listener = new TemplateRemoveListener() {

        @Override
        public void finish(final PrinterStatus status,
                           final List<TemplateInfo> tmplList) {
            handler.post(new Runnable() {

                @Override
                public void run() {
                    if (status.errorCode == ErrorCode.ERROR_NONE) {
                        if (tmplList != null && tmplList.size() > 0) {
                            setTeamplate(tmplList);
                        }

                    } else {
                        displayNoFile();
                    }
                }
            });
        }

    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_remove_template);

        Button btnPrinterSetting = (Button) findViewById(R.id.btnPrinterSetting);
        btnPrinterSetting.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                printerSettingsButtonOnClick();
            }
        });

        Button btnRemoveTemplate = (Button) findViewById(R.id.btnRemoveTemplate);
        btnRemoveTemplate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                removeTemplateButtonOnClick();
            }
        });
        Button btnTemplateList = (Button) findViewById(R.id.btnTemplateList);
        btnTemplateList.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getTemplateButtonOnClick();
            }
        });


        mActivity = this;

        mDialog = new MsgDialog(this);
        mHandle = new MsgHandle(this, mDialog);
        myPrint = new TemplateRemove(this, mHandle, mDialog);
        BluetoothAdapter bluetoothAdapter = super.getBluetoothAdapter();
        myPrint.setBluetoothAdapter(bluetoothAdapter);

        mNoDataTextView = (TextView) findViewById(R.id.text_no_data);
        mNoDataTextView.setVisibility(View.VISIBLE);
        mListView = (ListView) this.findViewById(R.id.list_template_list);
        mListView.setVisibility(View.GONE);

    }

    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
    }

    /**
     * Called when [Remove Template] button is tapped
     */
    private void removeTemplateButtonOnClick() {
        if (!checkUSB())
            return;

        List<Integer> removeList = getRemoveList();

        ((TemplateRemove) myPrint).removeTemplate(removeList, listener);

    }

    /**
     * Called when [Get List] button is tapped
     */
    private void getTemplateButtonOnClick() {
        if (!checkUSB())
            return;

        ((TemplateRemove) myPrint).getTemplateList(listener);

    }

    /**
     * Set template list to ListView
     *
     * @param tmplList template list
     */
    private void setTeamplate(List<TemplateInfo> tmplList) {
        if (tmplList.size() == 0) {
            displayNoFile();
        } else {
            mNoDataTextView.setVisibility(View.GONE);
            mListAdapter = new TemplateListAdapter(mActivity, tmplList);
            mListView.setAdapter(mListAdapter);
            mListView.setVisibility(View.VISIBLE);
            mListAdapter.notifyDataSetChanged();
        }

    }

    /**
     * Show that template does not exist
     */
    private void displayNoFile() {
        mNoDataTextView.setVisibility(View.VISIBLE);
        mListView.setVisibility(View.GONE);
        mListAdapter = null;
    }

    /**
     * Get template list to be deleted
     *
     * @return
     */
    private List<Integer> getRemoveList() {
        if (mListAdapter == null) {
            return null;
        }
        List<TempInfo> list = mListAdapter.getTemplateList();
        List<Integer> enableList = new ArrayList<Integer>();
        for (TempInfo temp : list) {
            if (temp.getEnabled()) {
                enableList.add(temp.getTemplateInfo().key);
            }
        }
        return enableList;
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
