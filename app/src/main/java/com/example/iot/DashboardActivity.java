package com.example.iot;

import android.app.AlertDialog;
import android.content.Intent;
import android.os.Bundle;
import android.widget.*;
import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;
import com.example.iot.models.*;
import com.example.iot.net.*;

import retrofit2.*;

public class DashboardActivity extends AppCompatActivity {
    TextView tvName, tvRfid, tvVehicle;
    String rfid;

    @Override
    protected void onCreate(Bundle b) {
        super.onCreate(b);
        setContentView(R.layout.activity_dashboard);

        tvName = findViewById(R.id.tvName);
        tvRfid = findViewById(R.id.tvRfid);
        tvVehicle = findViewById(R.id.tvVehicle);

        rfid = getSharedPreferences("iot", MODE_PRIVATE).getString("rfid", null);
        if (rfid == null) {
            startActivity(new Intent(this, LoginActivity.class));
            finish();
            return;
        }

        findViewById(R.id.btnCheckBalance).setOnClickListener(v -> showBalanceDialog());

        CardView cardRecharge = findViewById(R.id.cardRecharge);
        cardRecharge.setOnClickListener(v ->
                startActivity(new Intent(this, RechargeActivity.class)));

        CardView cardAboutCar = findViewById(R.id.cardAboutCar);
        cardAboutCar.setOnClickListener(v ->
                startActivity(new Intent(this, AboutCarActivity.class)));

        findViewById(R.id.btnLogout).setOnClickListener(v -> {
            getSharedPreferences("iot", MODE_PRIVATE).edit().clear().apply();
            startActivity(new Intent(this, LoginActivity.class));
            finish();
        });

        loadProfile();
    }

    void loadProfile() {
        ApiService api = ApiClient.get().create(ApiService.class);
        api.profile(rfid).enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(Call<ProfileResponse> c, Response<ProfileResponse> r) {
                if (r.isSuccessful() && r.body() != null && r.body().ok) {
                    User p = r.body().profile;
                    tvName.setText("Name: " + p.name);
                    tvRfid.setText("RFID: " + p.rfid_number);
                    tvVehicle.setText("Vehicle: " + p.vehicle_number);
                }
            }

            @Override
            public void onFailure(Call<ProfileResponse> c, Throwable t) {}
        });
    }

    void showBalanceDialog() {
        ApiService api = ApiClient.get().create(ApiService.class);
        api.balance(rfid).enqueue(new Callback<BalanceResponse>() {
            @Override
            public void onResponse(Call<BalanceResponse> call, Response<BalanceResponse> r) {
                if (r.isSuccessful() && r.body() != null && r.body().ok) {
                    new AlertDialog.Builder(DashboardActivity.this)
                            .setTitle("Current Balance")
                            .setMessage("Balance: " + r.body().balance)
                            .setPositiveButton("OK", null)
                            .show();
                } else {
                    Toast.makeText(DashboardActivity.this, "Failed to fetch balance", Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<BalanceResponse> call, Throwable t) {
                Toast.makeText(DashboardActivity.this, t.getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }
}
