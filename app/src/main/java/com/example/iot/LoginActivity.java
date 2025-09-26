package com.example.iot;

import android.content.Intent;
import android.os.Bundle;
import android.widget.*;
import androidx.appcompat.app.AppCompatActivity;
import com.example.iot.models.LoginResponse;
import com.example.iot.net.*;

import retrofit2.*;

public class LoginActivity extends AppCompatActivity {
    EditText etRfid, etPass;
    @Override protected void onCreate(Bundle b){
        super.onCreate(b); setContentView(R.layout.activity_login);
        etRfid=findViewById(R.id.etRfid); etPass=findViewById(R.id.etPassword);
        findViewById(R.id.btnGoSignup).setOnClickListener(v ->
                startActivity(new Intent(this, SignupActivity.class)));
        findViewById(R.id.btnLogin).setOnClickListener(v -> doLogin());
    }
    void doLogin(){
        ApiService api = ApiClient.get().create(ApiService.class);
        api.login(etRfid.getText().toString(), etPass.getText().toString())
                .enqueue(new Callback<LoginResponse>() {
                    @Override public void onResponse(Call<LoginResponse> c, Response<LoginResponse> r){
                        if(r.isSuccessful() && r.body()!=null && r.body().ok){
                            // Save rfid for later calls
                            getSharedPreferences("iot", MODE_PRIVATE).edit()
                                    .putString("rfid", r.body().user.rfid_number)
                                    .putString("vehicle", r.body().user.vehicle_number)
                                    .apply();
                            startActivity(new Intent(LoginActivity.this, DashboardActivity.class));
                            finish();
                        } else {
                            Toast.makeText(LoginActivity.this, "Login failed", Toast.LENGTH_LONG).show();
                        }
                    }
                    @Override public void onFailure(Call<LoginResponse> c, Throwable t){
                        Toast.makeText(LoginActivity.this, t.getMessage(), Toast.LENGTH_LONG).show();
                    }
                });
    }
}
