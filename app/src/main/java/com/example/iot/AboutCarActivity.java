package com.example.iot;

import android.os.Bundle;
import android.widget.*;
import androidx.appcompat.app.AppCompatActivity;
import com.example.iot.models.BasicResponse;
import com.example.iot.net.*;

import retrofit2.*;

public class AboutCarActivity extends AppCompatActivity {
    @Override
    protected void onCreate(Bundle b) {
        super.onCreate(b);
        setContentView(R.layout.activity_about_car);

        EditText et = findViewById(R.id.etMsg);

        findViewById(R.id.btnSend).setOnClickListener(v -> {
            String rfid = getSharedPreferences("iot", MODE_PRIVATE).getString("rfid", null);

            ApiService api = ApiClient.get().create(ApiService.class);
            api.reportLost(rfid, et.getText().toString())   // ✅ only rfid + message
                    .enqueue(new Callback<BasicResponse>() {
                        @Override
                        public void onResponse(Call<BasicResponse> c, Response<BasicResponse> r) {
                            if (r.isSuccessful() && r.body() != null && r.body().ok) {
                                Toast.makeText(AboutCarActivity.this, "Reported to admin", Toast.LENGTH_LONG).show();
                                finish();
                            } else {
                                Toast.makeText(AboutCarActivity.this, "Failed", Toast.LENGTH_LONG).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<BasicResponse> c, Throwable t) {
                            Toast.makeText(AboutCarActivity.this, t.getMessage(), Toast.LENGTH_LONG).show();
                        }
                    });
        });
    }
}
