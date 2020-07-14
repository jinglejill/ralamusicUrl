/**
 * Base Activity for printing
 *
 * @author Brother Industries, Ltd.
 * @version 2.2
 */

package com.brother.ptouch.sdk.printdemo;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class Activity_ManageTemplate extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_manage_template);

        Button btnTransfer = (Button) this
                .findViewById(R.id.btnTransfer);
        btnTransfer.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                transferTemplateButtonOnClick();

            }
        });


        Button btnRemoveTemplate = (Button) this
                .findViewById(R.id.btnRemoveTemplate);
        btnRemoveTemplate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                removeTemplateButtonOnClick();
            }
        });

    }

    /**
     * Called when [Printer Settings] button is tapped
     */
    private void removeTemplateButtonOnClick() {
        startActivity(new Intent(this, Activity_RemoveTemplate.class));
    }

    /**
     * Called when [Printer Settings] button is tapped
     */
    private void transferTemplateButtonOnClick() {
        startActivity(new Intent(this, Activity_TransferPdz.class));
    }

}