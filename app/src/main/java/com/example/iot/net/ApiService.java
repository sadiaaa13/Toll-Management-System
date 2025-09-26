package com.example.iot.net;

import com.example.iot.models.*;
import retrofit2.Call;
import retrofit2.http.*;

import java.util.Map;

public interface ApiService {
    @FormUrlEncoded @POST("signup_request.php")
    Call<BasicResponse> signup(@Field("name") String name,
                               @Field("email") String email,
                               @Field("phone") String phone,
                               @Field("password") String password,
                               @Field("vehicle_number") String vehicle);

    @FormUrlEncoded @POST("login.php")
    Call<LoginResponse> login(@Field("rfid_number") String rfid,
                              @Field("password") String password);

    @GET("get_profile.php")
    Call<ProfileResponse> profile(@Query("rfid_number") String rfid);

    @GET("refresh_balance.php")
    Call<BalanceResponse> balance(@Query("rfid_number") String rfid);

    @FormUrlEncoded @POST("recharge.php")
    Call<BasicResponse> recharge(@Field("rfid_number") String rfid,
                                 @Field("amount") double amount);

    @FormUrlEncoded
    @POST("report_lost_car.php")
    Call<BasicResponse> reportLost(
            @Field("rfid_number") String rfid,
            @Field("message") String message
    );
}
