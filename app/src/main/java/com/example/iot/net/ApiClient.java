package com.example.iot.net;

import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class ApiClient {
    // Use PC IP on a real phone; use 10.0.2.2 on emulator
    private static final String BASE_URL = "http://192.168.0.101/mydb/"; // change to 10.0.2.2 for emulator

    private static Retrofit retrofit;
    public static Retrofit get(){
        if(retrofit == null){
            HttpLoggingInterceptor log = new HttpLoggingInterceptor();
            log.setLevel(HttpLoggingInterceptor.Level.BODY);
            OkHttpClient client = new OkHttpClient.Builder().addInterceptor(log).build();

            retrofit = new Retrofit.Builder()
                    .baseUrl(BASE_URL)
                    .addConverterFactory(GsonConverterFactory.create())
                    .client(client)
                    .build();
        }
        return retrofit;
    }
}
