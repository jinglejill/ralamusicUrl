/**
 * TemplatePrint for printing
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo.printprocess;

import android.content.Context;

import com.brother.ptouch.sdk.PrinterInfo.ErrorCode;
import com.brother.ptouch.sdk.printdemo.common.Common;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.common.MsgHandle;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class TemplatePrint extends BasePrint {

    private ArrayList<HashMap<String, Object>> mPrintData = null;
    private String mEncoding = null;

    public TemplatePrint(Context context, MsgHandle mHandle, MsgDialog mDialog) {
        super(context, mHandle, mDialog);
    }

    /**
     * set print data
     */
    public void setPrintData(ArrayList<HashMap<String, Object>> list) {

        mPrintData = list;
    }

    /**
     * set encode for startPTTPrint
     */
    public void setEncoding(String encoding) {

        if (encoding.equalsIgnoreCase(Common.ENCODING_JPN)) {
            mEncoding = "SJIS";
        } else if (encoding.equalsIgnoreCase(Common.ENCODING_CHN)) {
            mEncoding = "GB18030";
        } else {
            mEncoding = null;
        }

    }

    /**
     * do the particular print
     */
    @Override
    protected void doPrint() {

        int count = mPrintData.size();
        Map<String, Object> mapData;
        boolean printError = false;

        for (int i = 0; i < count && !mCancel; i++) {
            mapData = mPrintData.get(i);
            switch (Integer.parseInt(mapData.get(Common.TEMPLATE_REPLACE_TYPE)
                    .toString())) {
                case Common.TEMPLATE_REPLACE_TYPE_START: // start for the pdz print
                    int templateKey = Integer.parseInt(mapData.get(
                            Common.TEMPLATE_KEY).toString());
                    mPrinter.startPTTPrint(templateKey, mEncoding);

                    break;
                case Common.TEMPLATE_REPLACE_TYPE_END: // end for the pdz print
                    mPrintResult = mPrinter.flushPTTPrint();

                    // if error, stop the next print
                    if (mPrintResult.errorCode != ErrorCode.ERROR_NONE) {
                        printError = true;
                    }
                    break;

                case Common.TEMPLATE_REPLACE_TYPE_TEXT: // replaceText
                    mPrinter.replaceText(mapData.get(Common.TEMPLATE_REPLACE_TEXT)
                            .toString());
                    break;

                case Common.TEMPLATE_REPLACE_TYPE_INDEX: // replaceTextIndex
                    mPrinter.replaceTextIndex(
                            mapData.get(Common.TEMPLATE_REPLACE_TEXT).toString(),
                            Integer.parseInt(mapData.get(
                                    Common.TEMPLATE_OBJECTNAME_INDEX).toString()));
                    break;

                case Common.TEMPLATE_REPLACE_TYPE_NAME: // replaceTextName
                    mPrinter.replaceTextName(
                            mapData.get(Common.TEMPLATE_REPLACE_TEXT).toString(),
                            mapData.get(Common.TEMPLATE_OBJECTNAME_INDEX)
                                    .toString());
                    break;

                default:
                    break;
            }

            if (printError) {
                break;
            }
        }
        if (mCancel && ErrorCode.ERROR_NONE == mPrintResult.errorCode) {
            mPrintResult.errorCode = ErrorCode.ERROR_CANCEL;
        }
    }

}