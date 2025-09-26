package com.example.iot;

import android.os.Bundle;
import android.widget.*;
import androidx.appcompat.app.AppCompatActivity;
import com.example.iot.models.BasicResponse;
import com.example.iot.net.*;

import retrofit2.*;

public class RechargeActivity extends AppCompatActivity {
    @Override protected void onCreate(Bundle b){
        super.onCreate(b); setContentView(R.layout.activity_recharge);
        EditText et = findViewById(R.id.etAmount);
        findViewById(R.id.btnDoRecharge).setOnClickListener(v -> {
            String rfid = getSharedPreferences("iot", MODE_PRIVATE).getString("rfid", null);
            double amt = Double.parseDouble(et.getText().toString());
            ApiService api = ApiClient.get().create(ApiService.class);
            api.recharge(rfid, amt).enqueue(new Callback<BasicResponse>() {
                @Override public void onResponse(Call<BasicResponse> c, Response<BasicResponse> r){
                    if(r.isSuccessful() && r.body()!=null && r.body().ok){
                        Toast.makeText(RechargeActivity.this,"Recharged",Toast.LENGTH_LONG).show();
                        finish();
                    }else Toast.makeText(RechargeActivity.this,"Failed",Toast.LENGTH_LONG).show();
                }
                @Override public void onFailure(Call<BasicResponse> c, Throwable t){
                    Toast.makeText(RechargeActivity.this,t.getMessage(),Toast.LENGTH_LONG).show();
                }
            });
        });
    }
}
