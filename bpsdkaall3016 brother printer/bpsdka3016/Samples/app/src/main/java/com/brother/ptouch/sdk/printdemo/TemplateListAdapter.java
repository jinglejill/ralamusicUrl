package com.brother.ptouch.sdk.printdemo;

import android.app.Activity;
import android.text.format.Formatter;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.TextView;

import com.brother.ptouch.sdk.TemplateInfo;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;

class TemplateListAdapter extends BaseAdapter {

    private final LayoutInflater mInflater;
    private final Activity mActivity;
    private List<TempInfo> mTemplateList;

    public TemplateListAdapter(Activity activity,
                               List<TemplateInfo> templateList) {

        creteTemplateList(templateList);
        mActivity = activity;
        mInflater = activity.getLayoutInflater();

    }

    public List<TempInfo> getTemplateList() {
        return mTemplateList;
    }

    public boolean isEnabled(int position) {
        return mTemplateList != null && mTemplateList.get(position).getEnabled();

    }

    public void setList(ArrayList<TemplateInfo> templateList) {
        creteTemplateList(templateList);

    }

    private void creteTemplateList(List<TemplateInfo> templateList) {
        mTemplateList = new ArrayList<TempInfo>();
        for (TemplateInfo temp : templateList) {
            TempInfo tempInfo = new TempInfo();
            tempInfo.setTemplateInfo(temp);
            mTemplateList.add(tempInfo);
        }
    }

    @Override
    public int getCount() {
        return mTemplateList.size();
    }

    @Override
    public Object getItem(int position) {
        return mTemplateList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {

        View view;
        if (convertView == null) {

            view = mInflater.inflate(R.layout.listitem_tempalte, parent, false);
        } else {
            view = convertView;
        }

        TemplateInfo tmpInfo = mTemplateList.get(position).getTemplateInfo();

        mTemplateList.get(position).view = view;
        TextView filename = (TextView) view
                .findViewById(R.id.tv_template_filename);
        TextView fileDate = (TextView) view.findViewById(R.id.tv_template_date);
        TextView tmpKey = (TextView) view.findViewById(R.id.tv_template_key);
        try {
            SimpleDateFormat formatter = new SimpleDateFormat(
                    "yyyy/MM/dd HH:mm:ss");
            fileDate.setText(formatter.format(tmpInfo.modifiedDate) + "  "
                    + Formatter.formatFileSize(mActivity, tmpInfo.fileSize));
        } catch (IllegalArgumentException e) {
            fileDate.setText(" " + "  "
                    + Formatter.formatFileSize(mActivity, tmpInfo.fileSize));
        }

        tmpKey.setText(String.valueOf(tmpInfo.key));
        filename.setText(tmpInfo.fileName);

        CheckBox checkBox = (CheckBox) view.findViewById(R.id.chkbox_select);

        checkBox.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {

            @Override
            public void onCheckedChanged(CompoundButton buttonView,
                                         boolean isChecked) {
                mTemplateList.get(position).isChecked = isChecked;
            }
        });

        checkBox.setChecked(mTemplateList.get(position).isChecked);

        return view;
    }

    /**
     * template information of a list item
     *
     * @author BIL
     */
    public class TempInfo {
        TemplateInfo mTempInfo;
        boolean isChecked = false;

        View view;

        public boolean getEnabled() {

            return isChecked;
        }

        public TemplateInfo getTemplateInfo() {
            return mTempInfo;
        }

        public void setTemplateInfo(TemplateInfo tempInfo) {
            mTempInfo = tempInfo;
        }

    }

}
