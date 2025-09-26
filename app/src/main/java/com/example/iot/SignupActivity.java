package com.example.iot;

import android.os.Bundle;
import android.widget.*;
import androidx.appcompat.app.AppCompatActivity;
import com.example.iot.models.BasicResponse;
import com.example.iot.net.ApiClient;
import com.example.iot.net.ApiService;
import retrofit2.*;

public class SignupActivity extends AppCompatActivity {
    EditText etName, etEmail, etPhone, etVehicle, etPass;
    @Override protected void onCreate(Bundle b){
        super.onCreate(b); setContentView(R.layout.activity_signup);
        etName=findViewById(R.id.etName); etEmail=findViewById(R.id.etEmail);
        etPhone=findViewById(R.id.etPhone); etVehicle=findViewById(R.id.etVehicle);
        etPass=findViewById(R.id.etPass);
        findViewById(R.id.btnSignup).setOnClickListener(v -> doSignup());
    }
    void doSignup(){
        ApiService api = ApiClient.get().create(ApiService.class);
        api.signup(etName.getText().toString(), etEmail.getText().toString(),
                        etPhone.getText().toString(), etPass.getText().toString(),
                        etVehicle.getText().toString())
                .enqueue(new Callback<BasicResponse>() {
                    @Override public void onResponse(Call<BasicResponse> call, Response<BasicResponse> res){
                        if(res.isSuccessful() && res.body()!=null && res.body().ok){
                            Toast.makeText(SignupActivity.this,
                                    "Request sent. Check email after admin approves.", Toast.LENGTH_LONG).show();
                            finish(); // back to Login
                        }else{
                            Toast.makeText(SignupActivity.this, "Failed: "+
                                    (res.body()!=null?res.body().error:"error"), Toast.LENGTH_LONG).show();
                        }
                    }
                    @Override public void onFailure(Call<BasicResponse> call, Throwable t){
                        Toast.makeText(SignupActivity.this, t.getMessage(), Toast.LENGTH_LONG).show();
                    }
                });
    }
}
