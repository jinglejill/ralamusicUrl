/**
 * ImagePrint for printing
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */
package com.brother.ptouch.sdk.printdemo.printprocess;

import android.content.Context;

import com.brother.ptouch.sdk.PrinterInfo.ErrorCode;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.common.MsgHandle;

import java.util.ArrayList;

public class ImagePrint extends BasePrint {

    private ArrayList<String> mImageFiles;

    public ImagePrint(Context context, MsgHandle mHandle, MsgDialog mDialog) {
        super(context, mHandle, mDialog);
    }

    /**
     * set print data
     */
    public ArrayList<String> getFiles() {
        return mImageFiles;
    }

    /**
     * set print data
     */
    public void setFiles(ArrayList<String> files) {
        mImageFiles = files;
    }

    /**
     * do the particular print
     */
    @Override
    protected void doPrint() {

        int count = mImageFiles.size();

        for (int i = 0; i < count; i++) {

            String strFile = mImageFiles.get(i);

            mPrintResult = mPrinter.printFile(strFile);

            // if error, stop print next files
            if (mPrintResult.errorCode != ErrorCode.ERROR_NONE) {
                break;
            }
        }
    }

}