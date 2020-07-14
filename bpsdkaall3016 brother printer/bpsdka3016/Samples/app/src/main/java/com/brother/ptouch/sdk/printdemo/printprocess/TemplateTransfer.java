/**
 * TemplateTransfer for printing
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo.printprocess;

import android.content.Context;
import android.os.Message;

import com.brother.ptouch.sdk.BatteryInfo;
import com.brother.ptouch.sdk.PrinterInfo;
import com.brother.ptouch.sdk.PrinterStatus;
import com.brother.ptouch.sdk.printdemo.common.Common;
import com.brother.ptouch.sdk.printdemo.common.MsgDialog;
import com.brother.ptouch.sdk.printdemo.common.MsgHandle;

public class TemplateTransfer extends BasePrint {

    private String mPdzFile;

    public TemplateTransfer(Context context, MsgHandle mHandle,
                            MsgDialog mDialog) {
        super(context, mHandle, mDialog);
    }

    /**
     * Launch the thread to transfer
     */
    public void transfer() {
        mCancel = false;
        TransferThread transfer = new TransferThread(0);
        transfer.start();
    }

    /**
     * Launch the thread to transfer
     */
    public void updateFirm() {
        mCancel = false;
        TransferThread transfer = new TransferThread(1);
        transfer.start();
    }

    /**
     * Launch the thread to transfer
     */
    public void sendFile() {
        mCancel = false;
        TransferThread transfer = new TransferThread(2);

        transfer.start();
    }

    /**
     * Launch the thread to transfer
     */
    public void sendDatabase() {
        mCancel = false;
        TransferThread transfer = new TransferThread(3);

        transfer.start();
    }

    /**
     * Launch the thread to transfer
     */
    public void getFirmVer() {
        FirmVersionThread verTh = new FirmVersionThread(0);
        verTh.start();
    }

    /**
     * Launch the thread to transfer
     */
    public void getMediaVer() {
        FirmVersionThread verTh = new FirmVersionThread(1);
        verTh.start();
    }

    /**
     * Launch the thread to transfer
     */
    public void getSerialNum() {
        FirmVersionThread verTh = new FirmVersionThread(2);
        verTh.start();
    }

    /**
     * Launch the thread to transfer
     */
    public void getMediaFileVer() {
        FirmVersionThread verTh = new FirmVersionThread(4);
        verTh.start();
    }

    /**
     * Launch the thread to transfer
     */
    public void getFirmFileVer() {
        FirmVersionThread verTh = new FirmVersionThread(3);
        verTh.start();
    }

    /**
     * Launch the thread to transfer
     */
    public void getSystemReport() {
        FirmVersionThread verTh = new FirmVersionThread(5);
        verTh.start();
    }

    /**
     * Launch the thread to getBatteryInfo
     */
    public void getBatteryInfo() {
        FirmVersionThread verTh = new FirmVersionThread(6);
        verTh.start();
    }

    // set the print data
    public void setFile(String file) {
        mPdzFile = file;
    }

    @Override
    protected void doPrint() {
    }

    /**
     * Thread for transferring
     */
    private class TransferThread extends Thread {

        int mode = 0;

        public TransferThread(int mode) {
            this.mode = mode;
        }

        @Override
        public void run() {

            // set info. for printing
            setPrinterInfo();

            // start message
            Message msg = mHandle.obtainMessage(Common.MSG_TRANSFER_START);
            mHandle.sendMessage(msg);
            mHandle.setFunction(MsgHandle.FUNC_TRANSFER);

            mPrintResult = new PrinterStatus();
            if (!mCancel) {
                if (mode == 0) {
                    mPrintResult = mPrinter.transfer(mPdzFile);
                } else if (mode == 1) {
                    mPrintResult = mPrinter.updateFirm(mPdzFile);
                } else if (mode == 2) {
                    mPrintResult = mPrinter.sendBinaryFile(mPdzFile);
                } else if (mode == 3) {
                    mPrintResult = mPrinter.sendDatabase(mPdzFile);
                }
            } else {
                mPrintResult.errorCode = PrinterInfo.ErrorCode.ERROR_CANCEL;
            }
            // end message
            mHandle.setResult(showResult());
            mHandle.setBattery(getBatteryDetail());

            msg = mHandle.obtainMessage(Common.MSG_PRINT_END);
            mHandle.sendMessage(msg);
        }
    }

    public String getBatteryInfoDetail(BatteryInfo batteryInfo) {
        String healthStatusString;
        switch (batteryInfo.batteryHealthStatus) {
            case Excellent:
                healthStatusString = "Excellent";
                break;
            case Good:
                healthStatusString = "Good";
                break;
            case ReplaceSoon:
                healthStatusString = "Replace Soon";
                break;
            case ReplaceBattery:
                healthStatusString = "Replace Battery";
                break;
            case NotInstalled:
            default:
                healthStatusString = "Not Installed";
        }
        String batteryInfoString = String.format(java.util.Locale.US, "ChargeLevel: %d%%\nBattery Health: %d%%(%s)",
                batteryInfo.batteryChargeLevel,
                batteryInfo.batteryHealthLevel,
                healthStatusString);
        return batteryInfoString;
    }

    /**
     * Thread for transferring
     */
    private class FirmVersionThread extends Thread {
        int mode = 0;

        public FirmVersionThread(int mode) {
            this.mode = mode;
        }

        @Override
        public void run() {

            // set info. for printing
            setPrinterInfo();

            // start message
            Message msg = mHandle.obtainMessage(Common.MSG_DATA_SEND_START);
            mHandle.sendMessage(msg);
            String firmVer;
            if (mode == 0) {
                firmVer = mPrinter.getFirmVersion();
            } else if (mode == 1) {
                firmVer = mPrinter.getMediaVersion();
            } else if (mode == 2) {
                firmVer = mPrinter.getSerialNumber();
            } else if (mode == 3) {
                firmVer = mPrinter.getFirmFileVer(mPdzFile);
            } else if (mode == 4) {
                firmVer = mPrinter.getMediaFileVer(mPdzFile);
            } else if (mode == 5) {
                firmVer = mPrinter.getSystemReport();
            } else if (mode == 6) {
                BatteryInfo getInfo = mPrinter.getBatteryInfo();
                firmVer = getBatteryInfoDetail(getInfo);
            } else {
                firmVer = "internal error";
            }
            // end message
            mHandle.setResult(firmVer);

            msg = mHandle.obtainMessage(Common.MSG_GET_FIRM);
            mHandle.sendMessage(msg);
        }
    }
}